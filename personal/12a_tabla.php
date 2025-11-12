<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=97;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$direccion = $_GET['dir'];
$area = $_GET['area'];
$id_proyecto = $_GET['id'];

if ($id_proyecto>0)
	{	$filtrar3 = " eval_odis.id_proyecto = $id_proyecto AND ";	} else {	$filtrar3 = "";	}
if ($direccion>0)
	{	$filtrar1 = " a_direcciones.id = $direccion AND ";	} else {	$filtrar1 = "";	}
if ($area>0)
	{	$filtrar2 = " eval_odis.id_area = $area AND ";	} else {	$filtrar2 = "";	}
//-----------------------------------
if ($_SESSION["direccion"]==10 or $_SESSION['ADMINISTRADOR']==1)
	{	}
else
	{
	$filtrar1 = " a_direcciones.id = ".$_SESSION["direccion"]." AND ";
	}
?>

<table class="formateada table" border="1" align="center" width="100%">
<thead>
<tr><td class="TituloTablaP" height="41" colspan="10" align="center">Odi Registrados</d></tr>
	<tr>
		<th bgcolor="#CCCCCC" align="center"><strong>Item</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Dirección</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Area</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Descripción</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Eliminar</strong></th>
	</tr>
</thead>
<tbody><?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT eval_odis. id, a_direcciones.direccion, a_areas.area,	eval_odis.descripcion,	eval_odis.estatus FROM	a_direcciones,	a_areas,	eval_odis WHERE	$filtrar1 $filtrar2 $filtrar3 a_direcciones.id = eval_odis.id_direccion AND eval_odis.id_area = a_areas.id ORDER BY	a_direcciones.id ASC,	a_areas.id ASC";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	if ($registro->estatus==0)	{	$valor = 'checked';	}
		else
			{	$valor = ''; }
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->direccion); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->area); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" >
	
		<input onClick="activar('<?php echo $registro->id; ?>', '<?php echo ($registro->estatus); ?>');" id="txt_exento<?php echo $registro->id; ?>" name="txt_exento<?php echo $registro->id; ?>" type="checkbox" class="switch_new" value="1" <?php echo $valor; ?> />
	<label for="txt_exento<?php echo $registro->id; ?>" class="lbl_switch"></label>		

	</div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarg('<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td></tr>
 <?php 
 }
 ?>
 </tbody>  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
 
</table>
<!--<script language="JavaScript" src="funciones/datatable.js"></script>-->