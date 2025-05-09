<?php
require 'setting/configBD.php'; // Conexión a la BD
require 'functions/funciones.php'; // Funciones personalizadas

// Obtener todas las órdenes
$listaDeOrdenes = obtenerTodasLasOrdenes($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Órdenes de Compra con PHP y MySQL</title>
    <link rel="icon" href="assets/img/favicon.png" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="mr-3">
                <a href="javascript:history.back()" class="btn btn-outline-secondary" title="Volver atrás">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="flex-grow-1 text-center">
                <h1 class="mb-0 font-weight-bold mt-2">Listado de Órdenes de Compra</h1>
            </div>
            <div class="ml-3">
                <a href="./" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nueva Orden</a>
            </div>
            </div>
        </div>


        <?php if (!empty($listaDeOrdenes)): ?>
            <div class="table-responsive">
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
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No hay órdenes de compra registradas todavía. <a href="index.php">Crear una nueva orden</a>.
            </div>
        <?php endif; ?>
    </div>


</body>
</html>