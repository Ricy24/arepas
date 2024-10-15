<?php
// Iniciar sesión
session_start();

// Incluir el archivo de conexión a la base de datos
include 'config/db.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener productos destacados
$sql = "SELECT * FROM productos WHERE destacado = 1";
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mr. Peto - Inicio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    .banner {
        background: url('img/arepa.png') no-repeat center center/cover;
        color: #fff;
        text-align: center;
        padding: 100px 20px;
        position: relative;
    }
</style>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <!-- Banner Principal -->
        <section class="banner">
            <div class="banner-content">
                <h1>Bienvenido a Mr. Peto</h1>
                <p>¡La mejor selección de arepas frescas y deliciosas!</p>
                <a href="productos.php" class="btn btn-banner">Ver Productos</a>
            </div>
        </section>

        
<!-- Productos Destacados -->
<section class="featured-products">
    <h2>Productos Destacados</h2>
    <div class="slider-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <!-- Ruta de la imagen -->
                    <img src="productos/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>">
                    <h3><?php echo $row['nombre']; ?></h3>
                    <p><?php echo $row['descripcion']; ?></p>
                    <!-- Precio formateado -->
                    <span class="price">$<?php echo number_format($row['precio'], 0, ',', '.'); ?></span>
                    <!-- Enlace a productos.php sin ID -->
                    <a href="productos.php" class="btn-product">Ver más</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay productos destacados en este momento</p>
        <?php endif; ?>
    </div>
</section>



        <!-- Información sobre el negocio -->
        <section class="about-us">
            <h2>Sobre Nosotros</h2>
            <div class="about-content">
                <div class="about-img-container">
                    <img src="img/nosotros.jpg" alt="Sobre Nosotros" class="about-img">
                </div>
                <div class="about-info">
                    <p>En Mr. Peto, nos enorgullecemos de ofrecer a nuestros clientes arepas de alta calidad con una amplia variedad de recetas y sabores. Nos dedicamos a proporcionar un excelente servicio al cliente personalizado y de respeto, garantizando la satisfacción total de nuestros clientes.</p>
                </div>
            </div>
        </section>

        <!-- Servicios -->
        <section class="services">
            <h2>Servicios</h2>
            <div class="services-grid">
                <div class="service-card">
                    <i class="fas fa-truck"></i>
                    <h3>Envío a Domicilio</h3>
                    <p>Ofrecemos servicio de entrega rápida y eficiente a la puerta de tu casa.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-cash-register"></i>
                    <h3>Pago Seguro</h3>
                    <p>Realiza tus pagos de forma segura a través de nuestra plataforma en línea.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-certificate"></i>
                    <h3>Calidad Garantizada</h3>
                    <p>Disfruta de productos frescos y de la mejor calidad.</p>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
