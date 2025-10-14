<?php
// registro.php
session_start();
require_once __DIR__ . '/../config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pass = $_POST['contraseña'] ?? '';

    if (!$nombre || !$correo || !$pass) $err = "Completa todos los campos.";
    else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $err = "Correo no válido.";
    else {
        $s = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $s->bind_param("s", $correo);
        $s->execute();
        $r = $s->get_result();
        if ($r && $r->num_rows > 0) $err = "Correo ya registrado.";
        else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $ins = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES (?, ?, ?, 'user')");
            $ins->bind_param("sss", $nombre, $correo, $hash);
            if ($ins->execute()) {
                $_SESSION['usuario_id'] = $ins->insert_id;
                $_SESSION['usuario_nombre'] = $nombre;
                $_SESSION['usuario_rol'] = 'user';
                header("Location: /happy_pets/Assets/index.php"); // vuelve al sitio público
                exit;
            } else $err = "Error al registrar: " . $conexion->error;
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Registro</title>
<!-- ✅ CSS corregido -->
<link rel="stylesheet" href="/happy_pets/crud-styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<h2>Registro</h2>
<?php if ($err): ?><div style="color:red"><?=htmlspecialchars($err)?></div><?php endif; ?>
<form method="post">
  <input name="nombre" placeholder="Nombre completo" required>
  <input name="correo" type="email" placeholder="Correo" required>
  <input name="contraseña" type="password" placeholder="Contraseña" required>
  <button type="submit">Registrarme</button>
</form>
</body>
</html>
