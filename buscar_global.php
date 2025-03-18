<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está autenticado para la búsqueda global
if (!isset($_SESSION['busqueda_global'])) {
    header('Location: index.php');
    exit;
}

// Procesar la búsqueda global
$resultados = [];
if (isset($_GET['buscar'])) {
    $busqueda = '%' . $_GET['buscar'] . '%'; // Búsqueda parcial

    // Consulta SQL para buscar por nombre en todas las iglesias
    $query = $conexion->prepare("
        SELECT f.nombre, f.ci, f.fecha_nacimiento, f.bautizo, f.confirmacion, f.matrimonio, i.nombre AS iglesia
        FROM feligreses f
        JOIN iglesias i ON f.id_iglesia = i.id
        WHERE f.nombre ILIKE ?
    ");
    $query->execute([$busqueda]); // Solo buscamos por nombre
    $resultados = $query->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Búsqueda Global de Feligreses</title>
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
    <a href="logout.php" class="btn btn-danger logout-btn">Cerrar Sesión</a>
    <div class="container mt-5">
        <!-- Sección 1: Buscar por nombre -->
        <div class="card mb-5 shadow">
            <div class="card-body">
                <h2 class="h4 mb-3 text-center">Búsqueda Global de Feligreses</h2>
                <form method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6 mx-auto">
                            <div class="input-group">
                                <input type="text" name="buscar" class="form-control" placeholder="Nombre del feligrés">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </div>
                </form>

                <?php if (isset($resultados)): ?>
                    <?php if (count($resultados) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered custom-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="col-4 text-center">Nombre Completo</th>
                                        <th class="text-center">Fecha de Nacimiento</th>
                                        <th class="col-1 text-center">Bautizo</th>
                                        <th class="col-1 text-center">Confirmación</th>
                                        <th class="col-1 text-center">Matrimonio</th>
                                        <th class="text-center">Parroquia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($resultados as $f): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($f['nombre']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars(date('d/m/Y', strtotime($f['fecha_nacimiento']))) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($f['bautizo']) ? '<span style="color: #2ecc71; opacity: 0.5;">✅</span>' : '<span style="color: #e74c3c; opacity: 0.5;">❌</span>' ?></td>
                                            <td class="text-center"><?= htmlspecialchars($f['confirmacion']) ? '<span style="color: #2ecc71; opacity: 0.5;">✅</span>' : '<span style="color: #e74c3c; opacity: 0.5;">❌</span>' ?></td>
                                            <td class="text-center"><?= htmlspecialchars($f['matrimonio']) ? '<span style="color: #2ecc71; opacity: 0.5;">✅</span>' : '<span style="color: #e74c3c; opacity: 0.5;">❌</span>' ?></td>
                                            <td class="text-center"><?= htmlspecialchars($f['iglesia']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center">No se encontraron resultados.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>