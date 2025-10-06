<?php include('../auth/validar_sesion.php'); ?>
<?php
include('../config.php');
include('../auth/validar_sesion.php');

$sql = "SELECT * FROM productos ORDER BY id DESC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f9f9f9; }
        <div style="text-align:right; margin:10px 30px;">
    <span>👋 Bienvenido, <strong><?= $_SESSION['usuario_nombre']; ?></strong></span>
    <a href="../auth/logout.php" 
       style="margin-left:15px; background:red; color:white; padding:6px 12px; border-radius:4px; text-decoration:none;">
       Cerrar sesión
    </a>
</div>

        h1 { text-align: center; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #333; color: white; }
        a { text-decoration: none; color: white; background: #4CAF50; padding: 6px 10px; border-radius: 4px; }
        a.eliminar { background: red; }
        .crear { display: block; width: 150px; text-align: center; margin: 20px auto; background: #2196F3; }
    </style>
</head>
<body>

<h1>Gestión de Productos</h1>
<a class="crear" href="create.php">+ Agregar Producto</a>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Acciones</th>
    </tr>

    <?php while ($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?= $fila['id'] ?></td>
            <td><?= htmlspecialchars($fila['nombre']) ?></td>
            <td><?= htmlspecialchars($fila['descripcion']) ?></td>
            <td>$<?= number_format($fila['precio'], 2) ?></td>
            <td>
                <a href="edit.php?id=<?= $fila['id'] ?>">Editar</a>
                <a href="delete.php?id=<?= $fila['id'] ?>" class="eliminar" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
