<!-- ============================================ -->
<!-- CRUD/auth/login.php -->
<!-- ============================================ -->
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
                $_SESSION['usuario_id'] = $u['id'];
                $_SESSION['usuario_nombre'] = $u['nombre_usuario'];
                $_SESSION['usuario_rol'] = $u['rol'];
                header("Location: ../../Assets/index.php");
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
  
  <link rel="stylesheet" href="/happy_pets/crud-styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  
  <!-- Botón volver al inicio -->
  <div style="text-align: center; padding: 20px 0;">
    <a href="/happy_pets/Assets/index.php" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 25px; background: linear-gradient(135deg, #ff8fc1, #ff4f9e); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; box-shadow: 0 4px 15px rgba(255, 100, 150, 0.3); transition: all 0.3s ease;">
      <i class="fa-solid fa-home"></i> Volver al Inicio
    </a>
  </div>

  <h2>Iniciar Sesión</h2>
  <form method="post">
    <?php if($err): ?><div class="err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
    <input name="correo" type="email" placeholder="Correo" required>
    <input name="contraseña" type="password" placeholder="Contraseña" required>
    <button type="submit">Ingresar</button>
    <p><a href="registro.php">Crear cuenta</a></p>
    <p style="font-size:0.9em; color:#666;">Si eres administrador y quieres acceder al CRUD, usa el botón <strong>CRUD</strong> en el menú (verificación separada).</p>
  </form>
</body>
</html>