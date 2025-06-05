<?php
include("verificar_sesion.php");
include("conexion.php");

$logueado = isset($_SESSION["cliente"]);
$resultado = $conn->query("SELECT v.fecha_venta, p.nombre AS producto, cl.nombre AS cliente, v.precio FROM ventas v JOIN productos p ON v.producto_id = p.id JOIN clientes cl ON v.cliente_id = cl.id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - ECO-HIGIENE</title>
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
        <section id="ventas">
            <h2><i class="fas fa-dollar-sign"></i> Registro de Ventas</h2>
            <?php if ($resultado->num_rows > 0): ?>
                <table class="ventas-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_venta'])); ?></td>
                                <td><?php echo htmlspecialchars($row['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($row['producto']); ?></td>
                                <td>$<?php echo number_format($row['precio'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay ventas registradas.</p>
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