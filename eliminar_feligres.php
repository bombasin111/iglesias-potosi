<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_iglesia'])) {
    header('Location: index.php');
    exit;
}

$id_feligres = $_GET['id'];
$query = $conexion->prepare("DELETE FROM feligresespt WHERE id = ? AND id_iglesia = ?");
$query->execute([$id_feligres, $_SESSION['id_iglesia']]);
header('Location: registro_feligreses.php');
exit;
?>