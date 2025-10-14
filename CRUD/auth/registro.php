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
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $err = "Correo no válido.";
    } else {
        // Verificar duplicado
        $s = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $s->bind_param("s", $correo);
        $s->execute();
        $r = $s->get_result();

        if ($r && $r->num_rows > 0) {
            $err = "Correo ya registrado.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            // INSERT: usamos nombre_usuario según la tabla que vas a crear
            $ins = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, correo, contraseña, rol) VALUES (?, ?, ?, 'usuario')");
            $ins->bind_param("sss", $nombre, $correo, $hash);

            if ($ins->execute()) {
                // Auto-login: guardar sesión y redirigir al index público
                $_SESSION['usuario_id'] = $conexion->insert_id;
                $_SESSION['usuario_nombre'] = $nombre;
                $_SESSION['usuario_rol'] = 'usuario';

                // Redirigir a la página pública principal (assets/index.html)
                header("Location: ../../assets/index.php");
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
  <title>CRUD Productos - Happy Pets</title>

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Enlaces correctos a tus estilos -->
  <link rel="stylesheet" href="/happy_pets/styles.css">
  <link rel="stylesheet" href="/happy_pets/crud-styles.css">

  <!-- Íconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
  <h2 style="text-align:center">Crear cuenta</h2>
  <form method="post">
    <?php if($err): ?><div class="err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
    <input name="nombre" placeholder="Nombre completo" required>
    <input name="correo" type="email" placeholder="Correo electrónico" required>
    <input name="contraseña" type="password" placeholder="Contraseña" required>
    <button type="submit">Registrarme</button>
    <p style="text-align:center"><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
  </form>
</body>
</html>
