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
        $filtrar = " (ordenes_pago.estatus >= 0 and ordenes_pago.estatus <>99) AND ordenes_pago.numero = $dato_buscar AND";
		$_SESSION['titulo'] = "POR CONSULTA (numero: $dato_buscar)";
        break;
    case 3:
		$filtrar = " (ordenes_pago.estatus >= 0 and ordenes_pago.estatus <>99) AND (ordenes_pago.fecha >= '$fecha1' and ordenes_pago.fecha <='$fecha2') AND";
        $_SESSION['titulo'] = "POR FECHA (desde el ".voltea_fecha($fecha1)." al ".voltea_fecha($fecha2).")";
        break;
    case 4:
        $filtrar = " (ordenes_pago.estatus >= 0 and ordenes_pago.estatus <>99) AND";
		$_SESSION['titulo'] = "TODAS";
        break;
    case 5:
        $filtrar = " (ordenes_pago.estatus >= 0 and ordenes_pago.estatus <>99) AND ordenes_pago.descripcion LIKE '%$dato_buscar%' AND";
        $_SESSION['titulo'] = "POR CONSULTA (descripcion: $dato_buscar)";
        break;
    case 6:
        $filtrar = " (ordenes_pago.estatus >= 0 and ordenes_pago.estatus <>99) AND ordenes_pago.fecha = '".date('Y/m/d')."' AND";
		$_SESSION['titulo'] = "POR CONSULTA (dia actual: ".date('d/m/Y').")";
        break;
    case 10:
        $filtrar = " ((ordenes_pago.estatus = 0 or ordenes_pago.estatus = 9) and ordenes_pago.estatus <>99) AND";
        $_SESSION['titulo'] = "POR CONSULTA (pendientes de comprobante)";	
		break;
    case 15:
        $filtrar = " (ordenes_pago.estatus = 10 and ordenes_pago.estatus <>99) AND";
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
<td  bgcolor="#CCCCCC" align="center"><strong>Orden:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center" ><strong>Periodo:</strong></td>-->
<td  bgcolor="#CCCCCC" align="right"><strong>Asignaciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Deducciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td colspan="4" bgcolor="#CCCCCC" ></td>
</tr>
<?php 	
$consultx = "SELECT * FROM ((SELECT ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.tipo_solicitud, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.total, ordenes_pago.estatus, '' as nombre FROM ordenes_pago , nomina_solicitudes WHERE $filtrar (ordenes_pago.tipo_solicitud='NOMINA') AND nomina_solicitudes.id_orden_pago = ordenes_pago.id GROUP BY ordenes_pago.id) UNION (SELECT ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.tipo_solicitud, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.total, ordenes_pago.estatus, contribuyente.nombre FROM ordenes_pago , contribuyente WHERE $filtrar (ordenes_pago.tipo_solicitud = 'NOMINA MANUAL') AND ordenes_pago.id_contribuyente = contribuyente.id GROUP BY ordenes_pago.id)) as tabla ORDER BY fecha DESC, id DESC;"; 
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<!--<td><div align="center" ><?php //echo ($i); ?></div></td>-->
<td ><div align="left" ><?php echo ($registro->numero); ?></div></td>
<td ><div align="left" ><?php echo substr($registro->descripcion,10); ?></div></td>
<!--<td ><div align="center" ><?php //echo voltea_fecha($registro->desde). ' al ' .voltea_fecha($registro->hasta); ?></div></td>-->
<td ><div align="right" ><?php echo formato_moneda($registro->asignaciones); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->descuentos); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Informacion del Pago"><button type="button" id="boton<?php echo ($registro->id); ?>" data-toggle="modal" data-target="#modal_largo" class="btn btn-info waves-effect" onclick="enviar_datos('<?php echo ($registro->id); ?>','<?php echo ($registro->tipo_solicitud); ?>');" >Pago</button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Orden de Pago"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir_ord('<?php echo encriptar($registro->id); ?>', '<?php echo ($registro->tipo_solicitud); ?>', 0);" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Comprobante de Pago"><button type="button" class="btn btn-outline-success waves-effect" onclick="imprimir('<?php echo encriptar($registro->id); ?>', '<?php echo ($registro->tipo_solicitud); ?>', 0);" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->tipo_pago==1) { ?><a data-toggle="tooltip" title="Cheque"><button type="button" class="btn btn-outline-success waves-effect" onclick="imprimir_orden('<?php echo encriptar($registro->id); ?>', 'CHEQUE');" ><i class="fas fa-money-check-alt mr-1"></i></button></a><?php } ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>