<?php
session_start();
if (!isset($_SESSION['VERIFICADO']) || $_SESSION['VERIFICADO'] != 'SI') {
    echo '<div class="alert alert-danger mb-0">Sesión no válida.</div>';
    exit;
}
include_once 'conexion.php';
$cn = $_SESSION['conexionsql'];
$id  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$rif = isset($_GET['rif']) ? trim($_GET['rif']) : '';
if ($id <= 0 && $rif === '') {
    echo '<div class="alert alert-warning mb-0">Parámetros insuficientes.</div>';
    exit;
}
$where = $id > 0 ? 'id=' . $id : "rif='" . $cn->real_escape_string($rif) . "'";
$sql = "SELECT id, rif, IFNULL(razon_social,'') AS razon_social, IFNULL(direccion,'') AS direccion, IFNULL(telefono,'') AS telefono, IFNULL(correo,'') AS correo, IFNULL(contacto,'') AS contacto FROM contribuyente WHERE $where LIMIT 1;";
$rs = $cn->query($sql);
if (!$rs || $rs->num_rows === 0) {
    echo '<div class="alert alert-warning mb-0">Proveedor no encontrado.</div>';
    exit;
}
$pr = $rs->fetch_assoc();
function fmt($v)
{
    return htmlspecialchars(trim($v) === '' ? '—' : $v);
}
?>
<div class="p-2">
    <h5 class="mb-3"><i class="fas fa-industry text-secondary"></i> Proveedor: <?php echo fmt($pr['rif']); ?></h5>
    <table class="table table-sm table-bordered mb-2">
        <tbody>
            <tr>
                <th style="width:160px;">Razón Social</th>
                <td><?php echo fmt($pr['razon_social']); ?></td>
            </tr>
            <tr>
                <th>Dirección</th>
                <td><?php echo fmt($pr['direccion']); ?></td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td><?php echo fmt($pr['telefono']); ?></td>
            </tr>
            <tr>
                <th>Correo</th>
                <td><?php echo fmt($pr['correo']); ?></td>
            </tr>
            <tr>
                <th>Contacto</th>
                <td><?php echo fmt($pr['contacto']); ?></td>
            </tr>
        </tbody>
    </table>
    <small class="text-muted">Consulta rápida de proveedor.</small>
</div>