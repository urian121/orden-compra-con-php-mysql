<?php
session_start();
require 'setting/configBD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $descripciones = $_POST['descripcion'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];
    $ivas = $_POST['iva'] ?? []; // Porcentajes de IVA por ítem
    $precios = $_POST['precio'] ?? [];

    if (empty($descripciones) || empty($cantidades) || empty($precios)) {
        $_SESSION['mensaje_error'] = "Debe agregar al menos un producto con descripción, cantidad y precio.";
        header("Location: index.php");
        exit;
    }

    try {
        // Insertar la orden en 'ordenes_compra'
        $estado_default = 'Pendiente';
        $estado_sql = "'" . $conn->real_escape_string($estado_default) . "'";

        // Inicialmente insertamos sin los totales, ya que se calculan después
        $sql_orden = "INSERT INTO ordenes_compra (estado) VALUES ($estado_sql)";
        
        if (!$conn->query($sql_orden)) {
            throw new Exception("Error al insertar la orden: " . $conn->error);
        }
        
        $orden_compra_id = $conn->insert_id;

        // Generar y actualizar el numero_de_compra
        $numero_de_compra = str_pad($orden_compra_id, 4, '0', STR_PAD_LEFT);
        $numero_de_compra_sql = "'" . $conn->real_escape_string($numero_de_compra) . "'";
        $orden_compra_id_sql = (int)$orden_compra_id;

        $sql_update_numero = "UPDATE ordenes_compra SET numero_de_compra = $numero_de_compra_sql WHERE id = $orden_compra_id_sql";
        if (!$conn->query($sql_update_numero)) {
            throw new Exception("Error al actualizar el número de compra: " . $conn->error);
        }

        // Variables para calcular los totales finales
        $subtotal_final_orden = 0.00;
        $iva_total_final_orden = 0.00;

        // Insertar los detalles de la orden en 'detalle_orden_compra'
        for ($i = 0; $i < count($descripciones); $i++) {
            if (!empty($descripciones[$i]) && isset($cantidades[$i]) && isset($precios[$i])) {
                $descripcion_sql = "'" . $conn->real_escape_string($descripciones[$i]) . "'";
                $cantidad_item = (float)$cantidades[$i];
                $precio_unitario_item = (float)$precios[$i];
                $iva_porcentaje_item = isset($ivas[$i]) ? (float)$ivas[$i] : 0.00;

                // Cálculos por ítem
                $subtotal_item = $cantidad_item * $precio_unitario_item;
                $iva_item = ($subtotal_item * $iva_porcentaje_item) / 100;

                // Acumular para los totales de la orden
                $subtotal_final_orden += $subtotal_item;
                $iva_total_final_orden += $iva_item;

                $sql_detalle = "INSERT INTO detalle_orden_compra (orden_compra_id, descripcion, cantidad, iva, precio_unitario) VALUES ($orden_compra_id_sql, $descripcion_sql, $cantidad_item, $iva_porcentaje_item, $precio_unitario_item)";
                
                if (!$conn->query($sql_detalle)) {
                    throw new Exception("Error al insertar detalle de la orden: " . $conn->error . " en SQL: " . $sql_detalle);
                }
            }
        }

        $total_pagar_final_orden = $subtotal_final_orden + $iva_total_final_orden;

        // Sanitizar y formatear para SQL
        $subtotal_final_sql = (float)$subtotal_final_orden;
        $iva_total_final_sql = (float)$iva_total_final_orden;
        $total_pagar_final_sql = (float)$total_pagar_final_orden;

        // Actualizar la orden principal con los totales calculados
        $sql_update_totales = "UPDATE ordenes_compra SET subtotal = $subtotal_final_sql, iva_total = $iva_total_final_sql, total_pagar = $total_pagar_final_sql WHERE id = $orden_compra_id_sql";
        
        if (!$conn->query($sql_update_totales)) {
            throw new Exception("Error al actualizar los totales de la orden: " . $conn->error);
        }

        $_SESSION['mensaje_exito'] = "Orden de compra N° " . htmlspecialchars($numero_de_compra) . " creada exitosamente.";

    } catch (Exception $e) {
        $_SESSION['mensaje_error'] = "Error al crear la orden de compra: " . $e->getMessage();
    }

    header("Location: index.php");
    exit;
} else {
    $_SESSION['mensaje_error'] = "Método de solicitud no válido.";
    header("Location: index.php");
    exit;
}
?>