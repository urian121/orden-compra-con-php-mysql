<?php
require 'setting/configBD.php';
require 'functions/funciones.php'; // Funciones personalizadas
require 'dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Validar ID
$idOrden = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$idOrden) {
    header("Location: index.php");
    exit;
}

// Obtener cabecera y detalles
$dataOrden = obtenerDetallesOrden($conn, $idOrden);
if (!$dataOrden) {
    exit("Orden no encontrada.");
}

$cabecera = $dataOrden['cabecera'];
$detalles = $dataOrden['detalles'];

// Configuración de DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// Generar HTML del PDF
$html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Orden No. ' . htmlspecialchars($cabecera['numero_de_compra']) . '</title>';
$html .= '<style>
    body { font-family: Arial, sans-serif; font-size: 10px; margin: 15mm; }
    h1 { text-align: center; color: #333; font-size: 18px; margin-bottom: 5px; }
    .order-meta { text-align: center; margin-bottom: 15px; font-size: 9px; color: #555; }
    .company-info { text-align: center; margin-bottom:15px; font-size: 9px; }
    .company-info strong {font-size: 11px; color: #000;}
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
    th { background-color: #f2f2f2; font-weight: bold; }
    .text-right { text-align: right; }
    .totals-table { width: 40%; margin-left: 60%; }
    .totals-table td { font-weight: bold; }
    .footer { text-align: center; font-size: 8px; color: #888; position: fixed; bottom: -10mm; left: 0mm; right: 0mm; }
</style></head><body>';

// Datos empresa
$html .= '<div class="company-info">
    <strong>[Nombre de tu Empresa]</strong><br>
    [Tu NIT/RUT]<br>
    [Tu Dirección] - Tel: [Tu Teléfono]
</div>';

$html .= '<h1>Orden de Compra</h1>';
$html .= '<div class="order-meta"><strong>Número:</strong> ' . htmlspecialchars($cabecera['numero_de_compra']) . 
         ' | <strong>Fecha:</strong> ' . htmlspecialchars(date('d/m/Y', strtotime($cabecera['fecha_creacion']))) . 
         ' | <strong>Estado:</strong> ' . htmlspecialchars($cabecera['estado']) . '</div>';

// Tabla de productos
if (!empty($detalles)) {
    $html .= '<table><thead><tr>
        <th>Descripción</th>
        <th class="text-right">Cant.</th>
        <th class="text-right">P. Unit.</th>
        <th class="text-right">IVA (%)</th>
        <th class="text-right">Total Item</th>
    </tr></thead><tbody>';

    foreach ($detalles as $detalle) {
        $precioUnitario = (float)$detalle['precio_unitario'];
        $cantidad = (float)$detalle['cantidad'];
        $iva = (float)$detalle['iva'];
        $subtotal = $cantidad * $precioUnitario;
        $totalItem = $subtotal * (1 + $iva / 100);

        $html .= '<tr>
            <td>' . htmlspecialchars($detalle['descripcion']) . '</td>
            <td class="text-right">' . number_format($cantidad, 2, ',', '.') . '</td>
            <td class="text-right">' . number_format($precioUnitario, 2, ',', '.') . '</td>
            <td class="text-right">' . number_format($iva, 0, ',', '.') . '%</td>
            <td class="text-right">' . number_format($totalItem, 2, ',', '.') . '</td>
        </tr>';
    }

    $html .= '</tbody></table>';
} else {
    $html .= '<p>No hay productos para esta orden.</p>';
}

// Totales
$html .= '<table class="totals-table">
    <tr><td>Subtotal:</td><td class="text-right">' . number_format((float)$cabecera['subtotal'], 2, ',', '.') . '</td></tr>
    <tr><td>IVA Total:</td><td class="text-right">' . number_format((float)$cabecera['iva_total'], 2, ',', '.') . '</td></tr>
    <tr><td><strong>Total a Pagar:</strong></td><td class="text-right"><strong>' . number_format((float)$cabecera['total_pagar'], 2, ',', '.') . '</strong></td></tr>
</table>';

$html .= '<div class="footer">Documento generado el ' . date('d/m/Y H:i:s') . '</div>';
$html .= '</body></html>';

// PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Limpiar el buffer de salida
if (ob_get_level()) ob_end_clean();

$nombreArchivo = 'Orden_' . preg_replace('/[^A-Za-z0-9_-]/', '', $cabecera['numero_de_compra']) . '.pdf';
$dompdf->stream($nombreArchivo, ['Attachment' => false]);
