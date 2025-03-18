<?php
session_start();
include 'conexion.php';

// Redirigir si ya está autenticado
if (isset($_SESSION['id_iglesia'])) {
    header('Location: login.php');
    exit;
}

// Obtener lista de iglesias desde la base de datos
$dsn = 'pgsql:host=tdpg-cv1ok2d6l47c73fi3rn0-a.oregon-postgres.render.com;dbname=iglesias_localidad_0nd1;sslmode=require';
$usuario = 'bombasin111'; // Reemplaza con tu usuario correcto
$contrasena = 'LOGlCiLdaP9T6a5O8PN3QM6A9Er7xul3'; // Reemplaza con tu contraseña correcta

try {
    $pdo = new PDO($dsn, $usuario, $contrasena, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Habilita excepciones para errores
    ]);

    // Consulta SQL con orden personalizado
    $query = $pdo->query("
        SELECT id, nombre 
        FROM iglesiaspt 
        ORDER BY 
            CASE 
                WHEN id = 1 THEN 0 
                ELSE 1 
            END, 
            id ASC
    ");
    $iglesias = $query->fetchAll();

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

// Procesar el formulario de autenticación para la búsqueda global
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_global'])) {
    $usuario_global = $_POST['usuario_global'];
    $contrasena_global = $_POST['contrasena_global']; // <span style="color: red;">Cambiado de $contraseña_global a $contrasena_global</span>

// Obtener la contraseña en texto plano desde la base de datos
$query = $conexion->prepare("SELECT id, contrasena FROM iglesiaspt WHERE usuario = ?"); // <span style="color: red;">Cambiado de contraseña a contrasena</span>
$query->execute([$usuario_global]);
$usuario = $query->fetch();

if ($usuario) {
    // Verificar la contraseña en texto plano
    if ($contrasena_global === $usuario['contrasena']) { // <span style="color: red;">Comparación directa en texto plano</span>
        // Autenticación exitosa para búsqueda global
        $_SESSION['busqueda_global'] = true;
        header('Location: buscar_global.php');
        exit;
    } else {
        $error_global = "Usuario o contraseña incorrectos.";
        echo "Error: $error_global<br>"; // Depuración: Mostrar el error
    }
} else {
    $error_global = "Usuario o contraseña incorrectos.";
    echo "Error: $error_global<br>"; // Depuración: Mostrar el error
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenid@ - Parroquias de la Localidad</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f8ff, #ffe4e1); /* Fondo pastel celeste y rosado */
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9); /* Fondo semi-transparente */
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #6a5acd; /* Color celeste oscuro */
            text-align: center;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.9); /* Fondo semi-transparente para inputs */
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #9370db; /* Color lila pastel */
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background-color: #7b68ee; /* Color lila más oscuro al pasar el mouse */
        }
        .btn-secondary {
            background-color: #ffb6c1; /* Color rosado pastel */
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
        }
        .btn-secondary:hover {
            background-color: #ff69b4; /* Color rosado más oscuro al pasar el mouse */
        }
        .error {
            color: #ff4500; /* Color naranja para errores */
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seleccione su Parroquia</h1>
        <h2>Usuario y Contraseña</h2>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="iglesia" class="form-label">Parroquia:</label>
                <select class="form-control" name="iglesia" required>
                    <option value="">-- Elija una Parroquia --</option>
                    <?php foreach ($iglesias as $iglesia): ?>
                        <option value="<?= htmlspecialchars($iglesia['id']) ?>"><?= htmlspecialchars($iglesia['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" class="form-control" name="usuario" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña:</label> <!-- <span style="color: red;">Cambiado de contraseña a contrasena</span> -->
                <input type="password" class="form-control" name="contrasena" required> <!-- <span style="color: red;">Cambiado de contraseña a contrasena</span> -->
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Ingresar</button>
                <button type="button" onclick="mostrarBusquedaGlobal()" class="btn btn-secondary">Búsqueda Global</button>
            </div>
        </form>

        <!-- Formulario de búsqueda global (oculto inicialmente) -->
        <div id="busqueda-global" style="display: none; margin-top: 20px;">
            <h2>Búsqueda Global</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="usuario_global" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" name="usuario_global" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena_global" class="form-label">Contraseña:</label> <!-- <span style="color: red;">Cambiado de contraseña a contrasena</span> -->
                    <input type="password" class="form-control" name="contrasena_global" required> <!-- <span style="color: red;">Cambiado de contraseña a contrasena</span> -->
                </div>
                <?php if (isset($error_global)): ?>
                    <p class="error"><?= htmlspecialchars($error_global) ?></p>
                <?php endif; ?>
                <div class="text-center">
                    <button type="submit" name="buscar_global" class="btn btn-primary">Acceder a la búsqueda</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (opcional, si necesitas funcionalidades de Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function mostrarBusquedaGlobal() {
            document.getElementById('busqueda-global').style.display = 'block';
        }
    </script>
</body>
</html>