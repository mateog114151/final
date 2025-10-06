<?php
include('../config.php');

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];

$sql = "UPDATE servicios SET nombre=?, descripcion=?, precio=? WHERE id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $id);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Error al actualizar: " . $conexion->error;
}
?>
