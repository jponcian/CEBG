<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=62;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>

<table class="formateada table" border="1" align="center" width="100%">
<thead>
<tr><td class="TituloTablaP" height="41" colspan="10" align="center">Plan Operativo Anual</d></tr>
	<tr>
		<th  bgcolor="#CCCCCC" align="center"><strong>Item</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Descripcion</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Opciones</strong></th>
	</tr>
</thead>
<tbody><?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM poa WHERE 1=1 ORDER BY anno DESC";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//list($banco,$cuenta)=explode(' ', $registro->codigo);
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" >Plan Operativo Anual <strong><?php echo ($registro->anno); ?></strong></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="center" ><?php echo $_SESSION['estatus_poa'][($registro->estatus)]; ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Agregar o Eliminar"><button data-toggle="modal" data-target="#modal_largo" data-keyboard="false" type="button" class="btn btn-outline-info btn-sm" onclick="cheques('<?php echo ($registro->id); ?>','<?php echo ($registro->anno); ?>');">Proyectos</button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td></tr>
 <?php 
 }
 ?>
 </tbody>  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
 
</table>
<!--<script language="JavaScript" src="funciones/datatable.js"></script>-->