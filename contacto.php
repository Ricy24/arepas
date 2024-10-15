<?php
session_start(); // Asegúrate de iniciar sesión

$host = 'localhost'; // O el nombre de tu servidor
$user = 'root';      // O el nombre de usuario de tu base de datos
$password = '';      // O la contraseña de tu base de datos
$dbname = 'arepas';  // Nombre de tu base de datos

// Crear conexión
$mysqli = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Procesar el formulario de contacto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'] ?? ''; // 'contacto' o 'peticion'
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    // Validación simple
    if (empty($tipo) || empty($nombre) || empty($email) || empty($mensaje)) {
        $error = 'Por favor complete todos los campos.';
    } else {
        // Preparar la consulta
        $sql = "INSERT INTO contacto (nombre, email, mensaje, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            // Enlazar parámetros
            $stmt->bind_param('ssss', $nombre, $email, $mensaje, $tipo);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Redirigir a una página de confirmación
                header('Location: confirmacion_contacto.php');
                exit();
            } else {
                $error = 'Hubo un error al procesar su mensaje.';
            }
        } else {
            $error = 'Error en la preparación de la consulta.';
        }

        // Cerrar la declaración
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="includes/styles.css">
    <style>
        .contacto-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
        }
        .contacto-container h2 {
            margin-top: 0;
        }
        .contacto-container form {
            margin-bottom: 20px;
        }
        .contacto-container label {
            display: block;
            margin: 10px 0 5px;
        }
        .contacto-container input,
        .contacto-container textarea,
        .contacto-container select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .contacto-container textarea {
            height: 100px;
        }
        .contacto-container button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #f0ad4e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .contacto-container button:hover {
            background-color: #ec971f;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .map-container {
            margin-top: 20px;
            text-align: center;
        }
        .map-container iframe {
            border: 0;
            width: 100%;
            height: 400px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="contacto-container">
        <h2>Contacto y Peticiones</h2>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="contacto.php" method="post">
            <div>
                <label for="tipo">Tipo de mensaje:</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Selecciona una opción</option>
                    <option value="contacto">Contacto</option>
                    <option value="peticion">Petición</option>
                </select>
            </div>

            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
            </div>

            <button type="submit">Enviar</button>
        </form>

        <h2>Misión</h2>
        <p>Ofrecer a nuestros clientes un producto garantizando, de buena calidad y con una amplia gama en cuanto a recetas y sabores, satisfaciendo sus exigencias y ofreciendo un excelente servicio al cliente personalizado y de respeto.</p>

        <h2>Visión</h2>
        <p>Proporcionar una buena nutrición a los niños, jóvenes y adultos con la seguridad, confianza y tranquilidad que ofrece el producto, recalcando su gran importancia en la gastronomía del país y del mundo.</p>

        <h2>Contacto</h2>
        <p>El número es 3209238268</p>
        <p>Correo: laurismisol@gmail.com</p>
        
        <!-- Google Maps -->
        <div class="map-container">
            <h2>Ubicación</h2>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d317095.37357277455!2d-74.23069012480308!3d4.61193813188555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3b1c1c4d6c123%3A0x2d0d2c3d0b2d0b0!2sBogot%C3%A1!5e0!3m2!1ses!2sco!4v1637785431661!5m2!1ses!2sco"
                allowfullscreen=""
                loading="lazy"
            ></iframe>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
