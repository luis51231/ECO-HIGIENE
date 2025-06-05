<?php
include("verificar_sesion.php");
include("conexion.php");

// Arreglo de imágenes para los productos
$imagenes = [
    1 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFMF9ZUdiw4MMrFSuhfd73LoNndpQIQ_5Y5g&s', // Gel Antibacterial
    2 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5UAMVNk-0AAC4Lfgcu5P5CoKDmhBuv4ZkGA&s', // Labial Natural
    3 => 'https://mejorconsalud.as.com/wp-content/uploads/2014/05/cremas-corporales-caseras.jpg', // Crema Corporal
    4 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTBMD_vB6cfvpRetvGzmtAcvRs03ctdnmZFUQ&s',      // Acetona Natural
    5 => 'https://www.hacercremas.es/wp-content/uploads/2014/05/aceite-desmaquillante-3.jpg',       // Desmaquillante
    6 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSZWvgahGIzJSXDnIoy0Ac6O4rM4HHL5JAuQ&s'    // Ungüento Medicinal
];

$total = 0;
$resultado = $conn->query("SELECT c.id, p.id AS producto_id, p.nombre, p.precio FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.cliente_id = " . $_SESSION["id"]);
$num_items = $resultado ? $resultado->num_rows : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $carrito_id = filter_var($_POST['carrito_id'], FILTER_SANITIZE_NUMBER_INT);
    $conn->query("DELETE FROM carrito WHERE id = $carrito_id AND cliente_id = " . $_SESSION["id"]);
    header("Location: carrito.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pagar'])) {
    if ($num_items > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $sql = "INSERT INTO ventas (cliente_id, producto_id, precio) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iid", $_SESSION["id"], $row['producto_id'], $row['precio']);
            $stmt->execute();
            $stmt->close();
        }
        $conn->query("DELETE FROM carrito WHERE cliente_id = " . $_SESSION["id"]);
        header("Location: pagar.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - ECO-HIGIENE</title>
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
        <section id="carrito">
            <h2><i class="fas fa-shopping-cart"></i> Tu Carrito</h2>
            <p>Items en el carrito: <?php echo $num_items; ?></p>
            <?php if ($num_items > 0): ?>
                <div class="carrito-grid">
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <?php $total += $row['precio']; ?>
                        <div class="carrito-item">
                            <img src="<?php echo htmlspecialchars($imagenes[$row['producto_id']]); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                            <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                            <p>Precio: $<?php echo number_format($row['precio'], 2); ?></p>
                            <form action="carrito.php" method="post">
                                <input type="hidden" name="carrito_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="eliminar" class="btn btn-secondary"><i class="fas fa-trash"></i> Eliminar</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                </div>
                <p class="total">Total: $<?php echo number_format($total, 2); ?></p>
                <form action="carrito.php" method="post">
                    <button type="submit" name="pagar" class="btn"><i class="fas fa-credit-card"></i> Pagar</button>
                </form>
            <?php else: ?>
                <p>Tu carrito está vacío. <a href="productos.php">¡Compra ahora!</a></p>
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