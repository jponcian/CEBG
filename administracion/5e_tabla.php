<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$id = $_GET['id'];
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Porcentaje:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Sustraendo:</strong></td>
<!--<td bgcolor="#CCCCCC" align="right"><strong>Monto:</strong></td>-->
<td bgcolor="#CCCCCC" align="right"><strong>Retencion:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT sustraendo, ordenes_pago_descuentos.id, ordenes_pago_descuentos.descuento, ordenes_pago_descuentos.porcentaje, a_retenciones.decripcion FROM ordenes_pago_descuentos , a_retenciones WHERE id_orden_pago=$id AND a_retenciones.id = ordenes_pago_descuentos.id_descuento ORDER BY ordenes_pago_descuentos.id_descuento ASC";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->decripcion); ?></div></td>
<td ><div align="right" ><?php echo ($registro->porcentaje); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->sustraendo); ?></div></td>
<!--<td ><div align="right" ><?php //echo formato_moneda($registro->descuento); ?></div></td>-->
<td ><div align="right" ><?php echo formato_moneda($registro->descuento); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
</table>