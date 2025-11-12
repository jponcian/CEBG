<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=107;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Empleados </td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Empleado:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Nomina:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Ubicacion:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cargo:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha Ingreso:</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Vacaciones Pendientes:</strong></td>-->
<td colspan="3" bgcolor="#CCCCCC" align="center"><strong>Opciones:</strong></td>
</tr>
<?php 	
$direccion = $_SESSION["direccion"];
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];

switch ($filtro) {
    case 1:
        $filtrar = " AND (cedula LIKE '%$dato_buscar%' or nombre LIKE '%$dato_buscar%' or nombre2 LIKE '%$dato_buscar%' or apellido LIKE '%$dato_buscar%' or apellido2 LIKE '%$dato_buscar%') ";
        break;		
    case 4:
        $consultx = "SELECT rac.*, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombreA FROM rrhh_permisos, rac WHERE id_div=$direccion AND rrhh_permisos.cedula = rac.cedula AND rrhh_permisos.tipo <> 'VACACIONES' GROUP BY rac.cedula ORDER BY rrhh_permisos.fecha DESC";
        break;		
    case 6:
        $filtrar = " AND vacaciones > 0";
        break;
}
//------ MONTAJE DE LOS DATOS
if ($filtro<>4) {$consultx = "SELECT *, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombreA FROM rac WHERE id_div=$direccion $filtrar ORDER BY vacaciones, nomina, ubicacion, cedula;";}
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	if ($registro->nomina<>'005 JUBILADOS' and $registro->nomina<>'006 PENSIONADO' and $registro->nomina<>'0700 CUERPO DE BOMBEROS' and $registro->nomina<>'0800 EGRESADOS') { $constancia='si';} else { $constancia='no';}
	if ($registro->nomina<>'0700 CUERPO DE BOMBEROS' and $registro->nomina<>'0800 EGRESADOS') { $recibo='si'; $arc='si';} else { $recibo='no'; $arc='no';}
	?>
<tr id="fila<?php echo $registro->rac; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo ($registro->cedula); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombreA); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->ubicacion); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha_ingreso); ?></div></td>
<!--<td ><div align="center" ><?php //echo ($registro->vacaciones); ?></div></td>-->
<td valign="middle" align="center"><div><a href="" class="btn btn-outline-danger btn-rounded btn-sm font-weight-bold" onclick="historial('<?php echo encriptar($registro->cedula); ?>');" data-toggle="modal" data-target="#modal_largo" data-keyboard="false">Historial</a></div></td></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
<script language="JavaScript">
//----------------
function expendiente(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/9b_modal.php?id='+id);
	}
</script>