<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
    header("Location: ../validacion.php?opcion=val");
    exit();
}

$acceso = 15;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------

// Filtros
$filtrar = "";
// Nómina puede venir como CSV para selección múltiple
if (isset($_GET['nomina']) && $_GET['nomina'] !== "") {
    $nominaParam = $_GET['nomina'];
    if (strpos($nominaParam, ',') !== false) {
        $partes = array_filter(array_map('trim', explode(',', $nominaParam)));
        $lista = [];
        foreach ($partes as $p) {
            $lista[] = "'" . $_SESSION['conexionsql']->real_escape_string($p) . "'";
        }
        if (count($lista) > 0) {
            $filtrar .= " AND nomina IN (" . implode(',', $lista) . ")";
        }
    } else {
        $nomina = $_SESSION['conexionsql']->real_escape_string($nominaParam);
        $filtrar .= " AND nomina='" . $nomina . "'";
    }
}
if (isset($_GET['sexo']) && $_GET['sexo'] != "") {
    $filtrar .= " AND sexo='" . $_SESSION['conexionsql']->real_escape_string($_GET['sexo']) . "'";
}

$consultx = "SELECT * FROM rac WHERE temporal=0 $filtrar;";
$_SESSION['consulta'] = "SELECT * FROM rac WHERE nomina<>'EGRESADOS' AND nomina<>'PENSIONADO' AND nomina<>'JUBILADOS' AND nomina<>'COMISION' AND temporal=0 $filtrar;";
$tablx = $_SESSION['conexionsql']->query($consultx);
?>

<input placeholder="Escriba aqui la informacion a buscar..." name="obuscar" id="obuscar" type="text" size="100" class="form-control" /><br>
<!-- Tabla optimizada para DataTables -->
<table class="datatabla formateada" align="center" width="100%">
    <thead>
        <tr>
            <th>Item</th>
            <th>Cedula</th>
            <th>Empleado</th>
            <th>Nomina</th>
            <th>Ubicacion</th>
            <th>Cargo</th>
            <th>Fecha Ingreso</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //------ MONTAJE DE LOS DATOS
        $i = 0;
        while ($registro = $tablx->fetch_object()) {
            $i++;
        ?>
            <tr id="fila<?php echo $registro->rac; ?>">
                <td><?php echo ($i); ?></td>
                <td><?php echo ($registro->cedula); ?></td>
                <td><?php echo ($registro->nombre . " " . $registro->nombre2 . " " . $registro->apellido . " " . $registro->apellido2); ?></td>
                <td><?php echo ($registro->nomina); ?></td>
                <td><?php echo ($registro->ubicacion); ?></td>
                <td><?php echo ($registro->cargo); ?></td>
                <td><?php echo voltea_fecha($registro->fecha_ingreso); ?></td>
                <td><?php echo ($registro->telefono); ?></td>
                <td><?php echo ($registro->correo); ?></td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#modal_largo" onclick="basicos(<?php echo ($registro->rac); ?>);" title="Datos Basicos"><i class="fas fa-user-edit"></i></button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#modal_largo" onclick="laboral(<?php echo ($registro->rac); ?>);" title="Datos Laborales"><i class="fas fa-briefcase"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#modal_largo" onclick="hijos(<?php echo ($registro->rac); ?>);" title="Carga Familiar"><i class="fa-solid fa-people-roof"></i></button>
                        <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#modal_normal" onclick="foto('<?php echo encriptar($registro->cedula); ?>');" title="Foto"><i class="fa-solid fa-image-portrait"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="ficha('<?php echo encriptar($registro->rac); ?>');" title="Ficha"><i class="fa-regular fa-file-pdf fa-2xl"></i></button>
                    </div>
                </td>
            </tr>
        <?php
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