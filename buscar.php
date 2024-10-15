<?php
session_start(); // Asegúrate de iniciar sesión

// Configuración de la base de datos
$host = 'localhost'; // O el nombre de tu servidor
$user = 'root';      // O el nombre de usuario de tu base de datos
$password = '';      // O la contraseña de tu base de datos
$dbname = 'arepas';  // Nombre de tu base de datos

// Crear conexión
$mysqli = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Inicializar variables
$query = '';
$results = [];

// Procesar la búsqueda
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Preparar la consulta
    $sql = "SELECT * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        // Enlazar parámetros
        $search_term = '%' . $query . '%';
        $stmt->bind_param('ss', $search_term, $search_term);

        // Ejecutar la consulta
        $stmt->execute();
        $result = $stmt->get_result();

        // Obtener los resultados
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }
}

// Cerrar la conexión
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="includes/styles.css">
    <style>
        .search-results {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        .search-results h2 {
            margin-top: 0;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            display: flex;
            align-items: center;
        }
        .product-card img {
            max-width: 150px;
            max-height: 150px;
            margin-right: 20px;
            object-fit: cover; /* Ajusta la imagen para que se ajuste dentro del contenedor */
        }
        .product-card h3 {
            margin: 0;
        }
        .product-card p {
            margin: 5px 0;
        }
        .btn-add-to-cart {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            background-color: #ffcc00; /* Color amarillo más intenso */
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-add-to-cart:hover {
            background-color: #e0b800; /* Amarillo más oscuro para el hover */
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="search-results">
        <h2>Resultados de la Búsqueda</h2>

        <?php if (empty($results)): ?>
            <p>No se encontraron productos que coincidan con "<strong><?php echo htmlspecialchars($query); ?></strong>".</p>
        <?php else: ?>
            <?php foreach ($results as $product): ?>
                <div class="product-card">
                    <img src="productos/<?php echo htmlspecialchars($product['imagen']); ?>" alt="<?php echo htmlspecialchars($product['nombre']); ?>">
                    <div>
                        <h3><?php echo htmlspecialchars($product['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars($product['descripcion']); ?></p>
                        <p>Precio: $<?php echo htmlspecialchars($product['precio']); ?></p>
                        <form action="agregar_carrito.php" method="post">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-add-to-cart"><i class="fas fa-cart-plus"></i> Agregar al Carrito</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
