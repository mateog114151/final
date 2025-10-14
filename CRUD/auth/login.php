<?php
session_start();
require_once __DIR__ . '/../config.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $pass = $_POST['contraseña'] ?? '';

    if ($correo === '' || $pass === '') {
        $err = "Completa todos los campos.";
    } else {
        $s = $conexion->prepare("SELECT id, nombre_usuario, contraseña, rol FROM usuarios WHERE correo = ?");
        $s->bind_param("s", $correo);
        $s->execute();
        $r = $s->get_result();

        if ($r->num_rows === 0) {
            $err = "Usuario no encontrado.";
        } else {
            $u = $r->fetch_assoc();
            if (!password_verify($pass, $u['contraseña'])) {
                $err = "Contraseña incorrecta.";
            } else {
                // Guardar sesión (independiente del rol)
                $_SESSION['usuario_id'] = $u['id'];
                $_SESSION['usuario_nombre'] = $u['nombre_usuario'];
                $_SESSION['usuario_rol'] = $u['rol'];

                // IMPORTANTE: no redirigir al CRUD/productos aquí.
                // Volver a la página pública principal
                header("Location: ../../assets/index.php");
                exit;
            }
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login - Happy Pets</title>
  <link rel="stylesheet" href="../../Public/css/crud-styles.css">
<!-- hoja base del sitio -->
  <link rel="stylesheet" href="../../Public/css/styles.css">

  <!-- ajustes específicos CRUD -->
  <link rel="stylesheet" href="../../Public/css/crud-styles.css">

  <!-- fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <h2 style="text-align:center">Iniciar Sesión</h2>
  <form method="post">
    <?php if($err): ?><div class="err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
    <input name="correo" type="email" placeholder="Correo" required>
    <input name="contraseña" type="password" placeholder="Contraseña" required>
    <button type="submit">Ingresar</button>
    <p style="text-align:center"><a href="registro.php">Crear cuenta</a></p>
    <p style="text-align:center; font-size:0.9em; color:#666;">Si eres administrador y quieres acceder al CRUD, usa el botón <strong>CRUD</strong> en el menú (verificación separada).</p>
  </form>
</body>
</html>
