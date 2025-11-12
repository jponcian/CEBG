<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Direcciones Registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Direccion:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Jefe:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Opcion</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT cr_memos_div_destino.id, a_direcciones.direccion, cr_memos_div_destino.ci_jefe_destino, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre FROM cr_memos_div_destino INNER JOIN a_direcciones ON cr_memos_div_destino.direccion_destino = a_direcciones.id INNER JOIN rac ON cr_memos_div_destino.ci_jefe_destino = rac.cedula WHERE	cr_memos_div_destino.id_correspondencia = '".decriptar($_GET['id'])."';"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
	<td><div align="center" ><?php echo ($i); ?></div></td>
	<td ><div align="left" ><?php echo ($registro->direccion); ?></div></td>
	<td ><div align="center" ><?php echo ($registro->ci_jefe_destino); ?></div></td>
	<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
	<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_hijo('<?php echo ($registro->id); ?>','<?php echo ($_GET['id']); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>