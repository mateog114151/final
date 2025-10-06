<?php
include('../config.php');

$id = $_GET['id'];

$sql = "DELETE FROM servicios WHERE id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Error al eliminar: " . $conexion->error;
}
?>
