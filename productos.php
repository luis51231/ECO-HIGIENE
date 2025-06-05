<?php
session_start();
include("conexion.php");

$logueado = isset($_SESSION["cliente"]);
$resultado = $conn->query("SELECT * FROM productos");
$num_productos = $resultado ? $resultado->num_rows : 0;

// Arreglo de imágenes para los productos
$imagenes = [
    1 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFMF9ZUdiw4MMrFSuhfd73LoNndpQIQ_5Y5g&s', // Gel Antibacterial
    2 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5UAMVNk-0AAC4Lfgcu5P5CoKDmhBuv4ZkGA&s', // Labial Natural
    3 => 'https://mejorconsalud.as.com/wp-content/uploads/2014/05/cremas-corporales-caseras.jpg', // Crema Corporal
    4 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTBMD_vB6cfvpRetvGzmtAcvRs03ctdnmZFUQ&s',      // Acetona Natural
    5 => 'https://www.hacercremas.es/wp-content/uploads/2014/05/aceite-desmaquillante-3.jpg',       // Desmaquillante
    6 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSZWvgahGIzJSXDnIoy0Ac6O4rM4HHL5JAuQ&s'    // Ungüento Medicinal
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - ECO-HIGIENE</title>
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
        <section id="productos">
            <h2><i class="fas fa-store"></i> Nuestros Productos</h2>
            <?php if($logueado): ?>
                <p class="welcome-msg">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></p>
            <?php endif; ?>
            <p>Productos encontrados: <?php echo $num_productos; ?></p>
            <?php if ($resultado && $num_productos > 0): ?>
                <div class="productos-grid">
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <div class="producto-card">
                            <img src="<?php echo htmlspecialchars($imagenes[$row['id']]); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                            <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                            <p class="precio">Precio: $<?php echo number_format($row['precio'], 2); ?></p>
                            <a href="receta_detalle.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Ver Receta</a>
                            <?php if($logueado): ?>
                                <form action="agregar_carrito.php" method="post">
                                    <input type="hidden" name="producto_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn"><i class="fas fa-cart-plus"></i> Agregar al carrito</button>
                                </form>
                            <?php else: ?>
                                <p class="login-required"><i class="fas fa-info-circle"></i> <a href="login.php">Inicia sesión</a> para comprar</p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No se encontraron productos. Error: <?php echo $conn->error ?: 'Tabla vacía o no existe.'; ?></p>
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