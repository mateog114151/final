<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';
$stmt = $conexion->query("SELECT id,nombre,descripcion,precio,precio_descuento,categoria,stock,fecha_creacion FROM productos ORDER BY id DESC");
$productos = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Productos</title>
<style>body{font-family:Arial;background:#f9f9f9;padding:30px}table{width:95%;margin:auto;border-collapse:collapse}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#333;color:#fff}a.btn{padding:6px 10px;background:#2196F3;color:#fff;border-radius:5px;text-decoration:none;margin-right:6px}a.danger{background:#d9534f}</style>
</head><body>
<div style="text-align:right;margin-bottom:10px">
    <span>👋 <?= htmlspecialchars($_SESSION['admin_nombre']) ?></span>
    <a href="../auth/logout.php" style="margin-left:12px;background:red;color:#fff;padding:6px 10px;border-radius:4px;text-decoration:none;">Cerrar sesión</a>
</div>
<h2 style="text-align:center">Productos</h2>
<p style="text-align:center"><a href="create.php" class="btn">+ Nuevo Producto</a></p>
<table>
<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Precio desc</th><th>Categoría</th><th>Stock</th><th>Creado</th><th>Acciones</th></tr>
<?php foreach($productos as $p): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?= htmlspecialchars($p['nombre']) ?></td>
  <td><?= htmlspecialchars($p['descripcion']) ?></td>
  <td>$<?= number_format($p['precio'],2) ?></td>
  <td>$<?= number_format($p['precio_descuento'],2) ?></td>
  <td><?= htmlspecialchars($p['categoria']) ?></td>
  <td><?= intval($p['stock']) ?></td>
  <td><?= $p['fecha_creacion'] ?? '' ?></td>
  <td>
    <a href="edit.php?id=<?= $p['id'] ?>" class="btn">Editar</a>
    <a href="delete.php?id=<?= $p['id'] ?>" class="btn danger" onclick="return confirm('Eliminar?')">Eliminar</a>
  </td>
</tr>
<?php endforeach; ?>
</table>
</body></html>
