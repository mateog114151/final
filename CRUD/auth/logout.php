<?php
session_start();
$_SESSION = [];
session_destroy();
// Volver a la página pública principal
header("Location: ../../assets/index.php");
exit;
