<?php
// Inicia sesión si es necesario
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - Mr. Peto</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        main {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        h1, h2, h3 {
            color: #007BFF; /* Azul principal */
        }

        p {
            line-height: 1.6;
        }

        .section-header {
            margin-top: 40px;
            color: #007BFF; /* Azul principal */
        }

        a {
            color: #007BFF; /* Azul principal */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .buttons a {
            background-color: #007BFF; /* Azul principal */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .buttons a:hover {
            background-color: #0056b3; /* Azul más oscuro al hacer hover */
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <h1>Términos y Condiciones</h1>
        <p>Bienvenido a Mr. Peto. Al acceder y utilizar este sitio web, aceptas estar sujeto a los siguientes términos y condiciones que rigen el uso de nuestro sitio y la compra de nuestros productos. Si no estás de acuerdo con alguna de las siguientes condiciones, te pedimos que no utilices nuestro sitio web.</p>

        <h2 class="section-header">1. Información General</h2>
        <p>El presente documento establece los términos y condiciones bajo los cuales ofrecemos los productos y servicios a través de nuestro sitio web. Nos reservamos el derecho de modificar estos términos en cualquier momento, por lo que te sugerimos revisar esta página periódicamente.</p>

        <h2 class="section-header">2. Protección de Datos Personales</h2>
        <p>De acuerdo con la Ley 1581 de 2012 y el Decreto 1377 de 2013 sobre la protección de datos personales en Colombia, Mr. Peto garantiza la privacidad de la información personal que recolectamos de nuestros clientes, tales como nombre, correo electrónico, dirección, número de teléfono, y cualquier otro dato necesario para el proceso de compra.</p>
        <p>El tratamiento de estos datos tiene como fin la correcta ejecución de los pedidos, la facturación, la mejora de nuestros servicios, y el envío de información promocional. Puedes solicitar la eliminación de tus datos personales o la rectificación de los mismos en cualquier momento enviándonos un correo electrónico a <strong>privacidad@mrpeto.com</strong>.</p>

        <h2 class="section-header">3. Uso de Cookies</h2>
        <p>En nuestro sitio web utilizamos cookies para mejorar la experiencia del usuario, facilitando la navegación y recordando tus preferencias. Las cookies son pequeños archivos que se almacenan en tu dispositivo cuando visitas nuestro sitio. Al navegar en Mr. Peto, aceptas el uso de cookies conforme a nuestra política.</p>
        <p>Puedes desactivar el uso de cookies en tu navegador, sin embargo, algunas funcionalidades del sitio pueden verse afectadas.</p>

        <h2 class="section-header">4. Seguridad en el Proceso de Compra</h2>
        <p>Nos comprometemos a garantizar que la información de tu tarjeta de crédito y otros datos sensibles sean tratados de manera segura. Utilizamos sistemas de pago certificados y tecnología SSL (Secure Socket Layer) para asegurar que la transmisión de datos esté cifrada y protegida.</p>

        <h2 class="section-header">5. Registro y Privacidad de la Cuenta</h2>
        <p>Para realizar compras en nuestro sitio web, es necesario crear una cuenta proporcionando información precisa y veraz. Eres responsable de mantener la confidencialidad de tus credenciales de acceso y de todas las actividades que ocurran bajo tu cuenta.</p>
        <p>Si detectas algún uso no autorizado de tu cuenta, notifícanos de inmediato para que podamos tomar las medidas necesarias.</p>

        <h2 class="section-header">6. Política de Devoluciones</h2>
        <p>Debido a la naturaleza de nuestros productos (alimentos), no aceptamos devoluciones una vez entregado el pedido, salvo en casos de productos defectuosos o errores en el envío. Si recibes un producto en mal estado o incorrecto, por favor contáctanos dentro de las primeras 24 horas para gestionar una solución.</p>

        <h2 class="section-header">7. Ley Aplicable</h2>
        <p>Estos términos y condiciones se rigen por las leyes de la República de Colombia. Cualquier disputa que surja en relación con el uso de este sitio web o la compra de nuestros productos será resuelta por los tribunales competentes de Colombia.</p>

        <h2 class="section-header">8. Contacto</h2>
        <p>Si tienes alguna duda sobre nuestros términos y condiciones o sobre el tratamiento de tus datos personales, puedes ponerte en contacto con nosotros en <strong>soporte@mrpeto.com</strong> o llamarnos al número +57 300 1234567.</p>

        <div class="buttons">
            <a href="index.php">Volver a la tienda</a>
            <a href="index.php" class="blue-button">Mr. Peto</a>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
