<?php
session_start();
require 'setting/configBD.php';

// Ruta a autoload.php de DOMPDF. Ajusta si es diferente (ej. 'vendor/autoload.php')
require 'dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID de orden no proporcionado.");
}

$idOrden = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($idOrden === false) {
    die("Error: ID de orden inválido.");
}

$idOrdenEscapado = $conn->real_escape_string($idOrden);

// --- Obtener datos de la orden --- 
// Columnas asumidas: id, numero_de_compra, fecha_creacion, estado, subtotal, iva_total, total_pagar
$sqlOrden = "SELECT * FROM ordenes_compra WHERE id = '{$idOrdenEscapado}'";
$resultOrden = $conn->query($sqlOrden);

if (!$resultOrden || $resultOrden->num_rows === 0) {
    die("Error: Orden no encontrada o error en consulta: " . $conn->error);
}
$orden = $resultOrden->fetch_assoc();
$resultOrden->free();

// --- Obtener productos/detalles de la orden --- 
// Columnas asumidas en detalle_orden_compra: id_orden_compra, producto_nombre, descripcion, cantidad, precio_unitario, iva_producto
$sqlDetalles = "SELECT * FROM detalle_orden_compra WHERE orden_compra_id = '{$idOrdenEscapado}'";
$resultDetalles = $conn->query($sqlDetalles);

if (!$resultDetalles) {
    die("Error en la consulta de detalles de orden: " . $conn->error);
}
$detalles = [];
while ($row = $resultDetalles->fetch_assoc()) {
    $detalles[] = $row;
}
$resultDetalles->free();

// Configuración de DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// --- Generar HTML para el PDF (versión más corta) --- 
$html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Orden No. ' . htmlspecialchars($orden['numero_de_compra']) . '</title>';
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

// Información de tu empresa (Personalizar)
$html .= '<div class="company-info">
            <strong>[Nombre de tu Empresa]</strong><br>
            [Tu NIT/RUT]<br>
            [Tu Dirección] - Tel: [Tu Teléfono]
          </div>';

$html .= '<h1>Orden de Compra</h1>';
$html .= '<div class="order-meta"><strong>Número:</strong> ' . htmlspecialchars($orden['numero_de_compra']) . 
           ' | <strong>Fecha:</strong> ' . htmlspecialchars(date('d/m/Y', strtotime($orden['fecha_creacion']))) . 
           ' | <strong>Estado:</strong> ' . htmlspecialchars($orden['estado']) . '</div>';

// Tabla de Productos/Detalles
if (!empty($detalles)) {
    $html .= '<table><thead><tr>
                        <th>Producto/Servicio</th>
                        <th>Descripción</th>
                        <th class="text-right">Cant.</th>
                        <th class="text-right">P. Unit.</th>
                        <th class="text-right">IVA (%)</th>
                        <th class="text-right">Total Item</th>
                    </tr></thead><tbody>';
    foreach ($detalles as $detalle) {
        $precioUnitario = floatval($detalle['precio_unitario']);
        $cantidad = floatval($detalle['cantidad']);
        $ivaPorcentaje = floatval($detalle['iva']);
        $subtotalItem = $cantidad * $precioUnitario;
        $totalItem = $subtotalItem * (1 + ($ivaPorcentaje / 100));
        
        $html .= '<tr>
                    <td>' . htmlspecialchars($detalle['descripcion']) . '</td>
                    <td>' . htmlspecialchars(isset($detalle['descripcion_larga']) ? $detalle['descripcion_larga'] : '') . '</td>
                    <td class="text-right">' . htmlspecialchars(number_format($cantidad, 2, ',', '.')) . '</td>
                    <td class="text-right">' . htmlspecialchars(number_format($precioUnitario, 2, ',', '.')) . '</td>
                    <td class="text-right">' . htmlspecialchars(number_format($ivaPorcentaje, 0, ',', '.')) . '%</td>
                    <td class="text-right">' . htmlspecialchars(number_format($totalItem, 2, ',', '.')) . '</td>
                  </tr>';
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>No hay detalles para esta orden.</p>';
}

// Totales Generales de la Orden (usando los campos de la tabla ordenes_compra)
$html .= '<table class="totals-table">
            <tr><td>Subtotal:</td><td class="text-right">' . htmlspecialchars(number_format(floatval($orden['subtotal']), 2, ',', '.')) . '</td></tr>
            <tr><td>IVA Total:</td><td class="text-right">' . htmlspecialchars(number_format(floatval($orden['iva_total']), 2, ',', '.')) . '</td></tr>
            <tr><td><strong>Total a Pagar:</strong></td><td class="text-right"><strong>' . htmlspecialchars(number_format(floatval($orden['total_pagar']), 2, ',', '.')) . '</strong></td></tr>
          </table>';

$html .= '<div class="footer">Documento generado el ' . date('d/m/Y H:i:s') . '</div>';
$html .= '</body></html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

if (ob_get_level()) {
    ob_end_clean();
}

$nombreArchivo = 'Orden_' . preg_replace('/[^A-Za-z0-9_-]/', '', $orden['numero_de_compra']) . '.pdf';
$dompdf->stream($nombreArchivo, ['Attachment' => false]);

if (isset($conn)) {
    $conn->close();
}
exit;
?>
