<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Nominas con Solicitud de Pago Completadas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="center" ><strong>Periodo:</strong></td>
<td  bgcolor="#CCCCCC" align="right"><strong>Asignaciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Deducciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td colspan="2" bgcolor="#CCCCCC" align="right"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.tipo_solicitud, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.total, ordenes_pago.estatus, nomina_solicitudes.desde, nomina_solicitudes.hasta FROM ordenes_pago , nomina_solicitudes WHERE nomina_solicitudes.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id ORDER BY ordenes_pago.id DESC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->desde). ' al ' .voltea_fecha($registro->hasta); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->asignaciones); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->descuentos); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><a href="personal/5b_generar_txt_vzla.php?id=<?php echo encriptar($registro->id); ?>" target="_blank" title="Archivo Txt Banco de Venezuela"><button type="button" class="btn btn-outline-danger waves-effect"><i class="fa-solid fa-v"></i></button></a></div></td>
<td ><div align="center" ><a href="personal/5b_generar_txt_mer.php?id=<?php echo encriptar($registro->id); ?>" target="_blank" title="Archivo Txt Banco Mercantil"><button type="button" class="btn btn-outline-primary waves-effect"><i class="fa-solid fa-m"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Alcaldia del Municipio Francisco de Miranda</td>
</tr>
</table>