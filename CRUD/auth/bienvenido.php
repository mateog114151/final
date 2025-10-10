<?php
session_start();
if (!isset($_SESSION['usuario_id'])) header("Location: login.php");
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Bienvenido</title>
<style>body{font-family:Arial;text-align:center;padding:80px;background:#f4f4f4} a{display:inline-block;margin:10px;padding:10px 15px;background:#2196F3;color:#fff;border-radius:6px;text-decoration:none}</style>
</head><body>
<h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? ''); ?></h1>
<p>Accesos rápidos:</p>
<a href="../productos/index.php">Ver Productos</a>
<a href="../servicios/index.php">Ver Servicios</a>
<a href="logout.php" style="background:red">Cerrar sesión</a>
</body></html>
