<?php
include('../config.php');

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];

$sql = "INSERT INTO productos (nombre, descripcion, precio) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssd", $nombre, $descripcion, $precio);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Error al guardar: " . $conexion->error;
}
?>
