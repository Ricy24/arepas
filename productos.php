<?php
// Inicia sesión
session_start();

// Verifica si el usuario está autenticado
$user_logged_in = isset($_SESSION['user_id']);
$username = $user_logged_in ? $_SESSION['username'] : '';

// Conectar a la base de datos
require 'config/db.php'; // Ajusta la ruta para que apunte a la carpeta config

// Obtener productos de la base de datos
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '';
$sql = "SELECT * FROM productos";

if ($search) {
    $sql .= " WHERE nombre LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $search);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$productos = $result->fetch_all(MYSQLI_ASSOC);

// Opcional: Obtener productos destacados (si tienes una columna para esto)
$featured_sql = "SELECT * FROM productos WHERE destacado = 1";
$featured_stmt = $conn->prepare($featured_sql);
$featured_stmt->execute();
$featured_result = $featured_stmt->get_result();
$featured_products = $featured_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mr. Peto - Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="producto.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <!-- Banner Principal -->
        <section class="banner">
            <div class="banner-content">
                <h1>Productos</h1>
                <p>Explora nuestra deliciosa selección de arepas.</p>
            </div>
        </section>

        <!-- Productos Destacados -->
        <section class="featured-products">
            <h2>Productos Destacados</h2>
            <div class="product-grid">
                <?php if (count($featured_products) > 0): ?>
                    <?php foreach ($featured_products as $producto): ?>
                        <div class="product-card">
                            <img src="productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <span class="price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
                            <!-- Mostrar el stock -->
                            <span class="stock">Stock: <?php echo htmlspecialchars($producto['stock']); ?></span>
                            <a href="agregar_carrito.php?id=<?php echo htmlspecialchars($producto['id']); ?>" class="btn-cart"><i class="fas fa-cart-plus"></i> Agregar al Carrito</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay productos destacados.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Todos los Productos -->
        <section class="product-list">
            <h2>Todos los Productos</h2>
            <div class="product-grid">
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $producto): ?>
                        <div class="product-card">
                            <img src="productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <span class="price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
                            <!-- Mostrar el stock -->
                            <span class="stock">Stock: <?php echo htmlspecialchars($producto['stock']); ?></span>
                            <a href="agregar_carrito.php?id=<?php echo htmlspecialchars($producto['id']); ?>" class="btn-cart"><i class="fas fa-cart-plus"></i> Agregar al Carrito</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No se encontraron productos.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
// Cerrar la conexión
$stmt->close();
$conn->close();
?>
