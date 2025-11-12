<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
//if ($_GET['periodo']<>'')	{$periodo = " WHERE desde='".voltea_fecha($_GET['periodo'])."'";} else {$periodo = "";}
$estatus = $_GET['estatus'];
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Orden de  Pago Pendiente</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center" ><strong>Periodo:</strong></td>-->
<td  bgcolor="#CCCCCC" align="right"><strong>Asignaciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Deducciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" ></td>
<td bgcolor="#CCCCCC" ></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM ((SELECT ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.tipo_solicitud, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.total, ordenes_pago.estatus, '' as nombre FROM ordenes_pago , nomina_solicitudes WHERE (ordenes_pago.estatus <= $estatus) AND (ordenes_pago.tipo_solicitud='NOMINA') AND nomina_solicitudes.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id) UNION (SELECT ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.tipo_solicitud, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.total, ordenes_pago.estatus, contribuyente.nombre FROM ordenes_pago , contribuyente WHERE (ordenes_pago.estatus = $estatus) AND (ordenes_pago.tipo_solicitud = 'NOMINA MANUAL') AND ordenes_pago.id_contribuyente = contribuyente.id GROUP BY ordenes_pago.id)) as tabla ORDER BY fecha DESC, id DESC;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo trim(($registro->descripcion).' '.($registro->nombre)); ?></div></td>
<!--</tr><tr><td bgcolor="#CCCCCC" colspan="2" align="center" valign="middle" ><strong>Totales =></strong></td>-->
<!--<td ><div align="center" ><?php //echo voltea_fecha($registro->desde). ' al ' .voltea_fecha($registro->hasta); ?></div></td>-->
<td ><div align="right" ><?php echo formato_moneda($registro->asignaciones); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->descuentos); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Informacion del Pago"><button type="button" id="boton<?php echo ($registro->id); ?>" data-toggle="modal" data-target="#modal_largo" class="btn btn-info waves-effect" onclick="enviar_datos(<?php echo ($registro->id); ?>);" >Pago</button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Orden de Pago"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir_ord('<?php echo encriptar($registro->id); ?>', '<?php echo ($registro->tipo_solicitud); ?>', 0);" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>