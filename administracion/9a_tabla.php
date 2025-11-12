<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);

switch ($filtro) {
    case 1:
		$titulo = "";
        $filtrar = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.num_comprobante = '$dato_buscar' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.num_comprobante DESC";
        $filtrar2 = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.num_comprobante = '$dato_buscar' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.banco, ordenes_pago.num_comprobante DESC";
        break;
    case 2:
        $filtrar = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.descripcion LIKE '%$dato_buscar%' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.num_comprobante DESC";
        $filtrar2 = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.descripcion LIKE '%$dato_buscar%' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.banco, ordenes_pago.num_comprobante DESC";
        break;
    case 3:
		$titulo = "Comprobantes desde el ".$_GET['fecha1']." al ".$_GET['fecha2']."";
        $filtrar = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.num_comprobante";
        $filtrar2 = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.banco, ordenes_pago.num_comprobante";
        break;
    case 4:
       	$titulo = "Todos los Comprobantes";
		$filtrar = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) GROUP BY ordenes_pago.id ORDER BY ordenes_pago.num_comprobante";
		$filtrar2 = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) GROUP BY ordenes_pago.id ORDER BY ordenes_pago.banco, ordenes_pago.num_comprobante";
        break;
    case 5:
        $filtrar = " AND (contribuyente.rif LIKE '%$dato_buscar%' or contribuyente.nombre LIKE '%$dato_buscar%') GROUP BY ordenes_pago.id ORDER BY ordenes_pago.num_comprobante ";
        $filtrar2 = " AND (contribuyente.rif LIKE '%$dato_buscar%' or contribuyente.nombre LIKE '%$dato_buscar%') GROUP BY ordenes_pago.id ORDER BY ordenes_pago.banco, ordenes_pago.num_comprobante ";
        break;
    case 6:
        $titulo = "Comprobantes del dia ".date('d/m/Y')."";
		$filtrar = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.fecha = '".date('Y/m/d')."' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.num_comprobante";
		$filtrar2 = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.fecha = '".date('Y/m/d')."' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.banco, ordenes_pago.num_comprobante";
        break;
    case 7:
        $titulo = "Comprobantes Anulados";
		$filtrar = " AND (ordenes_pago.estatus=99) GROUP BY ordenes_pago.id ORDER BY ordenes_pago.num_comprobante";
		$filtrar2 = " AND (ordenes_pago.estatus=99) GROUP BY ordenes_pago.id ORDER BY ordenes_pago.banco, ordenes_pago.num_comprobante";
        break;
    case 8:
        $titulo = "Pagos desde el ".$_GET['fecha1']." al ".$_GET['fecha2']."";
		$filtrar = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.fecha_pago >= '$fecha1' AND ordenes_pago.fecha_pago <= '$fecha2' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.fecha_pago";
		$filtrar2 = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.fecha_pago >= '$fecha1' AND ordenes_pago.fecha_pago <= '$fecha2' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.banco, ordenes_pago.fecha_pago";
        break;
    case 9:
        $filtrar = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.num_pago LIKE '%$dato_buscar%' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.num_pago DESC";
        $filtrar2 = " AND ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=10) AND ordenes_pago.num_pago LIKE '%$dato_buscar%' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.banco DESC";
        break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Comprobante de Pago en Sistema</td>
</tr>
<tr>
<td colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" align="center"><strong>N</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Rif</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Contribuyente</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Comprobante</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Referencia</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha Pago</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total</strong></td>
<td bgcolor="#CCCCCC" colspan="2"></td>
</tr>
<?php 	
$total = 0;
$_SESSION['titulo'] = $titulo;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT ordenes_pago.num_comprobante, ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.tipo_solicitud, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.total, ordenes_pago.estatus, contribuyente.rif, contribuyente.nombre, ordenes_pago.fecha_pago, ordenes_pago.banco FROM ordenes_pago , contribuyente WHERE contribuyente.id = ordenes_pago.id_contribuyente";
//echo $consultx;
$_SESSION['consulta2'] = $consultx.$filtrar2;
$tablx = $_SESSION['conexionsql']->query($consultx.$filtrar);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	$total += $registro->total;
	if ($registro->descripcion=='A.P.A.R.T.A.D.A')	{$descripcion=$registro->descripcion;}	
		elseif ($registro->estatus==99)	{$descripcion='A.N.U.L.A.D.A';}	
			else	{$descripcion=$registro->descripcion;}
	?>
<tr >
<td><div align="center" ><strong><?php echo ($i); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo ($registro->rif); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo ($registro->nombre); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo rellena_cero($registro->num_comprobante,8); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo rellena_cero($registro->num_pago,8); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha_pago); ?></strong></div></td>	
<td ><div align="right" ><strong><?php echo formato_moneda($registro->total); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Orden"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->id); ?>','<?php echo ($registro->tipo_solicitud); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Comprobante de Pago"><button type="button" class="btn btn-outline-success waves-effect" onclick="imprimir2('<?php echo encriptar($registro->id); ?>','<?php echo ($registro->tipo_solicitud); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->tipo_pago==1) { ?><a data-toggle="tooltip" title="Cheque"><button type="button" class="btn btn-outline-success waves-effect" onclick="imprimir2('<?php echo encriptar($registro->id); ?>', 'CHEQUE');" ><i class="fas fa-money-check-alt mr-1"></i></button></a><?php } ?></div></td>
</tr>
	<tr><td colspan="10" ><div align="left" ><?php echo ($descripcion); ?></div></td></tr>
 <?php 
 }
 ?>
<tr><td bgcolor="#CCCCCC" colspan="10" ><div align="right" ><strong>TOTAL PAGADO BS.: <?php echo formato_moneda($total); ?></strong></div></td></tr>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>