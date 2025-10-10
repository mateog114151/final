<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $conexion->prepare("SELECT id,nombre,descripcion,precio,duracion,caracteristicas FROM servicios WHERE id = ?");
$stmt->bind_param("i",$id); $stmt->execute(); $res = $stmt->get_result();
$serv = $res->fetch_assoc();
if (!$serv) { header("Location: index.php"); exit; }
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Editar Servicio</title>
<style>body{font-family:Arial;background:#f4f4f4;padding:40px}form{background:#fff;padding:20px;width:600px;margin:auto;border-radius:8px}input,textarea{width:100%;padding:8px;margin:8px 0}button{background:#4CAF50;color:#fff;padding:10px;border:0;border-radius:5px}</style>
</head><body>
<h2 style="text-align:center">Editar Servicio</h2>
<form action="update.php" method="post">
  <input type="hidden" name="id" value="<?= $serv['id'] ?>">
  <label>Nombre</label>
  <input name="nombre" value="<?= htmlspecialchars($serv['nombre']) ?>" required>
  <label>Descripción</label>
  <textarea name="descripcion" rows="3"><?= htmlspecialchars($serv['descripcion']) ?></textarea>
  <label>Precio</label>
  <input name="precio" type="number" step="0.01" value="<?= $serv['precio'] ?>" required>
  <label>Duración</label>
  <input name="duracion" value="<?= htmlspecialchars($serv['duracion']) ?>">
  <label>Características</label>
  <textarea name="caracteristicas" rows="2"><?= htmlspecialchars($serv['caracteristicas']) ?></textarea>
  <button type="submit">Actualizar</button>
  <p style="text-align:center"><a href="index.php">Volver</a></p>
</form>
</body></html>
