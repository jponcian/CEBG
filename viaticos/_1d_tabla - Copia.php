<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$id =$_GET['id'];
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Funcionario(s) Registrado(s)</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Empleado:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cargo:</strong></td>
<td bgcolor="#CCCCCC" align="center"></div></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT rac.cedula, rac.cargo, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, viaticos_solicitudes_detalle.id FROM viaticos_solicitudes_detalle , rac WHERE viaticos_solicitudes_detalle.cedula = rac.cedula AND viaticos_solicitudes_detalle.id_solicitud = '$id' ORDER BY CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre ASC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cedula); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>