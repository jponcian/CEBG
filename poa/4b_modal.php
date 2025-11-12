<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=73;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<?php 	
$id_meta = decriptar($_GET['id']); 
//------ MONTAJE DE LOS DATOS
$consultxg = "SELECT poa_metas.*, poa_proyecto.descripcion, poa_proyecto.objetivo FROM poa_proyecto_responsable, poa_metas, poa_proyecto WHERE poa_metas.id='$id_meta' AND (poa_proyecto.id= poa_proyecto_responsable.id_proyecto AND poa_proyecto_responsable.id_proyecto = poa_metas.id_proyecto AND poa_proyecto_responsable.id = poa_metas.id_responsable ) ORDER BY poa_metas.id_proyecto, poa_metas.codigo";//$filtrar.$_GET['valor'].";"; 
//echo $consultxg;
$tablxg = $_SESSION['conexionsql']->query($consultxg);
while ($registrog = $tablxg->fetch_object())
	{
	$i++;
	?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
	<td class="TituloTablaP" height="41" colspan="10" align="center">Información de la Meta</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Proyecto</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Objetivo</strong></td>
</tr>
<tr id="fila<?php echo $registrog->id; ?>">
<td ><div align="left" ><strong><?php echo ($registrog->descripcion); ?></strong></div></td>
<td ><div align="left" ><><?php echo ($registrog->objetivo); ?></></div></td>
</tr>
	</table>
	<table class="formateada" border="1" align="center" width="100%">
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Codigo Meta</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Meta</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Actividad</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Indicador</strong></td>
</tr>
<tr id="fila<?php echo $registrog->id; ?>">
<td ><div align="left" ><strong><?php echo ($registrog->codigo); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo ($registrog->meta); ?></strong></div></td>
<td ><div align="left" ><><?php echo ($registrog->actividad); ?></></div></td>
<td ><div align="left" ><><?php echo ($registrog->indicador); ?></></div></td>
</tr>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table><?php 
	}
	?>