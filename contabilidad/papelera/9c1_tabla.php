<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }
$valor = $_GET[valor];
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="11" align="center">Ordenes de Pago en Sistema</td>
</tr>
<tr>
<td bgcolor="#CCCCCC" align="center"><strong>N</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong># Orden Pago</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Rif</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Contribuyente</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Referencia</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>OP</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>CP</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong></strong></td>
</tr><?php 	
////------ MONTAJE DE LOS DATOS
$consultx = "SELECT ordenes_pago.*, ordenes_pago.id as idop, contribuyente.* FROM ordenes_pago, contribuyente WHERE ordenes_pago.id_contribuyente = contribuyente.id AND (num_pago  LIKE '%$valor%' or numero  LIKE '%$valor%' or nombre  LIKE '%$valor%' or total  LIKE '%$valor%') ORDER BY num_pago, LEFT(rif,1), total DESC;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0){} else {echo '<tr><td colspan="10" height="35" align="center" ><strong>No hay Resultados...</strong></td></tr>';}
while ($registro = $tablx->fetch_object())
	{
	$i++;
	$total += $registro->total;
	if ($registro->estatus<99 and $registro->estatus>=10) 
		{	$fecha_pago=voltea_fecha($registro->fecha_pago); 
		$num_pago=rellena_cero($registro->num_pago,8); 	}
	else
		{	$fecha_pago= ''; 
		$num_pago= ''; 	}
	if ($registro->estatus==99) 
		{ $num_pago= 'A.N.U.L.A.D.A'; }
	elseif ($registro->estatus<10) 
		{ $num_pago= 'SIN PAGO'; }
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><strong><?php echo rellena_cero($registro->numero,8); ?></strong></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><?php echo ($registro->rif); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="right" ><strong><?php echo $num_pago; ?></strong></div></td>
<td ><div align="center" ><?php echo $fecha_pago; ?></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->total); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Orden de Pago"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->idop); ?>','<?php echo ($registro->tipo_solicitud); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->estatus>=10 and $registro->estatus<99) { ?><a data-toggle="tooltip" title="Ver Comprobante de Pago"><button type="button" class="btn btn-outline-success waves-effect" onclick="imprimir2('<?php echo encriptar($registro->idop); ?>','<?php echo ($registro->tipo_solicitud); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a><?php } ?></div></td>
<td ><div align="center" ><?php if ($orden==$registro->idop) {echo '<div class="badge badge-success">Asignada</div>';} else {?><a data-toggle="tooltip" title="Asignar Orden de Pago"><button type="button" class="btn btn-outline-warning waves-effect" onclick="asignar_op('<?php echo encriptar($registro->idop); ?>');" >Asignar</button></a><?php } ?></div></td>
</tr>
 <?php 
 }
?>
<tr>
<td colspan="11" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>