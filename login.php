<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $clave = $_POST['clave'];
    $nombre = isset($_POST['nombre']) ? filter_var($_POST['nombre'], FILTER_SANITIZE_STRING) : '';

    if (isset($_POST['registro'])) {
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
        $sql = "INSERT INTO clientes (nombre, email, clave) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $email, $clave_hash);
        if ($stmt->execute()) {
            // Obtener el ID del cliente recién registrado
            $cliente_id = $conn->insert_id;
            $_SESSION["cliente"] = $email;
            $_SESSION["nombre"] = $nombre;
            $_SESSION["id"] = $cliente_id;
            header("Location: productos.php");
            exit();
        } else {
            $error = "Error en el registro: " . $conn->error;
        }
        $stmt->close();
    } else {
        $sql = "SELECT id, nombre, clave FROM clientes WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($clave, $row['clave'])) {
                $_SESSION["cliente"] = $email;
                $_SESSION["nombre"] = $row['nombre'];
                $_SESSION["id"] = $row['id'];
                header("Location: productos.php");
                exit();
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Usuario no encontrado";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Registro - ECO-HIGIENE</title>
    <link rel="stylesheet" href="ECO-HIGIENE.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><i class="fas fa-leaf"></i> ECO-HIGIENE</h1>
            <p>Higiene personal con conciencia ecológica</p>
        </div>
    </header>

    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a href="productos.php"><i class="fas fa-store"></i> Productos</a></li>
                <li><a href="comentarios.php"><i class="fas fa-comments"></i> Comentarios</a></li>
                <li><a href="contacto.php"><i class="fas fa-envelope"></i> Contacto</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <section id="login">
            <h2><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form action="login.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="clave">Contraseña:</label>
                <input type="password" id="clave" name="clave" required>
                <button type="submit" class="btn">Iniciar Sesión</button>
            </form>

            <h2><i class="fas fa-user-plus"></i> Registrarse</h2>
            <form action="login.php" method="post">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="email_reg">Email:</label>
                <input type="email" id="email_reg" name="email" required>
                <label for="clave_reg">Contraseña:</label>
                <input type="password" id="clave_reg" name="clave" required>
                <input type="hidden" name="registro" value="1">
                <button type="submit" class="btn">Registrarse</button>
            </form>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>© 2025 ECO-HIGIENE. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
<?php
$conn->close();
?>