# Sistema de Gestión de Órdenes de Compra con PHP y MySQL

Este proyecto es una aplicación web desarrollada en PHP y MySQL para crear, gestionar y visualizar órdenes de compra. Permite a los usuarios añadir productos, calcular totales con IVA y generar un PDF de la orden de compra.

![Resultado](https://raw.githubusercontent.com/urian121/imagenes-proyectos-github/refs/heads/master/orden-de-compra-php.png)
![Lista de productos](https://raw.githubusercontent.com/urian121/imagenes-proyectos-github/refs/heads/master/lista-ordenes-de-compra-con-php.png)
![Factura en PDF](https://raw.githubusercontent.com/urian121/imagenes-proyectos-github/refs/heads/master/pdf-orden-de-compra-con-php.png)


## Características

*   Creación dinámica de órdenes de compra.
*   Cálculo automático de subtotales, IVA y totales.
*   Listado y visualización de órdenes de compra existentes.
*   Generación de órdenes de compra en formato PDF.
*   Interfaz de usuario amigable construida con Bootstrap.
*   Notificaciones de éxito/error para las acciones del usuario.

## Tecnologías Utilizadas

*   **Backend:** PHP
*   **Base de Datos:** MySQL
*   **Frontend:** HTML, CSS, JavaScript, Bootstrap
*   **Generación de PDF:** Dompdf

## Prerrequisitos

*   Un servidor web (por ejemplo, Apache, Nginx). Se recomienda Laragon, XAMPP, WAMP o MAMP para un entorno de desarrollo local sencillo.
*   PHP (versión 7.x o superior recomendada).
*   Servidor de base de datos MySQL o MariaDB.
*   Un navegador web.

## Instalación y Configuración

1.  **Clonar el repositorio o descargar los archivos:**
    Si es un repositorio Git:
    ```bash
    git clone <URL_DEL_REPOSITORIO> nombre-del-directorio
    cd nombre-del-directorio
    ```
    Si has descargado un archivo ZIP, descomprímelo en el directorio de tu servidor web (por ejemplo, `htdocs` en XAMPP o `www` en Laragon/WAMP).

2.  **Configurar la Base de Datos:**
    *   Crea una nueva base de datos en tu servidor MySQL (puedes usar phpMyAdmin o cualquier otro cliente de MySQL).
    *   Importa el archivo `bd/orden_de_compra.sql` en la base de datos que acabas de crear. Esto creará las tablas necesarias y algunos datos de ejemplo si los incluye.

3.  **Configurar la Conexión a la Base de Datos:**
    *   Abre el archivo `configBD.php`.
    *   Modifica los siguientes parámetros con los detalles de tu conexión a la base de datos:
        ```php
        $servername = "localhost"; // O la IP de tu servidor de BD
        $username = "tu_usuario_de_bd";
        $password = "tu_contraseña_de_bd";
        $dbname = "nombre_de_tu_bd"; // El nombre de la BD que creaste en el paso 2
        ```

4.  **Acceder a la Aplicación:**
    *   Abre tu navegador web y navega a la URL donde has alojado el proyecto. Por ejemplo:
        *   Si usas Laragon: `http://orden-de-compra-con-php-y-mysql.test` (o el nombre que hayas configurado)
        *   Si usas XAMPP/WAMP: `http://localhost/nombre-del-directorio-del-proyecto/`

## Uso

1.  **Crear una Nueva Orden de Compra:**
    *   Navega a la página principal (`index.php`).
    *   El número de orden se generará automáticamente.
    *   Haz clic en el botón "Agregar" para añadir filas de productos.
    *   Para cada producto, completa la descripción, cantidad, IVA (%) y precio unitario.
    *   Puedes eliminar filas de productos usando el botón "Borrar".
    *   Una vez completados todos los ítems, haz clic en "Crear nueva orden".

2.  **Ver Lista de Órdenes:**
    *   Haz clic en el botón flotante con el icono de lista en la esquina inferior derecha o navega directamente a `lista_ordenes.php`.
    *   Aquí verás un listado de todas las órdenes de compra creadas.
    *   Podrás ver detalles o generar el PDF para cada orden.

3.  **Generar PDF:**
    *   Desde la lista de órdenes, usualmente habrá un botón o enlace para generar el PDF de una orden específica. Esto utilizará `generar_pdf_orden.php`.

## Estructura del Proyecto

```
.
├── assets/                 # Archivos estáticos (CSS, JS, imágenes)
│   ├── funciones.php       # Funciones PHP personalizadas
│   ├── home.css            # Estilos CSS para la página principal
│   ├── home.js             # Lógica JavaScript para la página principal
│   └── img/                # Imágenes (favicon, logo)
├── bd/                     # Archivos relacionados con la base de datos
│   └── orden_de_compra.sql # Script SQL para la estructura de la BD
├── dompdf/                 # Librería Dompdf para generación de PDF
├── configBD.php            # Configuración de la conexión a la BD
├── generar_pdf_orden.php   # Script para generar el PDF de una orden
├── index.php               # Página principal para crear órdenes
├── lista_ordenes.php       # Página para listar las órdenes existentes
├── mensajes.php            # Script para mostrar mensajes al usuario
├── procesar_orden.php      # Script para procesar y guardar una nueva orden
├── README.md               # Este archivo
└── subtotal.php            # Script para mostrar la sección de totales
```

## Contribuciones

Las contribuciones son bienvenidas. Si deseas mejorar este proyecto, por favor considera hacer un fork del repositorio y enviar un pull request.


### Expresiones de Gratitud 🎁

    ¡Comparte este emocionante proyecto con los demás! 📢
    Apóyanos con una cerveza 🍺 o un reconfortante café ☕
    Contribuye a través de PayPal: iamdeveloper86@gmail.com
    Expresa tus agradecimientos en público 🤓 ¡Te lo agradeceremos enormemente!

## ¡No dejes pasar la oportunidad de SUSCRIBIRTE! 👍