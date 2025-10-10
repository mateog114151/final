<?php
session_start();
require_once __DIR__ . '/../config.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pass = $_POST['contraseña'] ?? '';

    if (!$nombre || !$correo || !$pass) {
        $err = "Completa todos los campos.";
    } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $err = "Correo no válido.";
    } else {
        // Verificar si el correo ya está registrado
        $s = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $s->bind_param("s", $correo);
        $s->execute();
        $r = $s->get_result();

        if ($r && $r->num_rows > 0) {
            $err = "Correo ya registrado.";
        } else {
            // Encriptar contraseña
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            // Insertar nuevo usuario con rol 'usuario'
            $ins = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, correo, contraseña, rol) VALUES (?, ?, ?, 'usuario')");
            $ins->bind_param("sss", $nombre, $correo, $hash);

            if ($ins->execute()) {
                echo "<script>alert('Registro exitoso. Inicia sesión'); window.location='login.php';</script>";
                exit;
            } else {
                $err = "Error al registrar: " . $conexion->error;
            }
        }
    }
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registro - Happy Pets</title>
  <style>
    body {
      font-family: Arial;
      background: #f4f4f4;
      padding: 50px;
    }
    form {
      background: #fff;
      padding: 20px;
      width: 360px;
      margin: auto;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input {
      width: 100%;
      padding: 8px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      background: #4CAF50;
      color: #fff;
      padding: 10px;
      border: 0;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
    }
    button:hover {
      background: #45a049;
    }
    .err {
      color: #c00;
      text-align: center;
      margin-bottom: 10px;
    }
    a {
      text-decoration: none;
      color: #4CAF50;
    }
  </style>
</head>
<body>
  <h2 style="text-align:center">Crear cuenta</h2>
  <form method="post">
    <?php if($err): ?>
      <div class="err"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>
    <input name="nombre" placeholder="Nombre completo" required>
    <input name="correo" type="email" placeholder="Correo electrónico" required>
    <input name="contraseña" type="password" placeholder="Contraseña" required>
    <button type="submit">Registrarme</button>
    <p style="text-align:center"><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
  </form>
</body>
</html>
