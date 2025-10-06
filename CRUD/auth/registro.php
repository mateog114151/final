<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // 🔒 Encriptar

    // Verificar si ya existe el correo
    $verificar = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $verificar->bind_param("s", $correo);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        echo "<script>alert('El correo ya está registrado'); window.location='registro.php';</script>";
        exit;
    }

    $sql = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nombre, $correo, $contraseña);

    if ($stmt->execute()) {
        echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión'); window.location='login.php';</script>";
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Happy Pets</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        form { background: white; width: 400px; margin: auto; padding: 20px; border-radius: 10px; }
        input { width: 100%; padding: 10px; margin-top: 10px; }
        button { background: #4CAF50; color: white; border: none; padding: 10px 15px; margin-top: 15px; border-radius: 5px; cursor: pointer; }
        a { display: block; margin-top: 15px; text-align: center; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Crear cuenta</h2>
<form action="" method="POST">
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="correo" placeholder="Correo electrónico" required>
    <input type="password" name="contraseña" placeholder="Contraseña" required>
    <button type="submit">Registrarme</button>
    <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
</form>

</body>
</html>
