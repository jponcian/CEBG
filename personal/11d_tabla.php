<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Nomina:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Empleado:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"><div align="center" ><a data-toggle="tooltip" title="Eliminar Todo"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_to();"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT nomina.id, nomina.cedula, rac.nomina, nomina.partida, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, nomina.total FROM nomina , rac WHERE nomina.tipo_pago = '008' AND nomina.estatus = 0 AND nomina.cedula = rac.cedula ORDER BY rac.nomina, nomina.cedula ASC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	$total = $total + $registro->total;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cedula); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
<tr >
<td bgcolor="#CCCCCC"  colspan="7" ><div align="right" ><strong>Total => <?php echo formato_moneda($total); ?></strong></div></td>
</tr>
</table>