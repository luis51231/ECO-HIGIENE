<?php
include("conexion.php");
include("verificar_sesion.php");

$carrito_id = filter_input(INPUT_POST, 'carrito_id', FILTER_SANITIZE_NUMBER_INT);

$sql = "DELETE FROM carrito WHERE id = ? AND cliente_id = (SELECT id FROM clientes WHERE email = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $carrito_id, $_SESSION["cliente"]);
$stmt->execute();

header("Location: carrito.php");
exit();
?>