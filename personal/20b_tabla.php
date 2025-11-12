<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Aguinaldos Generados</td>
</tr>
	<tr>
<td colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Resumen</button></td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td width="250" bgcolor="#CCCCCC" align="left"><strong>Nomina:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="center" ><strong>Periodo:</strong></td>
<td  bgcolor="#CCCCCC" align="right"><strong>Asignaciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Deducciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="right"></td>
<td bgcolor="#CCCCCC" align="right"></td>
</tr>
<?php
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT id_solicitud, num_nomina, tipo_pago, id, nomina, descripcion, desde, hasta, SUM(asignaciones) as asi, SUM(descuentos) as des, SUM(total) as tot FROM nomina  WHERE tipo_pago='013' AND estatus<5  GROUP BY tipo_pago, nomina, desde, hasta;"; //$periodo
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	$total += $registro->tot;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->desde).' al '.voltea_fecha($registro->hasta); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->asi); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->des); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->tot); ?></div></td>
<td ><div align="center" ><button type="button" class="btn btn-outline-info blue light-3 btn-sm" onclick="imprimir_sol('<?php echo encriptar($registro->id_solicitud); ?>', '<?php echo ($registro->tipo_pago); ?>', 0);"><i class="fas fa-print"></i></button></div></td>
<td ><div align="center" ><button type="button" id="boton<?php echo ($registro->num_nomina); ?>" class="btn btn-outline-danger blue light-3 btn-sm" onclick="eliminar_nomina('<?php echo encriptar($registro->id_solicitud); ?>', '#boton<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></div></td></tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" align="right" ><h5><strong>Total Pendiente => <?php echo formato_moneda($total); ?></strong></h5></td>
</tr> 
	<tr>
<td colspan="10" class="PieTabla">Alcaldia del Municipio Francisco de Miranda</td>
</tr>
</table>
<script language="JavaScript">
//---------------------------
function rep()
 	{
	window.open("personal/reporte/11_aguinaldos.php?estatus=0&tipo=013","_blank");
	}
</script>