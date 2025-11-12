<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=89;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada table" border="1" align="center" width="100%">
<!--
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Ejecuci&oacute;n Presupuestaria <?php echo date('Y'); ?></td>
</tr>
-->
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Codigo</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Descripcion</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Original</strong></td>
<td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
$anno = $_GET['anno']; 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM a_bonos WHERE cargo='Todos' ORDER BY codigo";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//list($banco,$cuenta)=explode(' ', $registro->codigo);
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->codigo); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->monto); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Agregar o Eliminar"><button data-toggle="modal" data-target="#modal_largo" data-keyboard="false" type="button" class="btn btn-outline-info btn-sm" onclick="cheques('<?php echo ($registro->codigo); ?>','<?php echo ($registro->nomina); ?>');">Excepciones</button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->codigo); ?>','<?php echo ($registro->nomina); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>