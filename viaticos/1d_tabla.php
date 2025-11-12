<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$id =$_GET['id'];
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Item(s) Registrado(s)</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Tipo:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Monto Unitario:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cantidad:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></div></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT
	viaticos_solicitudes_detalle.id, viaticos_solicitudes_detalle.id_solicitud, 
	viaticos_solicitudes_detalle.precio_u, 
	viaticos_solicitudes_detalle.cantidad, 
	viaticos_solicitudes_detalle.total, 
	a_item_viaticos.tipo
FROM
	viaticos_solicitudes_detalle
	INNER JOIN
	a_item_viaticos
	ON 
		viaticos_solicitudes_detalle.id_tipo = a_item_viaticos.id WHERE viaticos_solicitudes_detalle.id_solicitud = '$id' ORDER BY id_tipo ASC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->tipo); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->precio_u,2); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->cantidad,2); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total,2); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_detalle('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($registro->id_solicitud); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>