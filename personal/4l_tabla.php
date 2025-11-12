<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Titulos Registrados</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Profesion:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Especialidad:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Colegio o Registro:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>N° Colegio o Registro:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Tomo o Folio:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha de Registro:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Opcion</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM rac_titulo WHERE rac_rep='".$_GET['id']."' ORDER BY fecha;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
	<td><div align="center" ><?php echo ($i); ?></div></td>
	<td ><div align="center" ><?php echo ($registro->profesion); ?></div></td>
	<td ><div align="center" ><?php echo ($registro->especialidad); ?></div></td>
	<td ><div align="center" ><?php echo ($registro->colegio); ?></div></td>
	<td ><div align="center" ><?php echo ($registro->numero); ?></div></td>
	<td ><div align="center" ><?php echo ($registro->tomo); ?></div></td>
	<td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
	<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_titulo('<?php echo ($registro->id); ?>','<?php echo ($_GET['id']); ?>','<?php echo encriptar($registro->parentesco); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>