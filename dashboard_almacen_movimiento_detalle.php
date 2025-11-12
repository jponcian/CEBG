<?php
session_start();
include_once "conexion.php";
include_once "funciones/auxiliar_php.php";

if (!isset($_GET['id'], $_GET['tipo'])) {
    echo '<div class="alert alert-danger mb-0">Parámetros incompletos.</div>';
    exit;
}

$id = (int)$_GET['id'];
$tipo = strtoupper(trim($_GET['tipo'])) === 'INGRESO' ? 'INGRESO' : 'SALIDA';

// Consultar cabecera
if ($tipo === 'SALIDA') {
    $sqlCab = "SELECT s.id, s.numero, s.fecha, d.direccion AS area
               FROM bn_solicitudes s
               JOIN a_direcciones d ON d.id = s.division
               WHERE s.id = $id LIMIT 1";
    $sqlDet = "SELECT m.descripcion_bien AS articulo, sd.cant_aprobada AS cantidad, m.unidad
               FROM bn_solicitudes_detalle sd
               JOIN bn_materiales m ON m.id_bien = sd.id_bien
               WHERE sd.id_solicitud = $id AND sd.estatus = 10
               ORDER BY m.descripcion_bien";
} else {
    $sqlCab = "SELECT i.id, i.numero, i.fecha, d.direccion AS area
               FROM bn_ingresos i
               JOIN a_direcciones d ON d.id = i.division
               WHERE i.id = $id LIMIT 1";
    $sqlDet = "SELECT m.descripcion_bien AS articulo, di.cantidad AS cantidad, m.unidad
               FROM bn_ingresos_detalle di
               JOIN bn_materiales m ON m.id_bien = di.id_bien
               WHERE di.id_ingreso = $id AND di.estatus = 10
               ORDER BY m.descripcion_bien";
}

$cab = null;
if ($rs = $_SESSION['conexionsql']->query($sqlCab)) {
    $cab = $rs->fetch_assoc();
}
if (!$cab) {
    echo '<div class="alert alert-warning mb-0">No se encontró el movimiento.</div>';
    exit;
}

$detalles = [];
if ($rs = $_SESSION['conexionsql']->query($sqlDet)) {
    while ($row = $rs->fetch_assoc()) {
        $detalles[] = $row;
    }
}
?>
<div class="container-fluid p-2">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">
            <?php echo ($tipo === 'INGRESO' ? 'Ingreso' : 'Salida'); ?>
            Nro: <?php echo htmlspecialchars(str_pad($cab['numero'], 4, '0', STR_PAD_LEFT)); ?>
        </h5>
        <div class="text-muted">
            Fecha: <?php echo htmlspecialchars(date('d/m/Y', strtotime($cab['fecha']))); ?>
        </div>
    </div>
    <div class="mb-2">
        <strong>Área/División:</strong> <?php echo htmlspecialchars($cab['area']); ?>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Artículo</th>
                    <th class="text-right">Cantidad</th>
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($detalles)) : ?>
                    <tr>
                        <td colspan="4" class="text-center">Sin renglones.</td>
                    </tr>
                    <?php else: $i = 1;
                    foreach ($detalles as $r): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($r['articulo']); ?></td>
                            <td class="text-right"><?php echo number_format((float)$r['cantidad'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($r['unidad']); ?></td>
                        </tr>
                <?php endforeach;
                endif; ?>
            </tbody>
        </table>
    </div>
</div>