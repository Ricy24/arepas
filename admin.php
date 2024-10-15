<?php
// Incluye el archivo de conexión a la base de datos
include 'config/db.php';

// Maneja la actualización del estado del pedido
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Prepara la consulta para actualizar el estado del pedido
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        $success_message = "Estado del pedido actualizado con éxito.";
    } else {
        $error_message = "Error al actualizar el estado del pedido: " . $conn->error;
    }

    $stmt->close();
}

// Maneja la eliminación de un producto
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    // Prepara la consulta para eliminar el producto
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        $success_message = "Producto eliminado con éxito.";
    } else {
        $error_message = "Error al eliminar el producto: " . $conn->error;
    }

    $stmt->close();
}

// Maneja la actualización de un producto
if (isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_stock = $_POST['product_stock']; // Agregar stock
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Prepara la consulta para actualizar el producto
    $stmt = $conn->prepare("UPDATE productos SET nombre = ?, precio = ?, stock = ?, destacado = ? WHERE id = ?");
    $stmt->bind_param("sdiii", $product_name, $product_price, $product_stock, $is_featured, $product_id);

    if ($stmt->execute()) {
        $success_message = "Producto actualizado con éxito.";
    } else {
        $error_message = "Error al actualizar el producto: " . $conn->error;
    }

    $stmt->close();
}

// Maneja la actualización del estado de destacado
if (isset($_POST['update_featured'])) {
    $product_id = $_POST['product_id'];
    $is_featured = $_POST['featured'];

    // Prepara la consulta para actualizar el estado de destacado
    $stmt = $conn->prepare("UPDATE productos SET destacado = ? WHERE id = ?");
    $stmt->bind_param("ii", $is_featured, $product_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $stmt->close();
}

// Consulta para obtener los pedidos, detalles y la información del producto
$order_query = "
    SELECT o.id AS order_id, o.created_at AS fecha, o.status AS estado, 
           od.product_id, od.quantity AS cantidad, od.price, p.nombre AS producto_nombre, 
           o.address AS direccion, u.nombre AS usuario_nombre, u.email AS usuario_email
    FROM orders o
    JOIN order_details od ON o.id = od.order_id
    JOIN productos p ON od.product_id = p.id
    JOIN usuarios u ON o.user_id = u.id
    ORDER BY o.created_at DESC
";

// Ejecutar la consulta de pedidos
$order_result = $conn->query($order_query);

// Verificar si la consulta de pedidos fue exitosa
if (!$order_result) {
    die("Error en la consulta de pedidos: " . $conn->error);
}

// Consulta para obtener la información de los usuarios
$user_query = "SELECT id, nombre, email, telefono, direccion FROM usuarios";

// Ejecutar la consulta de usuarios
$user_result = $conn->query($user_query);

// Verificar si la consulta de usuarios fue exitosa
if (!$user_result) {
    die("Error en la consulta de usuarios: " . $conn->error);
}

// Consulta para obtener la información de los productos incluyendo el stock
$product_query = "SELECT id, nombre, precio, destacado, stock FROM productos";

// Ejecutar la consulta de productos
$product_result = $conn->query($product_query);

// Verificar si la consulta de productos fue exitosa
if (!$product_result) {
    die("Error en la consulta de productos: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área de Administradores - Mr. Peto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../includes/styles.css"> <!-- Estilo general -->
</head>
<body>
    <header class="bg-dark text-white text-center py-3">
        <h1>Área de Administradores</h1>
    </header>
    
    <div class="container mt-5">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Sección de Pedidos -->
        <h2>Pedidos y Detalles</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID de Pedido</th>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Nombre del Usuario</th>
                    <th>Email del Usuario</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($order_result->num_rows > 0): ?>
                    <?php while ($row = $order_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($row['producto_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['usuario_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['usuario_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($row['estado']); ?></td>
                            <td>
                                <form method="POST" action="admin.php" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                    <select name="status" class="form-control d-inline-block w-auto">
                                        <option value="pendiente" <?php echo ($row['estado'] === 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="entregado" <?php echo ($row['estado'] === 'entregado') ? 'selected' : ''; ?>>Entregado</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary btn-sm ml-2">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No hay datos disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Sección de Usuarios -->
        <h2 class="mt-5">Usuarios</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($user_result->num_rows > 0): ?>
                    <?php while ($row = $user_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay datos disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

       <!-- Sección de Productos -->
<h2 class="mt-5">Productos</h2>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Destacado</th>
            <th>Stock</th> <!-- Agregamos una nueva columna para el stock -->
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($product_result->num_rows > 0): ?>
            <?php while ($row = $product_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['precio']); ?></td>
                    <td>
                        <form method="POST" class="form-inline" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="checkbox" name="is_featured" value="1" <?php echo ($row['destacado']) ? 'checked' : ''; ?> onchange="updateFeaturedStatus(this, <?php echo htmlspecialchars($row['id']); ?>)">
                            Destacado
                        </form>
                    </td>
                    <td><?php echo htmlspecialchars($row['stock']); ?></td> <!-- Mostramos el stock aquí -->
                    <td>
                        <!-- Botón para eliminar -->
                        <form method="POST" action="admin.php" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                        <!-- Botón para editar (pasa los datos en un formulario) -->
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editProductModal" data-id="<?php echo htmlspecialchars($row['id']); ?>" data-name="<?php echo htmlspecialchars($row['nombre']); ?>" data-price="<?php echo htmlspecialchars($row['precio']); ?>">Editar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No hay datos disponibles</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<a href="agregar_productos.php" class="btn btn-success mt-3">Subir Producto</a>
    </div>
<br>
   <!-- Modal para Editar Producto -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="admin.php">
                    <input type="hidden" name="product_id" id="editProductId">
                    <div class="form-group">
                        <label for="product_name">Nombre</label>
                        <input type="text" class="form-control" name="product_name" id="editProductName" required>
                    </div>
                    <div class="form-group">
                        <label for="product_price">Precio</label>
                        <input type="number" class="form-control" name="product_price" id="editProductPrice" required>
                    </div>
                    <div class="form-group">
                        <label for="product_stock">Stock</label> <!-- Nuevo campo para modificar stock -->
                        <input type="number" class="form-control" name="product_stock" id="editProductStock" min="0" required>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_featured" id="editIsFeatured">
                            <label class="form-check-label" for="editIsFeatured">Destacado</label>
                        </div>
                    </div>
                    <button type="submit" name="edit_product" class="btn btn-primary">Actualizar Producto</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Rellena el modal con los datos del producto al hacer clic en el botón de editar
        $('#editProductModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var productId = button.data('id'); // Extrae la información de los atributos data-* del botón
            var productName = button.data('name');
            var productPrice = button.data('price');

            var modal = $(this);
            modal.find('#editProductId').val(productId);
            modal.find('#editProductName').val(productName);
            modal.find('#editProductPrice').val(productPrice);
        });

        // Función para actualizar el estado de destacado
        function updateFeaturedStatus(checkbox, productId) {
            var isFeatured = checkbox.checked ? 1 : 0;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "admin.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (!response.success) {
                        alert("Error al actualizar el estado de destacado: " + response.error);
                    }
                }
            };
            xhr.send("update_featured=true&product_id=" + productId + "&featured=" + isFeatured);
        }
    </script>
</body>
</html>
