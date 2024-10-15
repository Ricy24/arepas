<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Mensaje de confirmación
$message = "¡Gracias por tu compra! Tu pedido ha sido recibido y está en proceso.";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="includes/styles.css">
    <style>
        .confirmation-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            text-align: center;
        }
        .confirmation-container h1 {
            margin-top: 0;
        }
        .confirmation-container p {
            font-size: 1.2em;
            margin: 20px 0;
        }
        .btn-home {
            display: inline-block;
            width: 200px;
            padding: 10px;
            text-align: center;
            background-color: #5bc0de;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            text-decoration: none;
        }
        .btn-home:hover {
            background-color: #31b0d5;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <section class="confirmation-container">
            <h1>Confirmación de Pedido</h1>
            <p><?php echo htmlspecialchars($message); ?></p>
            <a href="index.php" class="btn-home">Volver al Inicio</a>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
