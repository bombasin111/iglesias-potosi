<?php
session_start();
include 'conexion.php';

// Verificar autenticación
if (!isset($_SESSION['id_iglesia'])) {
    header('Location: index.php');
    exit;
}

// Obtener datos del feligrés
$id_feligres = $_GET['id'];
$query = $conexion->prepare("SELECT * FROM feligresespt WHERE id = ? AND id_iglesia = ?");
$query->execute([$id_feligres, $_SESSION['id_iglesia']]);
$feligres = $query->fetch();

if (!$feligres) {
    die("Feligrés no encontrado.");
}

// Actualizar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $ci = !empty($_POST['ci']) ? $_POST['ci'] : null; // Si está vacío, se asigna null
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $bautizo = isset($_POST['bautizo']) ? 1 : 0;
    $confirmacion = isset($_POST['confirmacion']) ? 1 : 0;
    $matrimonio = isset($_POST['matrimonio']) ? 1 : 0;
    $pag = !empty($_POST['pag']) ? $_POST['pag'] : null; // Si está vacío, se asigna null

    $query = $conexion->prepare("UPDATE feligresespt SET 
        nombre = ?, ci = ?, fecha_nacimiento = ?, bautizo = ?, 
        confirmacion = ?, matrimonio = ?, pag = ?
        WHERE id = ?");
    $query->execute([
        $nombre, $ci, $fecha_nacimiento, $bautizo, 
        $confirmacion, $matrimonio, $pag, $id_feligres
    ]);

    // Redirigir para evitar reenvío del formulario
    header('Location: registro_feligreses.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Feligrés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logout-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .custom-table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .icono-activo {
            color: #28a745 !important;
            opacity: 1 !important;
        }
        .icono-inactivo {
            color: #dc3545 !important;
            opacity: 0.5 !important;
        }
    </style>
</head>
<body class="bg-light">
    <a href="registro_feligreses.php" class="btn btn-success logout-btn">Volver</a>
    <div class="container mt-5">
        <div class="card shadow mb-5">
            <div class="card-body">
                <h1 class="text-center mb-4">Editar Feligrés</h1>
                <form method="POST">
                    <!-- Sección Nombre -->
                    <div class="form-group mb-3">
                        
                    </div>

                    <!-- Datos Principales -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <center><label class="form-label text-center">Nombre Completo</label></center>
                                <input type="text" 
                                    name="nombre" 
                                    class="form-control"
                                    value="<?= htmlspecialchars($feligres['nombre']) ?>" 
                                    required>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <center><label class="form-label text-center">CI</label></center>
                                <input type="number"
                                       name="ci" 
                                       class="form-control"
                                       value="<?= htmlspecialchars($feligres['ci'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <center><label class="form-label text-center">Fecha de Nacimiento:</label></center>
                                <input type="date" 
                                       name="fecha_nacimiento" 
                                       class="form-control"
                                       value="<?= htmlspecialchars($feligres['fecha_nacimiento']) ?>"
                                       required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <center><label class="form-label">Libro-Pág:</label></center>
                                <input type="text" 
                                       name="pag" 
                                       class="form-control"
                                       value="<?= htmlspecialchars($feligres['pag']) ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Sacramentos -->
                    <div class="form-group mb-4">
                        <center><label class="form-label">Sacramentos:</label></center>
                        <div class="gap-4 d-md-flex justify-content-md-center"> <!-- Mejor disposición horizontal -->
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="bautizo" 
                                       id="bautizo"
                                       <?= htmlspecialchars($feligres['bautizo']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bautizo">Bautizo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="confirmacion" 
                                       id="confirmacion"
                                       <?= htmlspecialchars($feligres['confirmacion']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="confirmacion">Confirmación</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="matrimonio" 
                                       id="matrimonio"
                                       <?= htmlspecialchars($feligres['matrimonio']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="matrimonio">Matrimonio</label>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de Submit -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <button type="submit" class="btn btn-success px-5">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>