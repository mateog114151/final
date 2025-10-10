<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';

$id = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$precio_desc = floatval($_POST['precio_descuento'] ?? 0);
$categoria = $_POST['categoria'] ?? 'general';
$stock = intval($_POST['stock'] ?? 0);

$sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, precio_descuento=?, categoria=?, stock=? WHERE id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssddsii", $nombre, $descripcion, $precio, $precio_desc, $categoria, $stock, $id);
if ($stmt->execute()) header("Location: index.php");
else echo "Error: " . $conexion->error;
