<?php
session_start();
ob_end_clean();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
setlocale(LC_TIME, 'sp_ES','sp', 'es');
//--------
$info = array();
$tipo = 'info';
//-------------	

$archivo = ($_GET['tipo']);
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
//----------------------
	$consultax = "CALL actualizar_orden_pago();"; //echo $consultx ;
	$tablax = $_SESSION['conexionsql']->query($consultax);
//----------------------
if ($archivo==6)
	{ 
	//----------- BASE IMPONIBLE
	$consulta = "DROP TABLE IF EXISTS base_imponible;"; 
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$consulta = "CREATE TEMPORARY TABLE base_imponible (SELECT ordenes_pago.id AS id_op, SUM(orden.total) AS base, orden.factura, orden.control, orden.fecha_factura FROM orden_solicitudes, ordenes_pago, orden WHERE LEFT (partida, 9)<> '403180100'  AND ordenes_pago.estatus < 99 AND ordenes_pago.estatus >=10 AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden_solicitudes.id = orden.id_solicitud AND ordenes_pago.fecha >= '$fecha1'  AND ordenes_pago.fecha <= '$fecha2' GROUP BY ordenes_pago.id, orden.factura, orden.control ORDER BY ordenes_pago.numero);";
 $tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;orden.exento=0 AND 
	//----------- IVA
	$consulta = "DROP TABLE IF EXISTS lista;"; 
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$consulta = "CREATE TEMPORARY TABLE lista (SELECT ordenes_pago.fecha, ordenes_pago.id AS id_op, ordenes_pago_retencion.numero,  ordenes_pago_descuentos.codigo,  ordenes_pago_descuentos.porcentaje, contribuyente.rif, contribuyente.nombre, ordenes_pago.numero AS num_op FROM ordenes_pago, contribuyente, ordenes_pago_descuentos, ordenes_pago_retencion WHERE ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2'  AND ordenes_pago.id_contribuyente = contribuyente.id  AND ordenes_pago.estatus < 99  AND ordenes_pago.estatus >=10  AND ordenes_pago.id = ordenes_pago_descuentos.id_orden_pago AND ordenes_pago_descuentos.id = ordenes_pago_retencion.id_orden_descuento AND ordenes_pago_descuentos.id_descuento = 6 ORDER BY ordenes_pago.numero);";
	$tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
	//-----------
	$consultx = "SELECT lista.*, base_imponible.base, base_imponible.factura, base_imponible.control, base_imponible.fecha_factura FROM lista, base_imponible WHERE lista.id_op=base_imponible.id_op ORDER BY lista.numero, base_imponible.factura;";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//-------------
	$file = fopen("../retenciones_xml.xml", "w");
	$titulo = '<?xml version="1.0" encoding="ISO-8859-1"?>
<RelacionRetencionesISLR RifAgente="G200012870" Periodo="'.anno($fecha1).mes($fecha1).'">';
	fwrite($file, $titulo);
	$total = 0;
	while ($registro = $tablx->fetch_object())
		{
		//-----------------
		$anno = anno($registro->fecha);
		$mes = mes($registro->fecha);
		$fecha = voltea_fecha($registro->fecha);
		$rif = $registro->rif;
		$codigo = rellena_cero($registro->codigo,3);
		$factura = rellena_cero($registro->factura,6);
		$control = rellena_cero($registro->control,6);
		$islr =  formato_natural($registro->base);
		$porcentaje = formato_natural($registro->porcentaje);
		//-----------
		$linea = "<DetalleRetencion>
		<RifRetenido>$rif</RifRetenido>
		<NumeroFactura>$factura</NumeroFactura>
		<NumeroControl>$control</NumeroControl>
		<FechaOperacion>$fecha</FechaOperacion>
		<CodigoConcepto>$codigo</CodigoConcepto>
		<MontoOperacion>$islr</MontoOperacion>
		<PorcentajeRetencion>$porcentaje</PorcentajeRetencion>
		</DetalleRetencion>"; //echo $linea;
		fwrite($file, "\r\n");
		fwrite($file, $linea);
		}
	
	fwrite($file, "\r\n");
	$linea = "</RelacionRetencionesISLR>"; //echo $linea;
	fwrite($file, $linea);
	fclose($file);
	//-----------
	$archivo = '../retenciones_xml.xml';
	header("Content-Description: Descargar XML");
	header("Content-Disposition: attachment; filename=$anno$mes.xml");
	header("Content-Type: application/force-download");
	header("Content-Length: " . filesize($archivo));
	header("Content-Transfer-Encoding: binary");
	readfile($archivo);
	//----------- 
	}
//----------------------
if ($archivo==7)
	{
	$i=0; 
	//----------- BASE IMPONIBLE
	$consulta = "DROP TABLE IF EXISTS base_imponible;"; 
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$consulta = "CREATE TEMPORARY TABLE base_imponible (SELECT ordenes_pago.id AS id_op,	SUM( orden.total ) AS base,	orden.factura,	orden.control,	orden.fecha_factura FROM orden_solicitudes,	ordenes_pago,	orden WHERE orden.exento=0 AND	LEFT ( partida, 9 )<> '403180100' AND ordenes_pago.estatus < 99 AND ordenes_pago.estatus >=10 AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden_solicitudes.id = orden.id_solicitud AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' GROUP BY	orden.factura,	orden.control , ordenes_pago.id ORDER BY	ordenes_pago.numero);";
	$tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
	//----------- IVA
	$consulta = "DROP TABLE IF EXISTS monto_iva;"; 
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$consulta = "CREATE TEMPORARY TABLE monto_iva (SELECT porcentaje_iva, ordenes_pago.id AS id_op,	SUM( orden.total ) AS iva,	orden.factura,	orden.control,	orden.fecha_factura FROM orden_solicitudes,	ordenes_pago,	orden WHERE LEFT ( partida, 9 )= '403180100' AND ordenes_pago.estatus < 99 AND ordenes_pago.estatus >=10 AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden_solicitudes.id = orden.id_solicitud AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' GROUP BY	orden.factura,	orden.control , ordenes_pago.id ORDER BY	ordenes_pago.numero);";
	$tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
	//-----------EXENTO
	$consulta = "DROP TABLE IF EXISTS monto_exento;"; 
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$consulta = "CREATE TEMPORARY TABLE monto_exento (SELECT ordenes_pago.id AS id_op,	SUM( orden.total ) AS exento,	orden.factura,	orden.control,	orden.fecha_factura FROM orden_solicitudes,	ordenes_pago,	orden WHERE orden.exento=1  AND LEFT ( partida, 9 )<> '403180100' AND ordenes_pago.estatus < 99 AND ordenes_pago.estatus >=10 AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden_solicitudes.id = orden.id_solicitud AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' GROUP BY	orden.factura,	orden.control ORDER BY	ordenes_pago.numero);";
	$tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
	//-----------
	$consulta = "DROP TABLE IF EXISTS lista;"; 
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$consultx = "CREATE TEMPORARY TABLE lista (SELECT ordenes_pago.fecha, ordenes_pago.id AS id_op, ordenes_pago_retencion.numero, ordenes_pago_descuentos.porcentaje,	contribuyente.rif,	contribuyente.nombre,	ordenes_pago.numero AS num_op FROM	ordenes_pago,	contribuyente,	ordenes_pago_descuentos,	ordenes_pago_retencion WHERE (ordenes_pago.tipo_solicitud = 'ORDEN' or ordenes_pago.tipo_solicitud = 'MANUAL') AND  ordenes_pago.fecha >= '$fecha1'	AND ordenes_pago.fecha <= '$fecha2' AND ordenes_pago.id_contribuyente = contribuyente.id AND ordenes_pago.estatus < 99 AND ordenes_pago.estatus >=10 AND ordenes_pago.id = ordenes_pago_descuentos.id_orden_pago AND ordenes_pago_descuentos.id = ordenes_pago_retencion.id_orden_descuento AND ordenes_pago_descuentos.id_descuento = 7 ORDER BY	ordenes_pago.numero);";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//-------------
	$totaliva= 0;
	$file = fopen("../retenciones_txt.txt", "w");	
	$consultx = "SELECT lista.*, porcentaje_iva, base, iva, base_imponible.factura, base_imponible.control, base_imponible.fecha_factura FROM lista, base_imponible, monto_iva WHERE lista.id_op=base_imponible.id_op AND lista.id_op=monto_iva.id_op AND monto_iva.id_op=base_imponible.id_op AND base_imponible.factura=monto_iva.factura AND base_imponible.control=monto_iva.control ORDER BY lista.num_op, base_imponible.factura;";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	while ($registro = $tablx->fetch_object())
		{
		$consulta = "SELECT * FROM monto_exento WHERE id_op=".$registro->id_op." AND factura='".$registro->factura."' AND control='".$registro->control."';"; //echo $consulta;
		$tabla = $_SESSION['conexionsql']->query($consulta);
		if ($tabla->num_rows>0)
			{	$registrox = $tabla->fetch_object();
				$exento = rellena_cero(formato_natural($registrox->exento),15);	
				$exento1 = $registrox->exento;	}
		else
			{	$exento = rellena_cero(formato_natural(0),15);	
				$exento1 = 0;	}
		//-----------
		$anno = anno($registro->fecha);
		$mes = mes($registro->fecha);
		$fecha = $registro->fecha;
		$rif = $registro->rif;
		$numero = rellena_cero($registro->numero,8);
		$factura = rellena_cero($registro->factura,20);
		$control = rellena_cero($registro->control,20);
		$base = rellena_cero($registro->base,15);
		$iva = rellena_cero(formato_natural(($registro->iva*$registro->porcentaje/100)),15);//*$registro->porcentaje/100
		$total = rellena_cero(formato_natural($registro->base+$registro->iva+$exento1),15);
		$porcentaje = rellena_cero(formato_natural($registro->porcentaje_iva),5);
		//-----------
		$linea = 'G200012870'."	$anno$mes	$fecha	C	01	$rif	$factura	$control	$total	$base	$iva	0	$anno$mes$numero	$exento	$porcentaje	0"; //echo $linea;
		if ($i>0) {fwrite($file, "\r\n");}
		fwrite($file, $linea);
		$i++;
		}
	fclose($file);
	//-----------
	$archivo = '../retenciones_txt.txt';
	header("Content-Description: Descargar TXT");
	header("Content-Disposition: attachment; filename=$anno$mes.txt");
	header("Content-Type: application/force-download");
	header("Content-Length: " . filesize($archivo));
	header("Content-Transfer-Encoding: binary");
	readfile($archivo);
	//----------- 
	}
?>