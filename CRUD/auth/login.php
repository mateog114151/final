<?php
session_start();
require_once __DIR__ . '/../config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $pass = $_POST['contraseña'] ?? '';
    if ($correo === '' || $pass === '') $err = "Completa todos los campos.";
    else {
        $s = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $s->bind_param("s",$correo); $s->execute();
        $r = $s->get_result();
        if ($r->num_rows === 0) $err = "Usuario no encontrado.";
        else {
            $u = $r->fetch_assoc();
            if (!password_verify($pass, $u['contraseña'])) $err = "Contraseña incorrecta.";
            else {
                $_SESSION['usuario_id'] = $u['id'];
                $_SESSION['usuario_nombre'] = $u['nombre'];
                $_SESSION['usuario_rol'] = $u['rol'];
                if ($u['rol'] === 'admin') header("Location: ../productos/index.php");
                else header("Location: bienvenido.php");
                exit;
            }
        }
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Login - Happy Pets</title>
<style>body{font-family:Arial;background:#f4f4f4;padding:50px}form{background:#fff;padding:20px;width:360px;margin:auto;border-radius:8px}input{width:100%;padding:8px;margin:8px 0}button{background:#2196F3;color:#fff;padding:10px;border:0;border-radius:5px}.err{color:#c00}</style>
</head><body>
<h2 style="text-align:center">Iniciar Sesión</h2>
<form method="post">
  <?php if($err):?><div class="err"><?=htmlspecialchars($err)?></div><?php endif;?>
  <input name="correo" type="email" placeholder="Correo" required>
  <input name="contraseña" type="password" placeholder="Contraseña" required>
  <button type="submit">Ingresar</button>
  <p style="text-align:center"><a href="registro.php">Crear cuenta</a></p>
</form>
</body></html>
