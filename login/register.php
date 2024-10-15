<?php
// Incluye el archivo de conexión a la base de datos
include '../config/db.php';

// Maneja el registro si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Verifica si las contraseñas coinciden
    if ($password !== $confirm_password) {
        $error_message = "Las contraseñas no coinciden.";
    } else {
        // Verifica si el nombre de usuario o el correo ya están en uso
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "El nombre de usuario o el correo electrónico ya están en uso.";
        } else {
            // Hashea la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Inserta el nuevo usuario en la base de datos
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, telefono, direccion) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $hashed_password, $telefono, $direccion);

            if ($stmt->execute()) {
                // Redirige a login.php después de un registro exitoso
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error al registrar el usuario. Por favor, intenta nuevamente.";
            }
        }

        // Cierra la consulta
        $stmt->close();
    }

    // Cierra la conexión
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Mr. Peto</title>
    <link rel="stylesheet" href="../includes/styles.css"> <!-- Estilo general -->
    <link rel="stylesheet" href="register.css"> <!-- Estilo específico para registro -->
</head>
<body>

    <main>
    <header>
        <!-- Logo -->
        <div class="logo">
            <a href="../index.php">
                <img src="../img/logooo.png" alt="Logo" class="logo-img">
            </a>
        </div>
    </header>
    <br>
        <div class="screen">
            <div class="screen__content">
                <div class="login">
                    <h1>Registrarse</h1>
                    <?php if (isset($error_message)): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    <form action="register.php" method="POST">
                        <div class="login__field">
                            <input type="text" id="username" name="username" class="login__input" placeholder="Nombre de Usuario" required>
                        </div>
                        <div class="login__field">
                            <input type="email" id="email" name="email" class="login__input" placeholder="Correo Electrónico" required>
                        </div>
                        <div class="login__field">
                            <input type="password" id="password" name="password" class="login__input" placeholder="Contraseña" required>
                        </div>
                        <div class="login__field">
                            <input type="password" id="confirm_password" name="confirm_password" class="login__input" placeholder="Confirmar Contraseña" required>
                        </div>
                        <div class="login__field">
                            <input type="text" id="telefono" name="telefono" class="login__input" placeholder="Teléfono">
                        </div>
                        <div class="login__field">
                            <textarea id="direccion" name="direccion" class="login__input" placeholder="Dirección"></textarea>
                        </div>
                         <!-- Cuadro de Términos y Condiciones -->
                         <div class="terms__field">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">Acepto los <a href="terminos_y_condiciones.php" target="_blank">Términos y Condiciones</a></label>
                        </div>
                        <button type="submit" class="login__submit"><i class="fas fa-user-plus button__icon"></i> Registrarse</button>
                        <p class="login-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                    </form>
                </div>
            </div>
            <div class="screen__background">
                <div class="screen__background__shape screen__background__shape1"></div>
                <div class="screen__background__shape screen__background__shape2"></div>
                <div class="screen__background__shape screen__background__shape3"></div>
                <div class="screen__background__shape screen__background__shape4"></div>
            </div>
        </div>
    </main>

</body>
</html>
