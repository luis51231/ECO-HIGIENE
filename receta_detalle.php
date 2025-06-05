<?php
session_start();
include("conexion.php");

$logueado = isset($_SESSION["cliente"]);
$producto_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

// Arreglo de imágenes para los productos
$imagenes = [
    1 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFMF9ZUdiw4MMrFSuhfd73LoNndpQIQ_5Y5g&s', // Gel Antibacterial
    2 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5UAMVNk-0AAC4Lfgcu5P5CoKDmhBuv4ZkGA&s', // Labial Natural
    3 => 'https://mejorconsalud.as.com/wp-content/uploads/2014/05/cremas-corporales-caseras.jpg', // Crema Corporal
    4 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTBMD_vB6cfvpRetvGzmtAcvRs03ctdnmZFUQ&s',      // Acetona Natural
    5 => 'https://www.hacercremas.es/wp-content/uploads/2014/05/aceite-desmaquillante-3.jpg',       // Desmaquillante
    6 => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSZWvgahGIzJSXDnIoy0Ac6O4rM4HHL5JAuQ&s'    // Ungüento Medicinal
];

// Obtener datos del producto
$producto = null;
$resultado = $conn->query("SELECT nombre FROM productos WHERE id = $producto_id");
if ($resultado && $resultado->num_rows > 0) {
    $producto = $resultado->fetch_assoc();
}

// Definir recetas
$recetas = [
    1 => [
        'nombre' => 'Gel Antibacterial',
        'ingredientes' => [
            '100 ml de alcohol etílico (70%)',
            '30 ml de gel de aloe vera',
            '10 gotas de aceite esencial de árbol de té',
            '5 gotas de aceite esencial de lavanda'
        ],
        'procedimiento' => [
            'En un recipiente limpio, mezcla el alcohol etílico con el gel de aloe vera hasta obtener una consistencia homogénea.',
            'Añade los aceites esenciales y revuelve suavemente.',
            'Vierte la mezcla en un frasco con dispensador.',
            'Agita antes de usar. Aplica una pequeña cantidad en las manos y frota hasta que se seque.'
        ]
    ],
    2 => [
        'nombre' => 'Labial Natural',
        'ingredientes' => [
            '10 g de cera de abeja',
            '10 g de manteca de karité',
            '10 ml de aceite de coco',
            '5 g de polvo de remolacha (para color natural)'
        ],
        'procedimiento' => [
            'Derrite la cera de abeja y la manteca de karité a baño maría.',
            'Añade el aceite de coco y mezcla bien.',
            'Incorpora el polvo de remolacha para dar color y revuelve.',
            'Vierte la mezcla en un tubo de labial limpio y deja enfriar por 2 horas.'
        ]
    ],
    3 => [
        'nombre' => 'Crema Corporal',
        'ingredientes' => [
            '50 g de manteca de cacao',
            '30 ml de aceite de almendras',
            '20 ml de agua de rosas',
            '10 gotas de aceite esencial de geranio'
        ],
        'procedimiento' => [
            'Derrite la manteca de cacao a baño maría.',
            'Añade el aceite de almendras y mezcla.',
            'Retira del fuego y agrega el agua de rosas lentamente, batiendo hasta emulsionar.',
            'Incorpora el aceite esencial y vierte en un frasco limpio. Deja enfriar antes de usar.'
        ]
    ],
    4 => [
        'nombre' => 'Acetona Natural',
        'ingredientes' => [
            '50 ml de vinagre blanco',
            '50 ml de jugo de limón',
            '10 ml de aceite de oliva'
        ],
        'procedimiento' => [
            'Mezcla el vinagre blanco y el jugo de limón en un frasco.',
            'Añade el aceite de oliva y agita bien.',
            'Aplica con un algodón sobre las uñas para remover esmalte.',
            'Lava las manos después de usar.'
        ]
    ],
    5 => [
        'nombre' => 'Desmaquillante',
        'ingredientes' => [
            '50 ml de aceite de jojoba',
            '30 ml de agua de hamamelis',
            '10 gotas de aceite esencial de manzanilla'
        ],
        'procedimiento' => [
            'Combina el aceite de jojoba y el agua de hamamelis en un frasco.',
            'Añade el aceite esencial de manzanilla y agita.',
            'Aplica con un algodón para remover el maquillaje.',
            'Enjuaga con agua tibia.'
        ]
    ],
    6 => [
        'nombre' => 'Ungüento Medicinal',
        'ingredientes' => [
            '20 g de cera de abeja',
            '50 ml de aceite de oliva infusionado con caléndula',
            '10 gotas de aceite esencial de eucalipto',
            '5 g de miel cruda'
        ],
        'procedimiento' => [
            'Derrite la cera de abeja a baño maría.',
            'Añade el aceite de oliva infusionado y mezcla.',
            'Retira del fuego, agrega la miel y el aceite esencial, y revuelve.',
            'Vierte en un frasco pequeño y deja enfriar. Aplica en heridas menores o piel irritada.'
        ]
    ]
];

$receta = isset($recetas[$producto_id]) ? $recetas[$producto_id] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receta - ECO-HIGIENE</title>
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
        <section id="receta">
            <?php if ($producto && $receta): ?>
                <h2><i class="fas fa-book"></i> Receta: <?php echo htmlspecialchars($producto['nombre']); ?></h2>
                <img src="<?php echo htmlspecialchars($imagenes[$producto_id]); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="receta-img">
                <h3>Ingredientes</h3>
                <ul>
                    <?php foreach ($receta['ingredientes'] as $ingrediente): ?>
                        <li><?php echo htmlspecialchars($ingrediente); ?></li>
                    <?php endforeach; ?>
                </ul>
                <h3>Procedimiento</h3>
                <ol>
                    <?php foreach ($receta['procedimiento'] as $paso): ?>
                        <li><?php echo htmlspecialchars($paso); ?></li>
                    <?php endforeach; ?>
                </ol>
                <a href="productos.php" class="btn btn-secondary">Volver a Productos</a>
            <?php else: ?>
                <p class="error">Receta no encontrada.</p>
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