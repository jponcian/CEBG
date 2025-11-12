<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=98;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table id="tablan" class="formateada" border="1" align="center" width="100%">
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
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Estatus:</strong></td>-->
<td colspan="3" bgcolor="#CCCCCC" align="center"><strong>Gestion:</strong></td>
</tr>
<?php 	
$direccion = $_GET['dir'];
$area = $_GET['area'];
$filtro = $_GET['tipo'];

if ($direccion>0)
	{	$filtrar1 = " id_div = $direccion AND ";	} else {	$filtrar1 = "";	}
if ($area>0)
	{	$filtrar2 = " id_area = $area AND ";	} else {	$filtrar2 = "";	}
//-----------------------------------
if ($_SESSION["direccion"]==10 or $_SESSION['ADMINISTRADOR']==1)
	{	}
else
	{
	$filtrar1 = " id_div = ".$_SESSION["direccion"]." AND ";
	}

switch ($filtro) {
    case 0:
        $filtrar = " odis =2 AND ";
        break;		
    case 1:
        $filtrar = " odis =3 AND ";
        break;
}
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM rac WHERE $filtrar $filtrar1 $filtrar2 evaluar_odis=1 AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO' ORDER BY (cedula+0)";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->rac; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo ($registro->cedula); ?></div></td>
<td ><div align="left" ><?php echo $registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->ubicacion); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha_ingreso); ?></div></td>
<!--<td ><div align="center" ><?php //echo //($registro->odis); ?></div></td>-->
<td valign="middle" align="center"><div><a href="" class="btn btn-outline-danger btn-rounded btn-sm font-weight-bold" onclick="asignar_odis('<?php echo encriptar($registro->cedula); ?>');" data-toggle="modal" data-target="#modal_largo" data-keyboard="false">Asignar</a><a href="" class="btn btn-outline-info btn-rounded btn-sm font-weight-bold" onclick="ver_odis('<?php echo encriptar($registro->cedula); ?>');" data-toggle="modal" data-target="#modal_largo" data-keyboard="false">Asignados</a></div></td></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
<script language="JavaScript">
//----------------
function ver_odis(cedula){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/24d_modal.php?cedula='+cedula);
	}
//----------------
function asignar_odis(cedula){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/24b_modal.php?cedula='+cedula);
	}
</script>