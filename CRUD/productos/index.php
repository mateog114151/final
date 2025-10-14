<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';
$stmt = $conexion->query("SELECT id, nombre, descripcion, precio, precio_descuento, categoria, stock, fecha_creacion FROM productos ORDER BY id DESC");
$productos = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>CRUD Productos - Happy Pets</title>

<link rel="stylesheet" href="../../Public/css/styles.css">
<link rel="stylesheet" href="../../Public/css/crud-styles.css">


  <!-- Íconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

  <div class="hp-crud-header">
    <div class="left">
      <img class="logo" src="/happy_pets/Public/img/WhatsApp Image 2025-09-16 at 7.23.51 PM.jpeg" alt="Happy Pets Logo">
      <div class="brand">Happy Pets - Admin</div>
    </div>
    <div class="actions">
      <a href="/happy_pets/assets/index.php" class="btn-home">Volver al sitio</a>
      <a href="/happy_pets/CRUD/servicios/index.php" class="btn">CRUD Servicios</a>
      <a href="/happy_pets/CRUD/auth/logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
  </div>

  <main>
    <h2>Productos Registrados</h2>
    <div class="crud-header-actions">
      <a href="create.php" class="btn-switch"><i class="fa-solid fa-plus"></i> Nuevo Producto</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Descuento</th>
          <th>Categoría</th><th>Stock</th><th>Creado</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($productos as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nombre']) ?></td>
            <td><?= htmlspecialchars($p['descripcion']) ?></td>
            <td>$<?= number_format($p['precio'],2) ?></td>
            <td>$<?= number_format($p['precio_descuento'],2) ?></td>
            <td><?= htmlspecialchars($p['categoria']) ?></td>
            <td><?= intval($p['stock']) ?></td>
            <td><?= htmlspecialchars($p['fecha_creacion'] ?? '') ?></td>
            <td>
              <a href="edit.php?id=<?= $p['id'] ?>" class="btn"><i class="fa-solid fa-pen"></i> Editar</a>
              <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar este producto?')">
                <i class="fa-solid fa-trash"></i> Eliminar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>

  <footer>
    © <?= date('Y') ?> Happy Pets | Todos los derechos reservados
  </footer>

</body>
</html>
