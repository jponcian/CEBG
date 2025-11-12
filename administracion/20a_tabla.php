<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
if ($_GET['tipo']=='1')	
	{$filtro = " ordenes_pago.numero='".($_GET['valor'])."' AND ";} 
else 
	{
	if ($_GET['tipo']=='2')	
		{$filtro = " ordenes_pago.descripcion like '%".($_GET['valor'])."%' AND ";} 
	else {$filtro = "";}
	}

?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Ordenes de Pago Registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Rif</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Contribuyente</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fecha:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Numero:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Concepto:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT	orden_solicitudes.tipo_orden, orden_solicitudes.id as id_solicitud, tipo_solicitud, rif, ordenes_pago.total, orden_solicitudes.estatus, ordenes_pago.fecha, ordenes_pago.numero, ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.id_contribuyente, contribuyente.nombre FROM orden_solicitudes, contribuyente, ordenes_pago WHERE $filtro ordenes_pago.id = orden_solicitudes.id_orden_pago AND tipo_solicitud = 'MANUAL' AND ordenes_pago.estatus <> 99 AND ordenes_pago.id_contribuyente = contribuyente.id GROUP BY ordenes_pago.numero, ordenes_pago.id_contribuyente ORDER BY ordenes_pago.fecha DESC, ordenes_pago.id DESC;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->rif); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><strong><?php echo estatus($registro->estatus,rellena_cero($registro->numero,8)); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->total); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Modificar Orden"><button type="button" data-toggle="modal" data-target="#modal_largo" class="btn btn-info waves-effect" onclick="modificar('<?php echo ($registro->id_solicitud); ?>','<?php echo ($registro->tipo_orden); ?>','<?php echo ($registro->estatus); ?>');" >Modificar</button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver PDF"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->id); ?>','<?php echo ($registro->tipo_solicitud); ?>', '<?php echo encriptar($registro->id_solicitud); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>