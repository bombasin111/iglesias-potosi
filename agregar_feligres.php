<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $ci = $_POST['ci'] ?? null; // Opcional
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null; // Opcional
    $bautizo = isset($_POST['bautizo']) ? 1 : 0;
    $confirmacion = isset($_POST['confirmacion']) ? 1 : 0;
    $matrimonio = isset($_POST['matrimonio']) ? 1 : 0;
    $pag = $_POST['pag'] ?? null; // Opcional
    $iglesia_id = $_POST['iglesia_id'];

    $stmt = $conn->prepare("
        INSERT INTO feligresespt (nombre, ci, fecha_nacimiento, bautizo, confirmacion, matrimonio, pag, iglesia_id)
        VALUES (:nombre, :ci, :fecha_nacimiento, :bautizo, :confirmacion, :matrimonio, :pag, :iglesia_id)
    ");

    $stmt->execute([
        'nombre' => $nombre,
        'ci' => $ci,
        'fecha_nacimiento' => $fecha_nacimiento,
        'bautizo' => $bautizo,
        'confirmacion' => $confirmacion,
        'matrimonio' => $matrimonio,
        'pag' => $pag,
        'iglesia_id' => $iglesia_id
    ]);

    header("Location: iglesia.php"); // Redirige a la página de la iglesia
    exit();
}
?>