<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login/login.php');
    exit();
}

// Conectar a la base de datos
require 'config/db.php'; // Asegúrate de que la ruta al archivo db.php es correcta

// Verifica si se ha pasado un ID de producto
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: productos.php');
    exit();
}

$product_id = intval($_GET['id']);

// Verifica si el ID del producto es válido
if ($product_id <= 0) {
    header('Location: productos.php');
    exit();
}

// Obtiene la información del producto
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Verifica si el producto existe
if (!$product) {
    header('Location: productos.php');
    exit();
}

// Añadir producto al carrito
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Verifica si el producto ya está en el carrito
if (isset($_SESSION['cart'][$product_id])) {
    // Incrementa la cantidad si el producto ya está en el carrito
    $_SESSION['cart'][$product_id]['quantity']++;
} else {
    // Añade el producto al carrito
    $_SESSION['cart'][$product_id] = [
        'name' => $product['nombre'],
        'price' => $product['precio'],
        'quantity' => 1
    ];
}

// Redirige al usuario a la página de productos con un mensaje de éxito
header('Location: productos.php?success=1');
exit();
