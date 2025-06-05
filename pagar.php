<?php
include("verificar_sesion.php");
include("conexion.php");

$cliente_id = $_SESSION["id"];

// Obtener productos del carrito
$resultado = $conn->query("SELECT c.producto_id, p.precio FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.cliente_id = $cliente_id");

// Registrar cada producto en la tabla ventas
if ($resultado->num_rows > 0) {
    $stmt = $conn->prepare("INSERT INTO ventas (cliente_id, producto_id, precio) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $cliente_id, $producto_id, $precio);
    
    while ($row = $resultado->fetch_assoc()) {
        $producto_id = $row['producto_id'];
        $precio = $row['precio'];
        $stmt->execute();
    }
    $stmt->close();
}

// Vaciar el carrito
$conn->query("DELETE FROM carrito WHERE cliente_id = $cliente_id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago - ECO-HIGIENE</title>
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
                <li><a href="carrito.php"><i class="fas fa-shopping-cart"></i> Carrito</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <section id="pago">
            <h2><i class="fas fa-check-circle"></i> Pago Completado</h2>
            <p>¡Gracias por tu compra! Tu pedido ha sido procesado.</p>
            <a href="productos.php" class="btn">Volver a Productos</a>
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