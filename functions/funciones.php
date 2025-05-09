<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

function obtenerSiguienteNumeroCompra($conn) {
    $sql = "SELECT MAX(numero_de_compra) as ultimo_numero FROM ordenes_compra";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ultimo_numero = $row['ultimo_numero'];
        
        if ($ultimo_numero !== null) {
            $siguiente_numero_int = (int)$ultimo_numero + 1;
            return str_pad($siguiente_numero_int, 4, '0', STR_PAD_LEFT);
        } else {
            return "0001";
        }
    } else {
        return "0001";
    }
}



/**
 * Obtiene todas las órdenes de compra de la base de datos.
 */
function obtenerTodasLasOrdenes($conn) {
    $sql = "SELECT id, numero_de_compra, fecha_creacion, estado, subtotal, iva_total, total_pagar 
            FROM ordenes_compra 
            ORDER BY fecha_creacion DESC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


/**
 * Obtiene los detalles de una orden de compra específica.
 */

 function obtenerDetallesOrden($conn, $idOrden) {
    $id = (int) $idOrden;

    $cabecera = $conn->query("SELECT * FROM ordenes_compra WHERE id = $id")->fetch_assoc();
    if (!$cabecera) return null;

    $detalles = $conn->query("SELECT * FROM detalle_orden_compra WHERE orden_compra_id = $id")->fetch_all(MYSQLI_ASSOC);

    return ['cabecera' => $cabecera, 'detalles' => $detalles];
}

?>