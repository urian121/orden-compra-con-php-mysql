<?php 
    session_start(); 
    require 'setting/configBD.php'; // Conexión a la BD
    require 'assets/funciones.php'; // Funciones personalizadas

    // Obtener el siguiente número de orden para mostrar
    $siguienteNumeroOrden = obtenerSiguienteNumeroCompra($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Compra Dinámica</title>
    <link rel="icon" href="assets/img/favicon.png" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/home.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center mb-5">
            <div class="col-auto d-flex align-items-center">
            <img src="assets/img/logo.png" alt="Logo" class="img-fluid" style="max-width: 70px;">
            </div>
            <div class="col text-center">
            <h1 class="mb-0 font-weight-bold mt-2">Crear Orden de Compra</h1>
            </div>
        </div>
        <?php include('mensajes.php'); ?>

        <div class="d-flex justify-content-end align-items-center  mb-5">
            <div class="text-right">
                <h4 class="mb-0">
                    <span class="text-muted">N° Orden:</span>
                    <strong><?php echo htmlspecialchars($siguienteNumeroOrden); ?></strong>
                </h4>
            </div>
        </div>

        <div class="mb-3 text-right">
             <button type="button" id="addRow" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Agregar</button>
        </div>

        <form id="ordenCompraForm" method="POST" action="procesar_orden.php"> 
            <div id="itemsContainer">
                <!-- Fila de ítems (la primera se usa como plantilla) -->
                <div class="form-row align-items-center item-row">
                    <div class="col-md-4 form-group">
                        <label>Descripción del producto</label>
                        <input type="text" name="descripcion[]" class="form-control" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Cantidad</label>
                        <input type="number" name="cantidad[]" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>IVA (%)</label>
                        <input type="number" name="iva[]" class="form-control" min="0" value="19">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Precio Unit.</label>
                        <input type="number" name="precio[]" class="form-control" min="0" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <button type="button" class="btn btn-danger mt-4 btn-sm remove-row" style="display:none;">
                            Borrar <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sección de Totales -->
            <?php include('subtotal.php'); ?>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg">Crear nueva orden <i class="bi bi-arrow-right"></i></button>
            </div>
        </form>
    </div>

    <!-- Botón Flotante para ir a Lista de Órdenes -->
    <a href="lista_ordenes.php" class="btn btn-info btn-flotante" title="Ver Lista de Órdenes">
        <i class="bi bi-list-ul"></i>
    </a>

    <!-- Font Awesome para el icono del botón (opcional pero mejora la UI) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
    <!-- jQuery y Bootstrap Bundle (incluye Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/home.js"></script>
</body>
</html>
<?php
    // Es buena práctica cerrar la conexión si ya no se va a usar en esta página.
    // Sin embargo, si tienes más lógica PHP abajo que la necesite, muévela al final.
    if (isset($conn)) {
        $conn->close();
    }
?>