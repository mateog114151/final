<?php
require_once __DIR__ . '/../auth/validar_admin.php';
require_once __DIR__ . '/../config.php';

// Consultar productos
$stmt = $conexion->query("SELECT id, nombre, descripcion, precio, precio_descuento, categoria, stock, fecha_creacion FROM productos ORDER BY id DESC");
$productos = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>CRUD Productos - Happy Pets</title>

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">


  <!-- Estilos exclusivos para la CRUD -->
  <link rel="stylesheet" href="../../Public/css/crud-styles.css">

  <!-- Íconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

  <!-- Encabezado CRUD -->
  <header>
    <div class="logo">
      <img src="../../Public/img/WhatsApp Image 2025-09-16 at 7.23.51 PM.jpeg" 
           alt="Happy Pets Logo" style="height:50px;border-radius:10px;">
    </div>
    <h1>Gestión de Productos</h1>
    <nav>
      <a href="../../assets/index.html"><i class="fa-solid fa-home"></i> Volver al inicio</a>
      <a href="../servicios/index.php"><i class="fa-solid fa-paw"></i> CRUD Servicios</a>
      <a href="../auth/logout.php" class="btn-danger"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </nav>
  </header>

  <main>
    <h2>Productos Registrados</h2>

    <!-- Botón agregar -->
    <div class="crud-header-actions">
      <a href="create.php" class="btn-switch">
        <i class="fa-solid fa-plus"></i> Nuevo Producto
      </a>
    </div>

    <!-- Tabla de productos -->
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Precio Descuento</th>
          <th>Categoría</th>
          <th>Stock</th>
          <th>Creado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($productos as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nombre']) ?></td>
            <td><?= htmlspecialchars($p['descripcion']) ?></td>
            <td>$<?= number_format($p['precio'], 2) ?></td>
            <td>$<?= number_format($p['precio_descuento'], 2) ?></td>
            <td><?= htmlspecialchars($p['categoria']) ?></td>
            <td><?= intval($p['stock']) ?></td>
            <td><?= htmlspecialchars($p['fecha_creacion'] ?? '') ?></td>
            <td>
              <a href="edit.php?id=<?= $p['id'] ?>" class="btn">
                <i class="fa-solid fa-pen"></i> Editar
              </a>
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
    © <?= date('Y') ?> Happy Pets 🐾 | Todos los derechos reservados
  </footer>

</body>
</html>
