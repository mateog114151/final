<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';
$stmt = $conexion->query("SELECT id,nombre,descripcion,precio,duracion,caracteristicas,fecha_creacion FROM servicios ORDER BY id DESC");
$servicios = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Servicios</title>
<style>body{font-family:Arial;background:#f9f9f9;padding:30px}table{width:95%;margin:auto;border-collapse:collapse}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#333;color:#fff}a.btn{padding:6px 10px;background:#2196F3;color:#fff;border-radius:5px;text-decoration:none;margin-right:6px}a.danger{background:#d9534f}</style>
</head><body>
<div style="text-align:right;margin-bottom:10px">
    <span>👋 <?= htmlspecialchars($_SESSION['admin_nombre']) ?></span>
    <a href="../auth/logout.php" style="margin-left:12px;background:red;color:#fff;padding:6px 10px;border-radius:4px;text-decoration:none;">Cerrar sesión</a>
</div>
<h2 style="text-align:center">Servicios</h2>
<a href="../productos/index.php"><i class="fa-solid fa-paw"></i> CRUD Productos</a>
<p style="text-align:center"><a href="create.php" class="btn">+ Nuevo Servicio</a></p>
<table>
<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Duración</th><th>Características</th><th>Creado</th><th>Acciones</th></tr>
<?php foreach($servicios as $s): ?>
<tr>
  <td><?= $s['id'] ?></td>
  <td><?= htmlspecialchars($s['nombre']) ?></td>
  <td><?= htmlspecialchars($s['descripcion']) ?></td>
  <td>$<?= number_format($s['precio'],2) ?></td>
  <td><?= htmlspecialchars($s['duracion']) ?></td>
  <td><?= htmlspecialchars($s['caracteristicas']) ?></td>
  <td><?= $s['fecha_creacion'] ?? '' ?></td>
  <td>
    <a href="edit.php?id=<?= $s['id'] ?>" class="btn">Editar</a>
    <a href="delete.php?id=<?= $s['id'] ?>" class="btn danger" onclick="return confirm('Eliminar?')">Eliminar</a>
  </td>
</tr>
<?php endforeach; ?>
</table>
</body></html>
