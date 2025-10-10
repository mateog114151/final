<?php require_once __DIR__ . '/../auth/validar_admin.php'; ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Nuevo Producto</title>
<style>body{font-family:Arial;background:#f4f4f4;padding:40px}form{background:#fff;padding:20px;width:600px;margin:auto;border-radius:8px}input,textarea,select{width:100%;padding:8px;margin:8px 0}button{background:#4CAF50;color:#fff;padding:10px;border:0;border-radius:5px}</style>
</head><body>
<h2 style="text-align:center">Nuevo Producto</h2>
<form action="store.php" method="post">
  <label>Nombre</label>
  <input name="nombre" required>
  <label>Descripción</label>
  <textarea name="descripcion" rows="3"></textarea>
  <label>Precio</label>
  <input name="precio" type="number" step="0.01" value="0.00" required>
  <label>Precio descuento</label>
  <input name="precio_descuento" type="number" step="0.01" value="0.00">
  <label>Categoría</label>
  <select name="categoria">
    <option value="general">general</option>
    <option value="perros">perros</option>
    <option value="gatos">gatos</option>
  </select>
  <label>Stock</label>
  <input name="stock" type="number" value="0">
  <button type="submit">Guardar</button>
  <p style="text-align:center"><a href="index.php">Volver</a></p>
</form>
</body></html>
