
<?php require_once __DIR__ . '/../auth/validar_admin.php'; ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Nuevo Servicio</title>
<!-- ✅ CSS corregido -->
<link rel="stylesheet" href="/happy_pets/crud-styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head><body>
<h2 style="text-align:center">Nuevo Servicio</h2>
<form action="store.php" method="post">
  <label>Nombre</label>
  <input name="nombre" required>
  <label>Descripción</label>
  <textarea name="descripcion" rows="3"></textarea>
  <label>Precio</label>
  <input name="precio" type="number" step="0.01" value="0.00" required>
  <label>Duración</label>
  <input name="duracion" placeholder="Ej: 30 min, 1 hora">
  <label>Características</label>
  <textarea name="caracteristicas" rows="2"></textarea>
  <button type="submit">Guardar</button>
  <p style="text-align:center"><a href="index.php">Volver</a></p>
</form>
</body></html>
