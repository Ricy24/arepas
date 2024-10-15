<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login/login.php');
    exit();
}

// Conectar a la base de datos
require 'config/db.php'; // Ajusta la ruta según la ubicación de tu archivo db.php

// Obtener productos del carrito
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calcular el total del carrito
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Manejar la eliminación de productos del carrito
if (isset($_GET['remove']) && !empty($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if (isset($cart[$remove_id])) {
        unset($cart[$remove_id]);
        $_SESSION['cart'] = $cart; // Actualiza la sesión
        header('Location: carrito.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="includes/styles.css">
    <style>
        .cart-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .cart-item img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .cart-item-details {
            flex: 1;
            margin-left: 20px;
        }
        .cart-item-details h3 {
            margin: 0;
        }
        .cart-item-details p {
            margin: 5px 0;
        }
        .cart-item-remove {
            color: red;
            cursor: pointer;
            text-decoration: none;
        }
        .cart-total {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .btn-checkout {
            display: block;
            width: 200px;
            padding: 10px;
            margin: 20px auto;
            text-align: center;
            background-color: #f0ad4e; /* Color más amarillo */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-checkout:hover {
            background-color: #ec971f; /* Un amarillo más oscuro al pasar el cursor */
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <section class="cart-container">
            <h1>Carrito de Compras</h1>

            <?php if (!empty($cart)): ?>
                <?php foreach ($cart as $id => $item): ?>
                    <?php
                    // Obtener la ruta de la imagen del producto
                    $sql = "SELECT imagen FROM productos WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();
                    $image = isset($product['imagen']) ? 'productos/' . htmlspecialchars($product['imagen']) : 'productos/default.png';
                    ?>

                    <div class="cart-item">
                        <!-- Mostrar la imagen del producto -->
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">

                        <div class="cart-item-details">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>Precio: $<?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                            <p>Cantidad: <?php echo intval($item['quantity']); ?></p>
                        </div>
                        <a href="carrito.php?remove=<?php echo $id; ?>" class="cart-item-remove"><i class="fas fa-trash"></i> Eliminar</a>
                    </div>
                <?php endforeach; ?>

                <div class="cart-total">
                    <strong>Total: $<?php echo number_format($total, 0, ',', '.'); ?></strong>
                </div>

                <a href="checkout.php" class="btn-checkout">Proceder al Pago</a>
            <?php else: ?>
                <p>Tu carrito está vacío.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
<script></script>