<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
if ($_GET['periodo']<>'')	{$periodo = " AND month(desde)='".mes(voltea_fecha($_GET['periodo']))."' AND year(desde)='".anno(voltea_fecha($_GET['periodo']))."'";} else {$periodo = "";}
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Nominas Generadas</td>
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
$consultx = "SELECT numero as num_nomina, tipo_pago, id, nomina, descripcion, desde, hasta, (asignaciones) as asi, (descuentos) as des, (total) as tot FROM nomina_solicitudes  WHERE tipo_pago IN ('001', '002', '003', '004', '006', '010') AND estatus<5 $periodo GROUP BY tipo_pago, nomina, desde, hasta ORDER BY nomina, tipo_pago, descripcion;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->desde).' al '.voltea_fecha($registro->hasta); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->asi); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->des); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->tot); ?></div></td>
<td ><div align="center" ><button type="button" class="btn btn-outline-info blue light-3 btn-sm" onclick="imprimir_sol('<?php echo encriptar($registro->id); ?>', '<?php echo ($registro->tipo_pago); ?>');"><i class="fas fa-print"></i></button></div></td>
<td ><div align="center" ><button type="button" id="boton<?php echo ($registro->id); ?>" class="btn btn-outline-danger blue light-3 btn-sm" onclick="eliminar_nomina('<?php echo encriptar($registro->id); ?>', '#boton<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>