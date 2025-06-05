<?php
session_start();
include("conexion.php");

$logueado = isset($_SESSION["cliente"]);
$error = null;
$success = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $mensaje = filter_var($_POST['mensaje'], FILTER_SANITIZE_STRING);

        $sql = "INSERT INTO contactos (nombre, email, mensaje) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }
        $stmt->bind_param("sss", $nombre, $email, $mensaje);
        if ($stmt->execute()) {
            $success = "Mensaje enviado correctamente.";
        } else {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - ECO-HIGIENE</title>
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
                <?php if($logueado): ?>
                    <li><a href="carrito.php"><i class="fas fa-shopping-cart"></i> Carrito</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login/Registro</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="container">
        <section id="contacto">
            <h2><i class="fas fa-envelope"></i> Contáctanos</h2>
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="contacto.php" method="post">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
                <button type="submit" class="btn">Enviar</button>
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