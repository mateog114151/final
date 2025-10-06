<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido - Happy Pets</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; text-align: center; padding-top: 100px; }
        h1 { color: #333; }
        a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2196F3; color: white; border-radius: 5px; text-decoration: none; }
        a.logout { background: red; }
    </style>
</head>
<body>

    <h1>🐾 ¡Hola, <?= $_SESSION['usuario_nombre']; ?>!</h1>
    <p>Bienvenido a Happy Pets 🐶🐱</p>

    <a href="../productos/index.php">Ver Productos</a>
    <a href="../servicios/index.php">Ver Servicios</a>
    <a href="logout.php" class="logout">Cerrar sesión</a>

</body>
</html>
