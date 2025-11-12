<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
    header("Location: ../validacion.php?opcion=val");
    exit();
}

$acceso = 15;
include_once "../validacion_usuario.php";
function calcular_edad($fecha_nac)
{
    $fecha = new DateTime($fecha_nac);
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha)->y;
    return $edad;
}
// Filtros
$nomina_filtrada = isset($_GET['nomina']) ? $_GET['nomina'] : '';
$filtro_nomina = $nomina_filtrada ? " AND rac.nomina='" . addslashes($nomina_filtrada) . "'" : '';

$parentesco_filtrado = isset($_GET['parentesco']) ? $_GET['parentesco'] : '';
$filtro_parentesco = $parentesco_filtrado ? " AND rac_carga.parentesco='" . addslashes($parentesco_filtrado) . "'" : '';

$sexo_filtrado = isset($_GET['sexo']) ? $_GET['sexo'] : '';
$filtro_sexo = $sexo_filtrado ? " AND rac_carga.sexo='" . addslashes($sexo_filtrado) . "'" : '';

$edad_min = isset($_GET['edad_min']) ? intval($_GET['edad_min']) : null;
$edad_max = isset($_GET['edad_max']) ? intval($_GET['edad_max']) : null;

// Consulta principal
$consultaH = "SELECT rac.cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, rac_carga.cedula as cih, rac_carga.nombres, rac_carga.fecha_nac, rac_carga.sexo, rac_carga.parentesco, rac.nomina FROM rac INNER JOIN rac_carga ON rac.rac = rac_carga.rac_rep WHERE rac.nomina<>'EGRESADOS' AND rac.temporal=0 $filtro_nomina $filtro_parentesco $filtro_sexo;";
$tablx = $_SESSION['conexionsql']->query($consultaH);
$_SESSION['consultaH'] = $consultaH;
?>

<!-- Tabla optimizada para DataTables -->
<table class="datatabla formateada" align="center" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nómina</th>
            <th>Cédula</th>
            <th>Empleado</th>
            <th>Cédula Carga</th>
            <th>Nombre Carga</th>
            <th>Fecha Nac.</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>Parentesco</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        while ($registro = $tablx->fetch_object()) {
            $edad = calcular_edad($registro->fecha_nac);
            if ((isset($_GET['edad_min']) && $_GET['edad_min'] !== '' && $edad < $edad_min) || (isset($_GET['edad_max']) && $_GET['edad_max'] !== '' && $edad > $edad_max)) {
                continue;
            }
        ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo $registro->nomina; ?></td>
                <td><?php echo formato_cedula($registro->cedula); ?></td>
                <td><?php echo $registro->nombre; ?></td>
                <td><?php echo formato_cedula(abs($registro->cih)); ?></td>
                <td><?php echo ($registro->nombres); ?></td>
                <td><?php echo voltea_fecha($registro->fecha_nac) ?></td>
                <td style="text-align:right;"><?php echo $edad; ?></td>
                <td><?php echo $registro->sexo; ?></td>
                <td><?php echo $registro->parentesco; ?></td>
            </tr>
        <?php
            $i++;
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="10" class="PieTabla text-center">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
        </tr>
    </tfoot>
</table>
<script language="JavaScript" src="funciones/datatable.js"></script>