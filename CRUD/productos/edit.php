<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $conexion->prepare("SELECT id,nombre,descripcion,precio,precio_descuento,categoria,stock FROM productos WHERE id = ?");
$stmt->bind_param("i",$id); $stmt->execute(); $res = $stmt->get_result();
$prod = $res->fetch_assoc();
if (!$prod) { header("Location: index.php"); exit; }
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Editar Producto</title>
<style>body{font-family:Arial;background:#f4f4f4;padding:40px}form{background:#fff;padding:20px;width:600px;margin:auto;border-radius:8px}input,textarea,select{width:100%;padding:8px;margin:8px 0}button{background:#4CAF50;color:#fff;padding:10px;border:0;border-radius:5px}</style>
</head><body>
<h2 style="text-align:center">Editar Producto</h2>
<form action="update.php" method="post">
  <input type="hidden" name="id" value="<?= $prod['id'] ?>">
  <label>Nombre</label>
  <input name="nombre" value="<?= htmlspecialchars($prod['nombre']) ?>" required>
  <label>Descripción</label>
  <textarea name="descripcion" rows="3"><?= htmlspecialchars($prod['descripcion']) ?></textarea>
  <label>Precio</label>
  <input name="precio" type="number" step="0.01" value="<?= $prod['precio'] ?>" required>
  <label>Precio descuento</label>
  <input name="precio_descuento" type="number" step="0.01" value="<?= $prod['precio_descuento'] ?>">
  <label>Categoría</label>
  <select name="categoria">
    <option value="general" <?= $prod['categoria']=='general' ? 'selected':'' ?>>general</option>
    <option value="perros" <?= $prod['categoria']=='perros' ? 'selected':'' ?>>perros</option>
    <option value="gatos" <?= $prod['categoria']=='gatos' ? 'selected':'' ?>>gatos</option>
  </select>
  <label>Stock</label>
  <input name="stock" type="number" value="<?= intval($prod['stock']) ?>">
  <button type="submit">Actualizar</button>
  <p style="text-align:center"><a href="index.php">Volver</a></p>
</form>
</body></html>
