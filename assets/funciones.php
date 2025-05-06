<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    $ordenes = [];
    $sql = "SELECT id, numero_de_compra, fecha_creacion, estado, subtotal, iva_total, total_pagar 
            FROM ordenes_compra 
            ORDER BY fecha_creacion DESC";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ordenes[] = $row;
        }
    }
    return $ordenes;
}
?>