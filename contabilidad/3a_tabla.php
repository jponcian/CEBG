<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
$tabla='';

switch ($filtro) {
    case 1:
        $filtrar = " (ordenes_pago.estatus >= 10 and ordenes_pago.estatus <>99) AND ordenes_pago.numero = $dato_buscar AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND contribuyente.id = ordenes_pago.id_contribuyente AND (ordenes_pago.tipo_solicitud ='ORDEN' or ordenes_pago.tipo_solicitud ='MANUAL') AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id ORDER BY ordenes_pago.fecha ASC, ordenes_pago.numero ASC";
		$_SESSION['titulo'] = "POR CONSULTA (numero: $dato_buscar)";
        break;
    case 3:
        $filtrar = " (ordenes_pago.fecha >= '$fecha1' and ordenes_pago.fecha <='$fecha2') AND (ordenes_pago.estatus >= 10 and ordenes_pago.estatus <>99) AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND contribuyente.id = ordenes_pago.id_contribuyente AND (ordenes_pago.tipo_solicitud ='ORDEN' or ordenes_pago.tipo_solicitud ='MANUAL') AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id ORDER BY ordenes_pago.fecha ASC, ordenes_pago.numero ASC";
        $_SESSION['titulo'] = "POR FECHA (desde el ".voltea_fecha($fecha1)." al ".voltea_fecha($fecha2).")";
        break;
    case 4:
        $filtrar = " (ordenes_pago.estatus >= 10 and ordenes_pago.estatus <>99) AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND contribuyente.id = ordenes_pago.id_contribuyente AND (ordenes_pago.tipo_solicitud ='ORDEN' or ordenes_pago.tipo_solicitud ='MANUAL') AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id ORDER BY ordenes_pago.fecha ASC, ordenes_pago.numero ASC";
		$_SESSION['titulo'] = "TODAS";
        break;
    case 5:
        $filtrar = " (ordenes_pago.estatus >= 10 and ordenes_pago.estatus <>99) AND (contribuyente.rif LIKE '%$dato_buscar%' or contribuyente.nombre LIKE '%$dato_buscar%') AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND contribuyente.id = ordenes_pago.id_contribuyente AND (ordenes_pago.tipo_solicitud ='ORDEN' or ordenes_pago.tipo_solicitud ='MANUAL') AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id ORDER BY ordenes_pago.fecha ASC, ordenes_pago.numero ASC";
        $_SESSION['titulo'] = "POR CONSULTA (contribuyente: $dato_buscar)";
        break;
    case 6:
        $tabla = ', ordenes_pago_retencion';
		$filtrar = " ordenes_pago.fecha='".date('Y/m/d')."' AND ordenes_pago_retencion.id_orden_descuento = ordenes_pago_descuentos.id AND (ordenes_pago.estatus >= 10 and ordenes_pago.estatus <>99) AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND contribuyente.id = ordenes_pago.id_contribuyente AND (ordenes_pago.tipo_solicitud ='ORDEN' or ordenes_pago.tipo_solicitud ='MANUAL') AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id ORDER BY ordenes_pago.fecha ASC, ordenes_pago.numero ASC";
		$_SESSION['titulo'] = "POR CONSULTA (dia actual: ".date('d/m/Y').")";
        break;
    case 10:
        $filtrar = " (ordenes_pago.estatus = 10) AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND contribuyente.id = ordenes_pago.id_contribuyente AND (ordenes_pago.tipo_solicitud ='ORDEN' or ordenes_pago.tipo_solicitud ='MANUAL') AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id ORDER BY ordenes_pago.fecha ASC, ordenes_pago.numero ASC";
        $_SESSION['titulo'] = "POR CONSULTA (pendientes de comprobante)";	
		break;
    case 15:
        $filtrar = " (ordenes_pago.estatus = 10) AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND contribuyente.id = ordenes_pago.id_contribuyente AND (ordenes_pago.tipo_solicitud ='ORDEN' or ordenes_pago.tipo_solicitud ='MANUAL') AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id ORDER BY ordenes_pago.fecha ASC, ordenes_pago.numero ASC";
        $_SESSION['titulo'] = "POR CONSULTA (con comprobante generado)";		
        break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr>
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Ordenes de Pago con Retenciones</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Rif</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Contribuyente:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Numero:</strong></td>
<!--<td bgcolor="#CCCCCC" align="left"><strong>Concepto:</strong></td>-->
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT ordenes_pago.iva, ordenes_pago.islr, ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.tipo_solicitud, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.total, ordenes_pago.estatus, contribuyente.rif, contribuyente.nombre FROM ordenes_pago , orden_solicitudes , contribuyente, ordenes_pago_descuentos $tabla WHERE $filtrar ;"; 
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo ($registro->rif); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->nombre); ?></strong></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="center" ><strong><?php echo rellena_cero($registro->numero,8); ?></strong></div></td>
<!--<td ><div align="left" ><?php //echo ($registro->descripcion); ?></div></td>-->
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><?php if ($registro->estatus>=10) { ?><button type="button" data-toggle="modal" data-target="#modal_largo" class="btn btn-info waves-effect" onclick="enviar(<?php echo ($registro->id); ?>);" >Retenciones</button><?php } ?></div></td>
<td ><a data-toggle="tooltip" title="Comprobante de Retencion"><div align="center" ><?php if ($registro->estatus>=10) { ?><button type="button" class="btn btn-outline-success waves-effect" onclick="retenciones('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button><?php } ?></div></a></td>
<td ><a data-toggle="tooltip" title="Orden de Pago"><div align="center" ><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir_orden('<?php echo encriptar($registro->id); ?>', '<?php echo ($registro->tipo_solicitud); ?>');" >Orden</button></div></a></td></tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>