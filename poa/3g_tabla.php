<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

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
<td class="TituloTablaP" height="41" colspan="10" align="center">Gestion Registrada</td>
</tr>
<tr>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Unidad Ejecutora</strong></td>-->
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Mes Gestion</strong></td>-->
<td  bgcolor="#CCCCCC" align="center"><strong>Mes (Meta)</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Gestion</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Cantidad</strong></td>
<td bgcolor="#CCCCCC" colspan="3" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
$id_meta = decriptar($_GET['id']); 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM poa_metas_gestion WHERE id_meta = '$id_meta'";  
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
<!--<td><div align="center" ><?php //echo ($registro->codigo1); ?></div></td>-->
<!--//<td ><div align="left" ><strong><?php //echo ($registro->mes_gestion); ?></strong></div></td>-->
<td ><div align="left" ><?php echo ($registro->mes_meta); ?></div></td>
<td ><div align="left" ><?php echo ($registro->detalle); ?></div></td>
<td ><div align="center" ><?php echo formato_moneda($registro->cantidad); ?></div></td>

<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarg('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($id_meta); ?>','<?php echo encriptar($registro->mes_meta); ?>','<?php echo encriptar($registro->cantidad); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td></tr>
</tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>