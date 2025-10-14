<!-- ============================================ -->
<!-- CRUD/auth/registro.php -->
<!-- ============================================ -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        $s = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
        if (!$s) {
            $err = "Error en la consulta: " . $conexion->error;
        } else {
            $s->bind_param("s", $correo);
            $s->execute();
            $r = $s->get_result();

            if ($r && $r->num_rows > 0) {
                $err = "Correo ya registrado.";
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $ins = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, correo, contraseña, rol) VALUES (?, ?, ?, 'usuario')");
                
                if (!$ins) {
                    $err = "Error al preparar INSERT: " . $conexion->error;
                } else {
                    $ins->bind_param("sss", $nombre, $correo, $hash);

                    if ($ins->execute()) {
                        $_SESSION['usuario_id'] = $conexion->insert_id;
                        $_SESSION['usuario_nombre'] = $nombre;
                        $_SESSION['usuario_rol'] = 'usuario';
                        header("Location: ../../Assets/index.php");
                        exit;
                    } else {
                        $err = "Error al registrar: " . $ins->error;
                    }
                }
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

  <h2>Crear cuenta</h2>
  <form method="post">
    <?php if($err): ?><div class="err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
    <input name="nombre" placeholder="Nombre completo" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
    <input name="correo" type="email" placeholder="Correo electrónico" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
    <input name="contraseña" type="password" placeholder="Contraseña" required>
    <button type="submit">Registrarme</button>
    <p><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
  </form>
</body>
</html>

