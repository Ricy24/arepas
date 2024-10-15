<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

// Incluir archivo de configuración de la base de datos
require 'config/db.php';

// Inicializa un mensaje de respuesta
$message = '';

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];

    // Verifica que todos los campos estén completos
    if ($nombre && $descripcion && $precio && $imagen) {
        // Mueve la imagen a la carpeta de imágenes
        $target_dir = "productos/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            // Insertar el producto en la base de datos
            $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $nombre, $descripcion, $precio, $imagen);

            if ($stmt->execute()) {
                $message = "Producto agregado exitosamente.";
            } else {
                $message = "Error al agregar el producto: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "Error al subir la imagen.";
        }
    } else {
        $message = "Por favor complete todos los campos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto - Mr. Peto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <section class="form-container">
            <h2>Agregar Nuevo Producto</h2>
            <form action="agregar_producto.php" method="post" enctype="multipart/form-data">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required>

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>

                <button type="submit">Agregar Producto</button>
            </form>

            <?php if ($message): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
