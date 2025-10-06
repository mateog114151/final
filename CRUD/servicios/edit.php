<?php
include('../config.php');
$id = $_GET['id'];

$sql = "SELECT * FROM servicios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$servicio = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Servicio</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 40px; }
        form { background: white; width: 400px; margin: auto; padding: 20px; border-radius: 10px; }
        label { display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; }
        button { background: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; margin-top: 15px; }
        a { text-decoration: none; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Editar Servicio</h2>

<form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?= $servicio['id'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($servicio['nombre']) ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion" rows="3"><?= htmlspecialchars($servicio['descripcion']) ?></textarea>

    <label>Precio:</label>
    <input type="number" name="precio" step="0.01" value="<?= $servicio['precio'] ?>" required>

    <button type="submit">Actualizar</button>
    <a href="index.php">Volver</a>
</form>

</body>
</html>
