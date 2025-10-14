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

<!-- ✅ CSS corregido -->
<link rel="stylesheet" href="/happy_pets/crud-styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <header class="hp-crud-header">
  <div class="container-header">
    <div class="logo-section">
      <img class="logo" src="/happy_pets/Public/img/WhatsApp Image 2025-09-16 at 7.23.51 PM.jpeg" alt="Happy Pets Logo">
      <h1>Panel de Administración</h1>
    </div>
    <nav class="nav-header">
      <a href="/happy_pets/Assets/index.php" class="btn-nav">Volver al sitio</a>
      <a href="/happy_pets/CRUD/servicios/index.php" class="btn-nav">CRUD Servicios</a>
      <a href="/happy_pets/CRUD/auth/logout.php" class="btn-nav logout">Cerrar sesión</a>
    </nav>
  </div>
</header>



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
