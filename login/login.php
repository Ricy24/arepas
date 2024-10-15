<?php
include '../config/db.php';
session_start();

// Redirige si el usuario ya está conectado
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nombre'];
            header("Location: ../index.php");
            exit();
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        $error_message = "Correo electrónico no encontrado.";
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Mr. Peto</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link rel="stylesheet" href="../includes/styles.css">
    <link rel="stylesheet" href="loginn.css">
</head>
<body>
    <header>
        <!-- Logo -->
        <div class="logo">
            <a href="../index.php">
                <img src="../img/logooo.png" alt="Logo" class="logo-img">
            </a>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="screen">
                <div class="screen__content">
                    <form class="login" action="login.php" method="POST" onsubmit="return validateForm()">
                        <div class="login__field">
                            <i class="login__icon fas fa-user"></i>
                            <input type="text" class="login__input" name="email" placeholder="Correo Electrónico" required>
                        </div>
                        <div class="login__field">
                            <i class="login__icon fas fa-lock"></i>
                            <input type="password" class="login__input" name="password" placeholder="Contraseña" required>
                        </div>
                        <!-- Cuadro de Términos y Condiciones -->
                        <div class="terms__field">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">Acepto los <a href="terminos_y_condiciones.php" target="_blank">Términos y Condiciones</a></label>
                        </div>
                        <button type="submit" class="button login__submit">
                            <span class="button__text">Iniciar Sesión</span>
                            <i class="button__icon fas fa-chevron-right"></i>
                        </button>
                        <?php if ($error_message): ?>
                            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                        <?php endif; ?>
                    </form>
                    <div class="social-login">
                        <p class="login-link">¿No tienes cuenta? <a href="register.php">Registrate aquí</a></p>
                        <div class="social-icons">
                            <a href="https://www.instagram.com/mr_peto_arepas?igsh=NWNveWgxdDVncjd6" class="social-login__icon fab fa-instagram"></a>
                            <a href="https://www.facebook.com/share/nekrGUE5ngVfXCXd/?mibextid=qi2Omg" class="social-login__icon fab fa-facebook"></a>
                            <a href="https://x.com/mr_arepas" class="social-login__icon fab fa-twitter"></a>
                        </div>
                    </div>
                </div>
                <div class="screen__background">
                    <span class="screen__background__shape screen__background__shape4"></span>
                    <span class="screen__background__shape screen__background__shape3"></span>		
                    <span class="screen__background__shape screen__background__shape2"></span>
                    <span class="screen__background__shape screen__background__shape1"></span>
                </div>		
            </div>
        </div>
    </main>

    <script>
        function validateForm() {
            var terms = document.getElementById("terms");
            if (!terms.checked) {
                alert("Debes aceptar los Términos y Condiciones antes de iniciar sesión.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>

