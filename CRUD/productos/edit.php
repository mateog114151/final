<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $conexion->prepare("SELECT id,nombre,descripcion,precio,precio_descuento,categoria,stock FROM productos WHERE id = ?");
$stmt->bind_param("i",$id); $stmt->execute(); $res = $stmt->get_result();
$prod = $res->fetch_assoc();
if (!$prod) { header("Location: index.php"); exit; }
?>
<!doctype html><html lang="es"><head>
  <meta charset="utf-8">
  <title>Panel - Happy Pets</title>
<link rel="stylesheet" href="/happy_pets/styles.css">
<link rel="stylesheet" href="/happy_pets/crud-styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
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
