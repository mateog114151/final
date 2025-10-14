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
<!-- ✅ CSS corregido -->
<link rel="stylesheet" href="/happy_pets/crud-styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

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
