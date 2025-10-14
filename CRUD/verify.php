<!-- ============================================ -->
<!-- CRUD/verify.php -->
<!-- ============================================ -->
<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/config.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $pass = $_POST['contraseña'] ?? '';

    if ($correo === '' || $pass === '') $err = "Completa correo y contraseña.";
    else {
        $stmt = $conexion->prepare("SELECT id,nombre,contraseña FROM administradores WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            $err = "Administrador no registrado.";
        } else {
            $a = $res->fetch_assoc();
            if (password_verify($pass, $a['contraseña'])) {
                $_SESSION['admin_id'] = $a['id'];
                $_SESSION['admin_nombre'] = $a['nombre'];
                $_SESSION['is_admin'] = true;
                header("Location: productos/index.php");
                exit;
            } else {
                $err = "Contraseña incorrecta.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Verificación CRUD - Happy Pets</title>

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

  <h2>Verificación para CRUD (Admin)</h2>
  <form method="post">
    <?php if($err): ?><div class="err"><?=htmlspecialchars($err)?></div><?php endif; ?>
    <input name="correo" type="email" placeholder="Correo de administrador" required>
    <input name="contraseña" type="password" placeholder="Contraseña" required>
    <button type="submit">Entrar al CRUD</button>
    <p><a href="auth/login.php">← Ir a Iniciar Sesión (usuarios)</a></p>
  </form>
</body>
</html>