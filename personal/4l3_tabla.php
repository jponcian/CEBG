<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Experiencia en la Administración Pública u otras Experiencias Laborales</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Institución:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cargo Desempeñado:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Desde:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Hasta:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Motivo de Renuncia:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Opcion</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM rac_experiencia WHERE rac_rep='".$_GET['id']."' ORDER BY desde;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
	<td><div align="center" ><?php echo ($i); ?></div></td>
	<td ><div align="left" ><?php echo ($registro->institucion); ?></div></td>
	<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
	<td ><div align="center" ><?php echo voltea_fecha($registro->desde); ?></div></td>
	<td ><div align="center" ><?php echo voltea_fecha($registro->hasta); ?></div></td>
	<td ><div align="left" ><?php echo ($registro->motivo); ?></div></td>
	<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_experiencia('<?php echo ($registro->id); ?>','<?php echo ($_GET['id']); ?>','<?php echo encriptar($registro->parentesco); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>