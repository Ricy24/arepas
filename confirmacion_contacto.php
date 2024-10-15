<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Contacto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="includes/styles.css">
    <style>
        .confirmacion-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f8f9fa;
            text-align: center;
        }
        .confirmacion-container h2 {
            margin-top: 0;
            color: #5bc0de;
        }
        .confirmacion-container p {
            font-size: 1.2em;
            color: #333;
        }
        .confirmacion-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #5bc0de;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
        }
        .confirmacion-container a:hover {
            background-color: #31b0d5;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="confirmacion-container">
        <h2>¡Gracias por contactarnos!</h2>
        <p>Hemos recibido tu mensaje y nos pondremos en contacto contigo a la brevedad posible.</p>
        <a href="index.php">Volver al Inicio</a>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
