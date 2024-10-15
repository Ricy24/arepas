<?php
// Inicia sesión
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

// Conectar a la base de datos
require 'config/db.php'; // Ajusta la ruta para que apunte a la carpeta config

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];
    $stock = $_POST['stock']; // Nuevo campo para el stock

    // Verifica que todos los campos estén completos
    if ($nombre && $descripcion && $precio && $imagen && $stock) {
        // Mueve la imagen a la carpeta de imágenes
        $target_dir = "productos/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);

        // Insertar el producto en la base de datos, incluyendo el stock
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, stock) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisi", $nombre, $descripcion, $precio, $imagen, $stock);

        if ($stmt->execute()) {
            $message = "Producto agregado exitosamente.";
        } else {
            $message = "Error al agregar el producto.";
        }

        $stmt->close();
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
    <style>


        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
        }

        input, textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="file"] {
            padding: 0;
        }

        button {
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .success, .error {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
        }

        .back-button {
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            color: #4CAF50;
            text-decoration: none;
            font-size: 16px;
        }

        .back-button a:hover {
            text-decoration: underline;
        }
    </style>
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

                <label for="stock">Stock:</label> <!-- Nuevo campo para el stock -->
                <input type="number" id="stock" name="stock" min="0" required>

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>

                <button type="submit">Agregar Producto</button>
            </form>

            <?php if (isset($message)): ?>
                <p class="<?php echo strpos($message, 'exitosamente') !== false ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php endif; ?>
        </section>

        <div class="back-button">
            <a href="admin.php">Volver al panel de administrador</a>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
