<?php
$conexion = new mysqli("localhost", "root", "", "happy_pets");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
