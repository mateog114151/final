<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
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
                // set session as admin
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
<head><meta charset="utf-8"><title>Verificación CRUD</title>
<style>
 body{font-family:Arial;background:#f4f4f4;padding:50px}
 form{background:#fff;padding:20px;width:360px;margin:auto;border-radius:8px;box-shadow:0 4px 10px rgba(0,0,0,0.08)}
 input{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:4px}
 button{background:#4CAF50;color:#fff;padding:10px;border:0;border-radius:5px;cursor:pointer}
 .err{color:#c00;margin-bottom:8px}
 a {display:block;text-align:center;margin-top:10px;color:#2196F3}
</style>
</head>
<body>
<h2 style="text-align:center">Verificación para CRUD (Admin)</h2>
<form method="post">
    <?php if($err): ?><div class="err"><?=htmlspecialchars($err)?></div><?php endif; ?>
    <input name="correo" type="email" placeholder="Correo de administrador" required>
    <input name="contraseña" type="password" placeholder="Contraseña" required>
    <button type="submit">Entrar al CRUD</button>
    <a href="auth/login.php">Ir a Iniciar Sesión (usuarios)</a>
</form>
</body>
</html>
