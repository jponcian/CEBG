<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=88;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Cargos Registrados</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Descripcion</strong></td>
<td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM a_cargo ORDER BY cargo";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//list($banco,$cuenta)=explode(' ', $registro->codigo);
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->codigo); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
	</tr>
</table>
<br>