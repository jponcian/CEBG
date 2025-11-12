<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=63;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Metas Registradas</td>
</tr>
<tr>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Unidad Ejecutora</strong></td>-->
<td  bgcolor="#CCCCCC" align="center"><strong>Codigo</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Meta</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Actividad</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Indicador</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Costo</strong></td>
<td bgcolor="#CCCCCC" colspan="3" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
$valor = explode("/",$_GET['unidad']);
$id_proyecto = decriptar($_GET['id']); 
$_SESSION['id_responsable'] = $valor[0];
$unidad = $valor[1];; 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT poa_proyecto_responsable.anno, poa_metas.meta_original, poa_metas.fecha_modificada, poa_metas.modificada, poa_metas.id, poa_metas.codigo, poa_metas.costo, poa_metas.fecha, poa_metas.meta, poa_metas.actividad, poa_metas.indicador, bn_dependencias.codigo as codigo1 FROM poa_metas, bn_dependencias, poa_proyecto_responsable WHERE bn_dependencias.id = poa_proyecto_responsable.id_direccion AND poa_metas.id_responsable = poa_proyecto_responsable.id AND poa_metas.modificada < '100' AND poa_proyecto_responsable.id_direccion = '$unidad' AND poa_metas.id_proyecto = '$id_proyecto' ORDER BY poa_metas.codigo, poa_metas.fecha, poa_metas.modificada";  
//echo $consultx.$_GET['unidad'];
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	if ($registro->meta_original>0)	{	$texto = ' (MODIFICADA '.voltea_fecha($registro->fecha_modificada).')';	$stylo = 'style="background-color:beige"';	} else {	$texto = '';	$stylo = '';	}
	?>
<tr <?php echo $stylo; ?> id="fila<?php echo $registro->id; ?>">
<!--<td><div align="center" ><?php //echo ($registro->codigo1); ?></div></td>-->
<td ><div align="left" ><?php echo ($registro->codigo).$texto; ?></div></td>
<td ><div align="left" ><?php echo ($registro->meta); ?></div></td>
<td ><div align="left" ><?php echo ($registro->actividad); ?></div></td>
<td ><div align="left" ><?php echo ($registro->indicador); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->costo); ?></div></td>

<td ><div align="center" ><a data-toggle="tooltip" title="Agregar o Eliminar"><button type="button" class="btn btn-outline-info btn-sm" onclick="programacion('<?php echo ($registro->id); ?>','<?php echo ($registro->anno); ?>','<?php echo ($_GET['unidad']); ?>');">Programacion</button></a></div></td>

<td ><div align="center" ><?php if ($registro->modificada<100)	{ ?><a data-toggle="tooltip" title="Editar"><button type="button" class="btn btn-outline-warning btn-sm" onclick="editar('<?php echo ($registro->id); ?>', '<?php echo $id; ?>');"><i class="fas fa-edit"></i></button></a><?php } ?></div>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar2('<?php echo ($registro->id); ?>', '<?php echo $_GET['id']; ?>');"><i class="fas fa-trash-alt"></i></button></a></div></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>