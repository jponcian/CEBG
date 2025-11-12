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
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">PROYECTOS ESTABLECIDOS</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Denominacion</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Objetivo</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Supuesto</strong></td>-->
<td bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
</tr>
<?php 	
$anno = $_GET['anno']; 
$responsable = $_GET['rep']; 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT poa_proyecto.* FROM poa_proyecto_responsable, poa_metas, poa_metas_frecuencia, poa_metas_gestion, poa_proyecto WHERE poa_proyecto_responsable.anno='$anno' AND poa_proyecto_responsable.id_direccion=$responsable AND (poa_proyecto.id= poa_proyecto_responsable.id_proyecto AND poa_proyecto_responsable.id_proyecto = poa_metas.id_proyecto AND poa_metas.id = poa_metas_frecuencia.id_meta AND poa_metas_frecuencia.id_meta = poa_metas_gestion.id_meta AND poa_proyecto_responsable.id = poa_metas.id_responsable ) GROUP BY poa_proyecto.id ORDER BY numero";//$filtrar.$_GET['valor'].";"; 
//	echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	$proyecto = $registro->id;
	?>
<tr id="fila<?php echo $registro->id; ?>">
<!--<td><div align="center" ><?php echo ($i); ?></div></td>-->
<td><div align="center" ><?php echo ($registro->numero); ?></div></td>
<td ><div align="left" ><?php echo ($registro->tipo); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="left" ><?php echo ($registro->objetivo); ?></div></td>
<!--<td ><div align="left" ><?php //echo ($registro->supuestos); ?></div></td>-->

	<td ><div align="center" ><?php echo $_SESSION['estatus_poa'][($registro->estatus)]; ?></div></td>
</tr>
 
<!--
	<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Metas Registradas</td>
</tr>
-->
<!--
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Unidad Ejecutora</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Codigo</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Meta</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Actividad</strong></td>
</tr>
-->
<?php 	
$consultxm = "SELECT poa_metas.* FROM poa_proyecto_responsable, poa_metas, poa_metas_frecuencia, poa_metas_gestion, poa_proyecto WHERE poa_metas.id_proyecto='$proyecto' AND poa_proyecto_responsable.anno='$anno' AND poa_proyecto_responsable.id_direccion=$responsable AND (poa_proyecto.id= poa_proyecto_responsable.id_proyecto AND poa_proyecto_responsable.id_proyecto = poa_metas.id_proyecto AND poa_metas.id = poa_metas_frecuencia.id_meta AND poa_metas_frecuencia.id_meta = poa_metas_gestion.id_meta AND poa_proyecto_responsable.id = poa_metas.id_responsable ) GROUP BY poa_metas.id ORDER BY poa_metas.id_proyecto, poa_metas.codigo";//$filtrar.$_GET['valor'].";"; 
//	echo $consultx;
$tablxm = $_SESSION['conexionsql']->query($consultxm);
while ($registrom = $tablxm->fetch_object())
	{
	$i++;
	$meta = $registrom->id;
	?>
<tr id="fila<?php echo $registrom->id; ?>">
<!--<td><div align="center" ><?php //echo ($registro->codigo1); ?></div></td>-->
<td ><div align="left" ><strong>META</strong></div></td>
<td ><div align="left" ><?php echo ($registrom->codigo); ?></div></td>
<td ><div align="left" ><?php echo ($registrom->meta); ?></div></td>
<td ><div align="left" ><?php echo ($registrom->actividad); ?></div></td>

</tr>
 
<!--
	<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Gestion Registrada</td>
</tr>
-->
<!--
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Unidad Ejecutora</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>TIPO</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Mes Gestion</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Mes (Meta)</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Gestion</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Cantidad</strong></td>
</tr>
-->
<?php 	
$id_meta = decriptar($_GET['id']); 
//------ MONTAJE DE LOS DATOS
$consultxg = "SELECT poa_metas_gestion.* FROM poa_proyecto_responsable, poa_metas, poa_metas_frecuencia, poa_metas_gestion, poa_proyecto WHERE poa_metas_gestion.id_meta='$meta' AND poa_proyecto_responsable.anno='$anno' AND poa_proyecto_responsable.id_direccion=$responsable AND (poa_proyecto.id= poa_proyecto_responsable.id_proyecto AND poa_proyecto_responsable.id_proyecto = poa_metas.id_proyecto AND poa_metas.id = poa_metas_frecuencia.id_meta AND poa_metas_frecuencia.id_meta = poa_metas_gestion.id_meta AND poa_proyecto_responsable.id = poa_metas.id_responsable ) GROUP BY poa_metas_gestion.id ORDER BY poa_metas.id_proyecto, poa_metas.codigo";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablxg = $_SESSION['conexionsql']->query($consultxg);
while ($registrog = $tablxg->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registrog->id; ?>">
<!--<td><div align="center" ><?php //echo ($registro->codigo1); ?></div></td>-->
<td ><div align="left" ><strong>GESTION</strong></div></td>
<td ><div align="left" ><strong><?php echo ($registrog->mes_gestion); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo ($registrog->mes_meta); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo ($registrog->detalle); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo ($registrog->cantidad); ?></strong></div></td>

</tr>
		 <?php 
		 }
		 ?>
	<?php 
	}
	?>
<?php 
}
?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>