<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="11" align="center">Historial</td>
</tr>
<tr>
<td rowspan="2" bgcolor="#CCCCCC" align="center"><strong>N°</strong></td>
<td colspan="2" bgcolor="#CCCCCC" align="center"><strong>Solicitud</strong></td>
<td colspan="2" bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td colspan="2" bgcolor="#CCCCCC" align="center"><strong>Dias</strong></td>
<td rowspan="2" bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td rowspan="2" colspan="2" bgcolor="#CCCCCC" align="center"><strong>Opcion</strong></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Tipo:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Desde:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Hasta:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Habiles:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Calendario:</strong></td>
</tr>	
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM rrhh_permisos WHERE cedula='".decriptar($_GET['id'])."' ORDER BY fecha DESC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Pdf"><button type="button" class="btn btn-outline-success btn-sm" onclick="permiso('<?php echo encriptar($registro->id); ?>','<?php echo ($registro->tipo); ?>');"><?php echo ($registro->tipo); ?></button></a></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->desde).' '.$registro->hora1; ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->hasta).' '.$registro->hora2; ?></div></td>
<td ><div align="center" ><?php echo ($registro->habiles); ?></div></td>
<td ><div align="center" ><?php echo ($registro->calendario); ?></div></td>
<td ><div align="center" ><?php echo estatus_rrhh($registro->estatus); ?></div></td>
<?php if ($registro->estatus==0) { ?>
<td ><div align="center" ><a data-toggle="tooltip" title="Aprobar"><button type="button" class="btn btn-outline-success btn-sm" onclick="aprobar('<?php echo ($registro->id); ?>','<?php echo ($_GET['id']); ?>');"><i class="far fa-check-circle"></i></button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->id); ?>','<?php echo ($_GET['id']); ?>');"><i class="far fa-times-circle"></i></button></a></div></td>
<?php } ?>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>