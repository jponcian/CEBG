<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
$anno = ($_GET['anno']);

switch ($filtro) {
    case 1:
        $filtrar = " AND anno = '$anno' AND numero = '$dato_buscar' GROUP BY id_solicitud, fecha, numero, id_contribuyente ORDER BY fecha, numero";
        $filtrar2 = " AND orden_solicitudes.anno = '$anno' AND orden_solicitudes.numero = '$dato_buscar' ORDER BY orden_solicitudes.fecha, orden_solicitudes.numero, categoria, partida";
        break;
    case 2:
        $filtrar = " AND anno = '$anno' AND concepto LIKE '%$dato_buscar%' GROUP BY id_solicitud, fecha, numero, id_contribuyente ORDER BY fecha, numero";
        $filtrar2 = " AND orden_solicitudes.anno = '$anno' AND orden_solicitudes.concepto LIKE '%$dato_buscar%' ORDER BY orden_solicitudes.fecha, orden_solicitudes.numero, categoria, partida";
        break;
    case 3:
        $filtrar = " AND anno = '$anno' AND fecha >= '$fecha1' AND fecha <= '$fecha2' GROUP BY id_solicitud, fecha, numero, id_contribuyente ORDER BY fecha, numero";
        $filtrar2 = " AND orden_solicitudes.anno = '$anno' AND orden_solicitudes.fecha >= '$fecha1' AND orden_solicitudes.fecha <= '$fecha2' ORDER BY orden_solicitudes.fecha, orden_solicitudes.numero, categoria, partida";
        break;
    case 4:
        $filtrar = " AND anno = '$anno' GROUP BY id_solicitud, fecha, numero, id_contribuyente ORDER BY fecha, numero";
        $filtrar2 = " AND orden_solicitudes.anno = '$anno' ORDER BY orden_solicitudes.fecha, orden_solicitudes.numero, categoria, partida";
        break;
    case 5:
        $filtrar = " AND anno = '$anno' AND (contribuyente.rif LIKE '%$dato_buscar%' or contribuyente.nombre LIKE '%$dato_buscar%') GROUP BY id_solicitud, fecha, numero, id_contribuyente ORDER BY fecha, numero";
        $filtrar2 = " AND orden_solicitudes.anno = '$anno' AND (contribuyente.rif LIKE '%$dato_buscar%' or contribuyente.nombre LIKE '%$dato_buscar%') ORDER BY orden_solicitudes.fecha, orden_solicitudes.numero, categoria, partida";
        break;
    case 6:
        $filtrar = " AND anno = '$anno' AND orden.estatus=99 GROUP BY id_solicitud, fecha, numero, id_contribuyente ORDER BY fecha, numero";
        $filtrar2 = " AND orden_solicitudes.anno = '$anno' AND orden_solicitudes.estatus=99 ORDER BY orden_solicitudes.fecha, orden_solicitudes.numero, categoria, partida";
        break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="8" align="center">Orden de Pago en Sistema</td>
</tr>
<tr>
<td colspan="4" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-primary" onClick="rep(1);"><i class="fas fa-search mr-2"></i>Pdf Resumen</button></td>
<td colspan="4" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep(2);"><i class="fas fa-search mr-2"></i>Pdf Detalle</button></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" align="center"><strong>N</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Rif</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Contribuyente</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Orden</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Concepto</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT anno, id_solicitud, tipo_orden, orden.estatus, orden.id, id_contribuyente, orden.rif, fecha, numero, concepto, sum(total) as total1, contribuyente.nombre FROM orden, contribuyente WHERE (tipo_orden='CD' OR tipo_orden='CC' OR tipo_orden='CP') AND estatus>0 AND orden.id_contribuyente = contribuyente.id $filtrar;"; 
	
$consultx1 = "SELECT anno, orden.num_orden_pago, id_solicitud, tipo_orden, orden.estatus, orden.id, id_contribuyente, orden.rif, fecha, numero, concepto, sum(total) as total1, contribuyente.nombre FROM orden, contribuyente WHERE (tipo_orden='CD' OR tipo_orden='CC' OR tipo_orden='CP') AND estatus>0 AND orden.id_contribuyente = contribuyente.id $filtrar;"; 

$consultx1d = "SELECT anno, orden_solicitudes.num_orden_pago, id_solicitud, orden.orden_solicitudes.tipo_orden, orden.estatus, orden.id, orden_solicitudes.id_contribuyente, orden.rif, orden_solicitudes.fecha, orden_solicitudes.numero, concepto, orden_solicitudes.asignaciones as total1, contribuyente.nombre, orden.categoria, orden.partida, orden.cantidad, orden.total, orden.descripcion FROM orden, contribuyente, orden_solicitudes WHERE orden_solicitudes.id=orden.id_solicitud AND (orden_solicitudes.tipo_orden='CD' OR orden_solicitudes.tipo_orden='CC' OR orden_solicitudes.tipo_orden='CP') AND orden_solicitudes.estatus>0 AND orden.id_contribuyente = contribuyente.id $filtrar2;";

//echo $consultx;
$_SESSION['consulta1'] = $consultx1;
$_SESSION['consulta1d'] = $consultx1d;
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
<td ><div align="left" ><strong><?php echo ($registro->tipo_orden).'-'.rellena_cero($registro->numero,3).'-'.($registro->anno); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->concepto); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total1); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Orden"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->id_solicitud); ?>','<?php echo ($registro->tipo_orden); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>