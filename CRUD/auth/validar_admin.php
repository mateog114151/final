<?php
session_start();
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../verify.php");
    exit;
}
