<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $conexion->prepare("DELETE FROM servicios WHERE id = ?");
$stmt->bind_param("i",$id);
if ($stmt->execute()) header("Location: index.php");
else echo "Error: " . $conexion->error;
