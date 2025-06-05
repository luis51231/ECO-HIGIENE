<?php
session_start();
include("conexion.php");

$logueado = isset($_SESSION["id"]);
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
    <title>ECO-HIGIENE</title>
    <link rel="stylesheet" href="ECO-HIGIENE.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header id="header">
        <div class="container">
            <h1><i class="fas fa-leaf"></i> ECO-HIGIENE</h1>
            <p>Higiene personal con conciencia ecológica</p>
        </div>
    </header>

    <nav id="nav">
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
        <section id="hero">
            <h2>Elabora tus propios productos naturales</h2>
            <p>Descubre recetas sencillas para crear productos de cuidado personal efectivos, económicos y amigables con el medio ambiente.</p>
            <a href="productos.php" class="btn">Ver Productos</a>
        </section>

        <section id="introduccion">
            <h2>¿Qué es la Ecohigiene?</h2>
            <p>La ecohigiene es la práctica de usar productos de higiene personal amigables con el medio ambiente, elaborados con ingredientes naturales y biodegradables.</p>
            <h3>¿Por qué es importante?</h3>
            <p>Contribuye a cuidar tu salud y el planeta al reducir el uso de químicos sintéticos.</p>
        </section>

        <section id="recetas">
            <h2>Nuestras Recetas</h2>
            <p>Productos encontrados: <?php echo $num_productos; ?></p>
            <?php if ($resultado && $num_productos > 0): ?>
                <div class="recetas-grid">
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <div class="receta-card">
                            <img src="<?php echo htmlspecialchars($imagenes[$row['id']]); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                            <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                            <p>Consulta la receta completa.</p>
                            <a href="receta_detalle.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Ver Receta</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No se encontraron productos. Error: <?php echo $conn->error ?: 'Tabla vacía o no existe.'; ?></p>
            <?php endif; ?>
        </section>

        <section id="beneficios">
            <h2>¿Por qué elaborar tus propios productos?</h2>
            <div class="beneficios-grid">
                <div class="beneficio-card"><h3>Ahorro</h3><p>Hasta 70% menos que productos comerciales.</p></div>
                <div class="beneficio-card"><h3>Natural</h3><p>Sin químicos dañinos.</p></div>
                <div class="beneficio-card"><h3>Ecológico</h3><p>Reduce tu huella de carbono.</p></div>
                <div class="beneficio-card"><h3>Personalizable</h3><p>Adapta a tus necesidades.</p></div>
            </div>
        </section>
    </main>

    <footer id="footer">
        <div class="container">
            <p>© 2025 ECO-HIGIENE. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
<?php
$conn->close();
?>