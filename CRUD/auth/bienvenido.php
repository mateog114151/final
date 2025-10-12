<?php
session_start();
if (!isset($_SESSION['usuario_id'])) header("Location: login.php");
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Bienvenido</title>
<link rel="stylesheet" href="../Public/css/crud-styles.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head><body>
<h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? ''); ?></h1>
<p>Accesos rápidos:</p>
<a href="../productos/index.php">Ver Productos</a>
<a href="../servicios/index.php">Ver Servicios</a>
<a href="logout.php" style="background:red">Cerrar sesión</a>
</body></html>
