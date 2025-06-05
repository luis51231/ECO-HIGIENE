<?php
include("verificar_sesion.php");
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
        header("Location: login.php?error=Usuario no autenticado");
        exit();
    }

    $producto_id = filter_var($_POST['producto_id'], FILTER_SANITIZE_NUMBER_INT);
    $cliente_id = $_SESSION["id"];

    try {
        $sql = "INSERT INTO carrito (cliente_id, producto_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }
        $stmt->bind_param("ii", $cliente_id, $producto_id);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        error_log("Error en agregar_carrito.php: " . $e->getMessage());
        header("Location: productos.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

header("Location: carrito.php");
exit();
?>