<?php
session_start();
require 'configBD.php'; // Conexión a la BD
require 'assets/funciones.php'; // Funciones personalizadas

// Obtener todas las órdenes
$listaDeOrdenes = obtenerTodasLasOrdenes($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Órdenes de Compra</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/home.css">
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0 fw-bold">Listado de Órdenes de Compra</h1>
            <a href="index.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nueva Orden</a>
        </div>

        <?php include('mensajes.php'); ?>


        <?php if (!empty($listaDeOrdenes)): ?>
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>N° Orden</th>
                        <th>Fecha Creación</th>
                        <th>Estado</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">IVA Total</th>
                        <th class="text-right">Total Pagar</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaDeOrdenes as $orden): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($orden['numero_de_compra']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($orden['fecha_creacion']))); ?></td>
                            <td><?php echo htmlspecialchars($orden['estado']); ?></td>
                            <td class="text-right"><?php echo htmlspecialchars(number_format($orden['subtotal'], 2, ',', '.')); ?></td>
                            <td class="text-right"><?php echo htmlspecialchars(number_format($orden['iva_total'], 2, ',', '.')); ?></td>
                            <td class="text-right"><strong><?php echo htmlspecialchars(number_format($orden['total_pagar'], 2, ',', '.')); ?></strong></td>
                            <td>
                                <a href="generar_pdf_orden.php?id=<?php echo htmlspecialchars($orden['id']); ?>" class="btn btn-danger btn-sm" title="Generar PDF" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                                <!-- Aquí podrías añadir más botones como Editar, etc. -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No hay órdenes de compra registradas todavía. <a href="index.php">Crear una nueva orden</a>.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
if (isset($conn)) {
    $conn->close();
}
?>