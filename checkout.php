<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login/login.php');
    exit();
}

// Conectar a la base de datos
require 'config/db.php'; // Ruta ajustada según tu configuración

// Obtener productos del carrito
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calcular el total del carrito
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Procesar el formulario de pago
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';

    // Validación simple
    if (empty($address) || empty($payment_method)) {
        $error = 'Por favor complete todos los campos.';
    } else {
        // Insertar el pedido en la base de datos
        $sql = "INSERT INTO orders (user_id, total, address, payment_method, status) VALUES (?, ?, ?, ?, 'pendiente')";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param('idss', $_SESSION['user_id'], $total, $address, $payment_method);
        $stmt->execute();
        if ($stmt->error) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }
        $order_id = $stmt->insert_id; // Obtiene el ID del pedido recién insertado
        $stmt->close();

        // Insertar los detalles del pedido en la base de datos
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }

        foreach ($cart as $id => $item) {
            $stmt->bind_param('iiid', $order_id, $id, $item['quantity'], $item['price']);
            $stmt->execute();
            if ($stmt->error) {
                die("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Reducir el stock del producto
            $update_stock_sql = "UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?";
            $update_stock_stmt = $conn->prepare($update_stock_sql);
            if ($update_stock_stmt === false) {
                die("Error en la preparación de la consulta de stock: " . $conn->error);
            }
            $update_stock_stmt->bind_param('iii', $item['quantity'], $id, $item['quantity']);
            $update_stock_stmt->execute();
            if ($update_stock_stmt->error) {
                die("Error al actualizar el stock: " . $update_stock_stmt->error);
            }
            $update_stock_stmt->close();
        }

        $stmt->close();

        // Limpia el carrito después del pago
        $_SESSION['cart'] = [];

        // Redirige a una página de confirmación
        header('Location: confirmacion.php');
        exit();
    }
}
?>
<style>
        .checkout-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
        }
        .checkout-container h1 {
            margin-top: 0;
        }
        .checkout-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .checkout-item img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .checkout-item-details {
            flex: 1;
            margin-left: 20px;
        }
        .checkout-item-details h3 {
            margin: 0;
        }
        .checkout-item-details p {
            margin: 5px 0;
        }
        .checkout-total {
            text-align: right;
            font-size: 1.5em;
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-submit {
            display: block;
            width: 200px;
            padding: 10px;
            margin: 20px auto;
            text-align: center;
            background-color: #f0ad4e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            text-decoration: none;
        }
        .btn-submit:hover {
            background-color: #ec971f;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="includes/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <section class="checkout-container">
            <h1>Checkout</h1>

            <?php if (!empty($cart)): ?>
                <div class="checkout-items">
                    <?php foreach ($cart as $id => $item): ?>
                        <?php
                        // Obtener la información del producto para mostrar la imagen
                        $sql = "SELECT imagen, nombre FROM productos WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $id);
                        $stmt->execute();
                        $product_result = $stmt->get_result();
                        $product = $product_result->fetch_assoc();
                        $stmt->close();
                        ?>
                        <div class="checkout-item">
                            <img src="productos/<?php echo htmlspecialchars($product['imagen']); ?>" alt="<?php echo htmlspecialchars($product['nombre']); ?>">
                            <div class="checkout-item-details">
                                <h3><?php echo htmlspecialchars($product['nombre']); ?></h3>
                                <p>Precio: $<?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                                <p>Cantidad: <?php echo intval($item['quantity']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="checkout-total">
                    <strong>Total: $<?php echo number_format($total, 0, ',', '.'); ?></strong>
                </div>

                <form action="checkout.php" method="post">
                    <?php if (isset($error)): ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="address">Dirección de Envío</label>
                        <input type="text" id="address" name="address" placeholder="Ingresa tu dirección de envío" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Método de Pago</label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="">Selecciona un método de pago</option>
                            <option value="credit_card">Tarjeta de Crédito</option>
                            <option value="paypal">PayPal</option>
                            <option value="cash">Efectivo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-submit">Confirmar Compra</button>
                </form>
            <?php else: ?>
                <p>Tu carrito está vacío.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
