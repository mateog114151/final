<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$duracion = trim($_POST['duracion'] ?? '');
$caracteristicas = trim($_POST['caracteristicas'] ?? '');

$sql = "INSERT INTO servicios (nombre, descripcion, precio, duracion, caracteristicas) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $duracion, $caracteristicas);
if ($stmt->execute()) header("Location: index.php");
else echo "Error: " . $conexion->error;
