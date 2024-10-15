<?php


// Verifica si el usuario está autenticado
$user_logged_in = isset($_SESSION['user_id']);
$username = $user_logged_in ? $_SESSION['username'] : '';

// Contar los productos en el carrito
$cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Fest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="includes/styles.css">
    <style>
        /* Estilo para el contador de productos en el carrito */
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #ffcc00; /* Amarillo no tan intenso */
            color: #333;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-container {
            position: relative;
        }
        .search-container {
            display: flex;
            align-items: center;
            margin-left: auto;
        }
        .search-container input[type="text"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-container button {
            padding: 5px;
            background-color: #f0ad4e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
        }
        .search-container button:hover {
            background-color: #ec971f;
        }
        .auth-buttons {
            display: flex;
            align-items: center;
            margin-left: auto;
        }
        .auth-buttons .btn {
            margin-left: 10px;
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-login {
            background-color: #007bff;
        }
        .btn-login:hover {
            background-color: #0056b3;
        }
        .btn-register {
            background-color: #28a745;
        }
        .btn-register:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="Img/logooo.png" alt="Logo" class="logo-img">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="contacto.php">Contáctanos</a></li>
                <li class="header-container">
                    <a href="carrito.php"><i class="fas fa-shopping-cart"></i></a>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
        <div class="search-container">
            <form action="buscar.php" method="GET">
                <input type="text" name="query" placeholder="Buscar productos..." required>
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="auth-buttons">
            <?php if ($user_logged_in): ?>
                <!-- Usuario autenticado, muestra nombre y botón de cerrar sesión -->
                <span class="username">Hola, <?php echo htmlspecialchars($username); ?>!</span>
                <a href="login/logout.php" class="btn btn-login"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            <?php else: ?>
                <!-- Usuario no autenticado, muestra botones de inicio de sesión y registro -->
                <a href="login/login.php" class="btn btn-login"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
                <a href="login/register.php" class="btn btn-register"><i class="fas fa-user-plus"></i> Registrarse</a>
            <?php endif; ?>
        </div>
    </header>
</body>
</html>
