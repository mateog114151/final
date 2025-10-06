<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($contraseña, $usuario['contraseña'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            if ($usuario['rol'] === 'admin') {
                header("Location: ../productos/index.php");
            } else {
                header("Location: ../auth/bienvenido.php");
            }
            exit;
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Happy Pets</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        form { background: white; width: 400px; margin: auto; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin-top: 10px; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #2196F3; color: white; border: none; padding: 10px 15px; margin-top: 15px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #1976D2; }
        a { display: block; margin-top: 15px; text-align: center; color: #2196F3; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Iniciar Sesión</h2>
<form action="" method="POST">
    <input type="email" name="correo" placeholder="Correo electrónico" required>
    <input type="password" name="contraseña" placeholder="Contraseña" required>
    <button type="submit">Ingresar</button>
    <a href="registro.php">¿No tienes cuenta? Regístrate</a>
</form>

</body>
</html>
