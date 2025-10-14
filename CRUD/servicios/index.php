<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';
$stmt = $conexion->query("SELECT id, nombre, descripcion, precio, duracion, caracteristicas, fecha_creacion FROM servicios ORDER BY id DESC");
$servicios = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>CRUD Servicios - Happy Pets</title>
<!-- ✅ CSS corregido -->
<link rel="stylesheet" href="/happy_pets/crud-styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">Actualizar
</head>

<body>

  <!-- === HEADER === -->
  <div class="hp-crud-header">
    <div class="left">
      <img class="logo" src="/happy_pets/Public/img/WhatsApp Image 2025-09-16 at 7.23.51 PM.jpeg" alt="Happy Pets Logo">
      <div class="brand">Panel de Administración</div>
    </div>
    <div class="actions">
      <a href="/happy_pets/assets/index.php" class="btn-home">Volver al sitio</a>
      <a href="/happy_pets/CRUD/productos/index.php" class="btn">CRUD Productos</a>
      <a href="/happy_pets/CRUD/auth/logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
  </div>

  <!-- === CONTENIDO === -->
  <main>
    <h2 style="text-align:center; color:#e44c9a; font-size:1.8rem; margin-top:30px;">Servicios</h2>

    <div class="crud-header-actions">
      <a href="create.php" class="btn-switch"><i class="fa-solid fa-plus"></i> Nuevo Servicio</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Duración</th>
          <th>Características</th>
          <th>Creado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($servicios as $s): ?>
          <tr>
            <td><?= $s['id'] ?></td>
            <td><?= htmlspecialchars($s['nombre']) ?></td>
            <td><?= htmlspecialchars($s['descripcion']) ?></td>
            <td>$<?= number_format($s['precio'], 2) ?></td>
            <td><?= htmlspecialchars($s['duracion']) ?></td>
            <td><?= htmlspecialchars($s['caracteristicas']) ?></td>
            <td><?= htmlspecialchars($s['fecha_creacion'] ?? '') ?></td>
            <td>
              <a href="edit.php?id=<?= $s['id'] ?>" class="btn"><i class="fa-solid fa-pen"></i> Editar</a>
              <a href="delete.php?id=<?= $s['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar este servicio?')">
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
