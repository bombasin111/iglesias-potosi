<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_iglesia = $_POST['iglesia'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena']; // <span style="color: red;">Cambiado de $contraseña a $contrasena</span>

    // Validar credenciales
    $query = $conexion->prepare("SELECT * FROM iglesiaspt WHERE id = ? AND usuario = ?");
    $query->execute([$id_iglesia, $usuario]);
    $iglesia = $query->fetch();

    if ($iglesia && $contrasena === $iglesia['contrasena']) { // <span style="color: red;">Comparación directa en texto plano</span>
        // Autenticación exitosa
        $_SESSION['id_iglesia'] = $iglesia['id'];
        header('Location: registro_feligreses.php');
        exit;
    } else {
        // Credenciales incorrectas, redirigir a index.php
        header('Location: index.php?error=1'); // <span style="color: red;">Redirigir con un parámetro de error</span>
        exit;
    }
}
?>