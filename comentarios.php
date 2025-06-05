<?php
session_start();
include("conexion.php");

$logueado = isset($_SESSION["cliente"]);
$success = null; // Inicializar $success
$error = null; // Inicializar $error
$resultado = $conn->query("SELECT c.comentario, c.fecha, p.nombre AS producto, cl.nombre AS cliente FROM comentarios c JOIN productos p ON c.producto_id = p.id JOIN clientes cl ON c.cliente_id = cl.id WHERE c.visible = 1");
$productos = $conn->query("SELECT id, nombre FROM productos");

if ($_SERVER["REQUEST_METHOD"] == "POST" && $logueado) {
    try {
        $producto_id = filter_var($_POST['producto_id'], FILTER_SANITIZE_NUMBER_INT);
        $comentario = filter_var($_POST['comentario'], FILTER_SANITIZE_STRING);
        $cliente_id = $_SESSION["id"];

        $sql = "INSERT INTO comentarios (cliente_id, producto_id, comentario) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }
        $stmt->bind_param("iis", $cliente_id, $producto_id, $comentario);
        if ($stmt->execute()) {
            $success = "Comentario enviado correctamente.";
        } else {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        $stmt->close();
        header("Location: comentarios.php");
        exit();
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
    <title>Comentarios - ECO-HIGIENE</title>
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
        <section id="comentarios">
            <h2><i class="fas fa-comments"></i> Comentarios</h2>
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($resultado->num_rows > 0): ?>
                <div class="comentarios-grid">
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <div class="comentario-card">
                            <h3><?php echo htmlspecialchars($row['cliente']); ?> sobre <?php echo htmlspecialchars($row['producto']); ?></h3>
                            <p><small><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></small></p>
                            <p><?php echo htmlspecialchars($row['comentario']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No hay comentarios disponibles.</p>
            <?php endif; ?>

            <?php if ($logueado): ?>
                <h2><i class="fas fa-comment-dots"></i> Deja tu Comentario</h2>
                <form action="comentarios.php" method="post">
                    <label for="producto_id">Producto:</label>
                    <select id="producto_id" name="producto_id" required>
                        <?php while ($producto = $productos->fetch_assoc()): ?>
                            <option value="<?php echo $producto['id']; ?>"><?php echo htmlspecialchars($producto['nombre']); ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label for="comentario">Comentario:</label>
                    <textarea id="comentario" name="comentario" required></textarea>
                    <button type="submit" class="btn">Enviar Comentario</button>
                </form>
            <?php else: ?>
                <p class="login-required"><i class="fas fa-info-circle"></i> <a href="login.php">Inicia sesión</a> para dejar un comentario.</p>
            <?php endif; ?>
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