<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';

$id = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$duracion = trim($_POST['duracion'] ?? '');
$caracteristicas = trim($_POST['caracteristicas'] ?? '');

$sql = "UPDATE servicios SET nombre=?, descripcion=?, precio=?, duracion=?, caracteristicas=? WHERE id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssdssi", $nombre, $descripcion, $precio, $duracion, $caracteristicas, $id);
if ($stmt->execute()) header("Location: index.php");
else echo "Error: " . $conexion->error;
