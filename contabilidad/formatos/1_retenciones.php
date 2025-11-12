<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Header()
	{    
	}	
	
	function Footer()
	{    
	}	
}

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(17,12,17);
$pdf->SetAutoPageBreak(1,12);
$pdf->SetTitle('Comprobante de Retencion');

// ----------
$id_orden = decriptar($_GET['id']);
//--------------
//$consulta = "UPDATE ordenes_pago SET estatus = 15 WHERE id = $id_orden AND estatus < 15;"; //
//echo $consulta;
//$tabla = $_SESSION['conexionsql']->query($consulta);
//--------------
$consulta = "SELECT ordenes_pago_descuentos.sustraendo, ordenes_pago_descuentos.cant_sustraendo, orden_solicitudes.fecha_factura, ordenes_pago_descuentos.id, ordenes_pago.fecha, ordenes_pago.estatus, orden_solicitudes.anno, vista_contribuyentes_direccion.rif, vista_contribuyentes_direccion.contribuyente, vista_contribuyentes_direccion.direccion, a_retenciones.titulo, a_retenciones.decripcion, ordenes_pago_descuentos.id_descuento,	ordenes_pago_descuentos.porcentaje, ordenes_pago_descuentos.descuento FROM ordenes_pago , orden_solicitudes , vista_contribuyentes_direccion , a_retenciones , ordenes_pago_descuentos WHERE ordenes_pago.id=$id_orden AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND vista_contribuyentes_direccion.id = ordenes_pago.id_contribuyente AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id AND ordenes_pago_descuentos.id_descuento = a_retenciones.id GROUP BY a_retenciones.id;"; 
//echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
while ($registro = $tabla->fetch_object())
{
$pdf->AddPage();
$id = $registro->id;
$id_descuento = $registro->id_descuento;
$porcentaje = $registro->porcentaje;
$sustraendo = $registro->sustraendo;
$cant_sustraendo = $registro->cant_sustraendo;
//-------------
$anno = $registro->anno;
$tipo = $registro->titulo;
$fechao = $registro->fecha;
$fechaf = $registro->fecha_factura;
$rif = $registro->rif;
$contribuyente = $registro->contribuyente;
$direccion = $registro->direccion;
//----------------
list($numero,$fecha)=explode(' ', reten_sig($id, $id_descuento));	

//-------------

$pdf->SetFillColor(240);
$pdf->SetTextColor(0);
	if (anno($fechao)<2024)
	{$pdf->Image('../../images/logo_2023.jpg',27,7,32);}
	else
	{$pdf->Image('../../images/logo_nuevo.jpg',27,7,40);}
//$pdf->Image('../../images/logo_nuevo.jpg',27,7,40);
$pdf->Image('../../images/bandera_linea.png',17,41,245,1);
$pdf->Image('../../images/linea.png',17,45,245,1);
$pdf->Image('../../images/linea.png',17,60,245,1);
$pdf->Image('../../images/linea.png',17,75,245,1);
$pdf->Image('../../images/linea.png',17,90,245,1);
//$pdf->Image('../../images/firma_administrador.png',50,165,55);
$pdf->SetFont('Times','',11);
// ---------------------

////$instituto = instituto();
$pdf->SetY(12);
$pdf->SetFont('Times','I',11.5);
$pdf->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Ejercicio Fiscal '.$anno,0,0,'C'); 
$pdf->Ln(8);

$pdf->SetFont('Times','B',14);
$pdf->Cell(0,5,'COMPROBANTE DE RETENCIÓN DE '.$tipo,0,0,'C'); 

$pdf->SetY(11);
//$pdf->SetX(150);
$pdf->SetFont('Arial','B',11);
$pdf->SetTextColor(0);
$pdf->Cell(190,5,'');
$pdf->Cell(0,5,'Numero de Comprobante',0,1,'C'); //$pdf->Ln(7);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(190,5,'');
$pdf->Cell(0,5,anno($fecha).mes($fecha).rellena_cero($numero,8),0,1,'C'); //$pdf->Ln(7);
$pdf->SetTextColor(0);
$pdf->Cell(190,5,'');
$pdf->Cell(0,5,'Periodo Fiscal',0,1,'C'); //$pdf->Ln(7);
$pdf->SetTextColor(0,0,255);
$pdf->Cell(190,5,'');
$pdf->Cell(0,5,mes($fecha).'-'.anno($fecha),0,1,'C'); //$pdf->Ln(7);
$pdf->SetTextColor(0);
$pdf->Cell(190,5,'');
$pdf->Cell(0,5,'Fecha',0,1,'C'); //
$pdf->SetTextColor(0,0,255);
$pdf->Cell(190,5,'');
$pdf->Cell(0,5,voltea_fecha($fecha),0,0,'C'); //
$pdf->SetTextColor(0);
//$pdf->Ln(20);
$pdf->SetY(47);

$pdf->SetFont('Times','',9);
$pdf->Cell(3,5,''); 
$pdf->Cell(130,5,'RAZON SOCIAL DEL AGENTE DE RETENCION:',0,0,'L');
$pdf->Cell(20,5,'REGISTRO DE INFORMACION FISCAL DEL AGENTE DE RETENCION:',0,0,'L');$pdf->Ln();
$pdf->SetFont('Times','B',10);
$Y = $pdf->GetY();
$pdf->MultiCell(120,4,strtoupper('Contraloria del Estado Bolivariano de Guárico'),0,'J');
$pdf->SetY($Y);
$pdf->Cell(140,5,'',0,0,'L'); 
$pdf->Cell(20,5,formato_rif('G200012870'),0,0,'L'); 
$pdf->Ln(10);

$pdf->SetFont('Times','',9);
$pdf->Cell(3,5,''); 
$pdf->Cell(140,5,'DOMICILIO FISCAL DEL AGENTE DE RETENCION:',0,0,'L');$pdf->Ln();
//$pdf->Cell(20,5,'RIF:',0,0,'L');
$pdf->SetFont('Times','B',10);
$pdf->Cell(140,5,strtoupper('Calle Mariño, Edificio Don Vito entre Av. Bolívar y Av. Monseñor Sendrea.'),0,0,'L');
//$pdf->Cell(20,5,formato_rif($rif),0,0,'L'); 
$pdf->Ln(10);

$pdf->SetFont('Times','',9);
$pdf->Cell(3,5,''); 
$pdf->Cell(130,5,'RAZON SOCIAL DEL CONTRIBUYENTE SUJETO A RETENIDO:',0,0,'L');
$pdf->Cell(20,5,'REGISTRO DE INFORMACION FISCAL:',0,0,'L');$pdf->Ln();
$pdf->SetFont('Times','B',10);
$pdf->Cell(140,5,$contribuyente,0,0,'L');
$pdf->Cell(20,5,formato_rif($rif),0,0,'L'); //$pdf->Ln();

$pdf->SetY(92);
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,9.5,'RETENCIÓN APLICADA',1,1,'C',1); 
$pdf->SetFont('Times','B',8);
$pdf->Cell(10,6.5,'N°',1,0,'C',1);
$pdf->Cell(18,6.5,'FECHA',1,0,'C',1);
$pdf->Cell($a=27,6.5,'N° FACTURA',1,0,'C',1);
$pdf->Cell($a,6.5,'N° CONTROL',1,0,'C',1);
$pdf->Cell($a,6.5,'MONTO TOTAL',1,0,'C',1);
$pdf->Cell($a,6.5,'BASE IMPONIBLE',1,0,'C',1);
$pdf->Cell($a,6.5,'MONTO EXENTO',1,0,'C',1);
if ($id_descuento==7)	{$pdf->Cell($a,6.5,'IVA',1,0,'C',1);}
	else {$pdf->Cell($a,6.5,'MONTO BASE',1,0,'C',1);}
$pdf->Cell($aa=22,6.5,'ALICUOTA %',1,0,'C',1);
$pdf->Cell(0,6.5,'MONTO RETENIDO',1,0,'C',1);
$pdf->Ln();
		
$pdf->SetY(108);
$pdf->SetFont('Times','',9);
//$a=20;
$b=9;
$c=0;

//----------- 
$consulta = "DROP TABLE IF EXISTS base_imponible;"; 
$tablax = $_SESSION['conexionsql']->query($consulta);
$consulta = "CREATE TEMPORARY TABLE base_imponible ( SELECT orden.factura, orden.control, orden_solicitudes.fecha_factura, SUM( orden.total ) AS imponible  FROM ordenes_pago, orden_solicitudes, orden  WHERE LEFT ( orden.partida, 7 ) <> '4031801' AND orden.exento = 0 AND ordenes_pago.id = orden_solicitudes.id_orden_pago  AND orden_solicitudes.id = orden.id_solicitud  AND ordenes_pago.id = $id_orden GROUP BY orden.factura, orden.control ORDER BY orden.factura, orden.control );";
$tablax = $_SESSION['conexionsql']->query($consulta);

if ($id_descuento==6)
	{
	$consulta = "DROP TABLE IF EXISTS base_imponible;"; 
	$tablax = $_SESSION['conexionsql']->query($consulta);
	$consulta = "CREATE TEMPORARY TABLE base_imponible ( SELECT orden.factura, orden.control, orden_solicitudes.fecha_factura, SUM( orden.total ) AS imponible  FROM ordenes_pago, orden_solicitudes, orden  WHERE LEFT ( orden.partida, 7 ) <> '4031801' AND LEFT ( orden.partida, 7 ) <> '4031801' AND (LEFT( orden.partida,3 ) = '404' or LEFT( orden.partida,3 ) = '403' or LEFT( orden.partida,3 ) = '401' or LEFT( orden.partida,9 ) = '404020100') AND orden.exento = 0 AND ordenes_pago.id = orden_solicitudes.id_orden_pago  AND orden_solicitudes.id = orden.id_solicitud  AND ordenes_pago.id = $id_orden GROUP BY orden.factura, orden.control ORDER BY orden.factura, orden.control );";
	$tablax = $_SESSION['conexionsql']->query($consulta);
	//----------- 
	$consulta = "DROP TABLE IF EXISTS base_retencion;"; 
	$tablax = $_SESSION['conexionsql']->query($consulta);
	$consulta = "CREATE TEMPORARY TABLE base_retencion ( SELECT orden.factura, orden.control, orden_solicitudes.fecha_factura, SUM( orden.total ) AS base  FROM ordenes_pago, orden_solicitudes, orden  WHERE LEFT ( orden.partida, 7 ) <> '4031801' AND (LEFT( orden.partida,3 ) = '404' or LEFT( orden.partida,3 ) = '403' or LEFT( orden.partida,3 ) = '401') AND ordenes_pago.id = orden_solicitudes.id_orden_pago  AND orden_solicitudes.id = orden.id_solicitud  AND ordenes_pago.id = $id_orden GROUP BY orden.factura, orden.control ORDER BY orden.factura, orden.control);";
	$tablax = $_SESSION['conexionsql']->query($consulta);
	}
elseif ($id_descuento==7)
	{
	//----------- 
	$consulta = "DROP TABLE IF EXISTS base_retencion;"; 
	$tablax = $_SESSION['conexionsql']->query($consulta);
	$consulta = "CREATE TEMPORARY TABLE base_retencion ( SELECT orden.factura, orden.control, orden_solicitudes.fecha_factura, SUM( orden.total ) AS base  FROM ordenes_pago, orden_solicitudes, orden  WHERE LEFT ( orden.partida, 7 ) <> '4031801'  AND orden.exento = 0 AND ordenes_pago.id = orden_solicitudes.id_orden_pago  AND orden_solicitudes.id = orden.id_solicitud  AND ordenes_pago.id = $id_orden GROUP BY orden.factura, orden.control ORDER BY orden.factura, orden.control);";
	$tablax = $_SESSION['conexionsql']->query($consulta);
	}
else
	{
	//----------- 
	$consulta = "DROP TABLE IF EXISTS base_retencion;"; 
	$tablax = $_SESSION['conexionsql']->query($consulta);
	$consulta = "CREATE TEMPORARY TABLE base_retencion ( SELECT orden.factura, orden.control, orden_solicitudes.fecha_factura, SUM( orden.total ) AS base  FROM ordenes_pago, orden_solicitudes, orden  WHERE LEFT ( orden.partida, 7 ) <> '4031801' AND ordenes_pago.id = orden_solicitudes.id_orden_pago  AND orden_solicitudes.id = orden.id_solicitud  AND ordenes_pago.id = $id_orden GROUP BY orden.factura, orden.control ORDER BY orden.factura, orden.control);";
	$tablax = $_SESSION['conexionsql']->query($consulta);
	}
	
//----------- 
$consulta = "DROP TABLE IF EXISTS base_exenta;"; 
$tablax = $_SESSION['conexionsql']->query($consulta);
$consulta = "CREATE TEMPORARY TABLE base_exenta (SELECT orden.factura, orden.control, orden_solicitudes.fecha_factura, SUM( orden.total ) AS exento FROM ordenes_pago, orden_solicitudes, orden WHERE LEFT ( orden.partida, 9 ) <> '403180100' AND orden.exento = 1 AND ordenes_pago.id = orden_solicitudes.id_orden_pago AND orden_solicitudes.id = orden.id_solicitud AND ordenes_pago.id = $id_orden GROUP BY orden.factura, orden.control ORDER BY orden.factura, orden.control);";
$tablax = $_SESSION['conexionsql']->query($consulta);
//----------- 
$consulta = "DROP TABLE IF EXISTS base_iva;"; 
$tablax = $_SESSION['conexionsql']->query($consulta);
$consulta = "CREATE TEMPORARY TABLE base_iva (SELECT orden.factura, orden.control, orden_solicitudes.fecha_factura, SUM( orden.total ) AS iva FROM ordenes_pago, orden_solicitudes, orden WHERE LEFT ( orden.partida, 9 ) = '403180100'  AND ordenes_pago.id = orden_solicitudes.id_orden_pago  AND orden_solicitudes.id = orden.id_solicitud AND ordenes_pago.id = $id_orden GROUP BY orden.factura, orden.control ORDER BY orden.factura, orden.control);";
$tablax = $_SESSION['conexionsql']->query($consulta);
//-----------------
$i=1;
$alto = 5;
$total = 0;
$total_base = 0;
$total_exento = 0;
$total_imponible = 0;
$total_retencion = 0;
//----------- 
$consulta = "SELECT factura, control, fecha_factura, SUM(imponible) as imponible, SUM(exento) as exento, SUM(base) as base, SUM(iva) as iva FROM 
(	  (SELECT 'factura', 'control', 'fecha_factura', 'imponible', 'base', 'exento', 'iva') 
UNION (SELECT factura, control, fecha_factura, imponible, 0, 0, 0 FROM base_imponible) 
UNION (SELECT factura, control, fecha_factura, 0, base, 0, 0 FROM base_retencion) 
UNION (SELECT factura, control, fecha_factura, 0, 0, exento, 0 FROM base_exenta) 
UNION (SELECT factura, control, fecha_factura, 0, 0, 0, iva FROM base_iva)
) as lista WHERE factura<>'factura' GROUP BY factura, control ORDER BY factura, control;";
$tablx = $_SESSION['conexionsql']->query($consulta);
//-----------------
while ($registroxx = $tablx->fetch_object())
	{
	$detalle_total = $registroxx->imponible + $registroxx->exento + $registroxx->iva ;
	$detalle_imponible = $registroxx->imponible;
	$detalle_base = $registroxx->base;
	$detalle_exento = $registroxx->exento;
	$detalle_iva = $registroxx->iva;
	//----------
	if ($id_descuento==7)
		{	
		$detalle_base = $registroxx->iva;
		$detalle_retencion = ($detalle_base * $porcentaje) /100;
		}
	else
		{	
		if ($id_descuento==6)
			{
			if ($sustraendo>0) {	$sustraendox = $sustraendo/$cant_sustraendo;	}
				else	{	$sustraendox = 0;	}
			$detalle_base = $registroxx->imponible;// + $registroxx->exento;
			$detalle_retencion = (($detalle_base * $porcentaje) /100)-($sustraendox);
			}
		else
			{
			if ($sustraendo>0) {	$sustraendox = $sustraendo/$cant_sustraendo;	}
				else	{	$sustraendox = 0;	}
			$detalle_base = $registroxx->imponible + $registroxx->exento;
			$detalle_retencion = (($detalle_base * $porcentaje) /100)-($sustraendox);
			}
		}
	//-----------
	if ($detalle_base>0)
		{
		//----------
		$pdf->Cell(10,$alto,$i,1,0,'C',0);
		$pdf->Cell(18,$alto,voltea_fecha($registroxx->fecha_factura),1,0,'C',0);
		$pdf->Cell($b=$a,$alto,($registroxx->factura),1,0,'C',0);
		$pdf->Cell($b,$alto,($registroxx->control),1,0,'C',0);
		$pdf->Cell($b,$alto,formato_moneda($detalle_total),1,0,'R',0);
		$pdf->Cell($b,$alto,formato_moneda($detalle_imponible),1,0,'R',0);
		$pdf->Cell($b,$alto,formato_moneda($detalle_exento),1,0,'R',0);
		$pdf->Cell($b,$alto,formato_moneda($detalle_base),1,0,'R',0);
		$pdf->Cell($aa,$alto,formato_moneda($porcentaje),1,0,'R',0);
		$pdf->Cell(0,$alto,formato_moneda($detalle_retencion),1,1,'R',0);
		//-----------
		$total += $detalle_total;
		$total_base += $detalle_base;
		$total_exento += $detalle_exento;
		$total_imponible += $detalle_imponible;
		$total_retencion += $detalle_retencion;
		//-----------
		$i++;
		}
	}

$pdf->SetFillColor(240);
$pdf->SetFont('Times','B',10);
$pdf->Cell(10+18+$b*2,7,'TOTALES',1,0,'C',1);
$pdf->Cell($b,7,formato_moneda($total),1,0,'R',1);
$pdf->Cell($b,7,formato_moneda($total_imponible),1,0,'R',1);
$pdf->Cell($b,7,formato_moneda($total_exento),1,0,'R',1);
$pdf->Cell($b,7,formato_moneda($total_base),1,0,'R',1);
$pdf->Cell($aa,7,formato_moneda($porcentaje),1,0,'R',1);
$pdf->Cell(0,7,formato_moneda($total_retencion),1,0,'R',1);

if ($_SESSION['estatus']==99)	
	{
	$pdf->SetY(140);
	$pdf->SetTextColor(255,0,0);
	$pdf->SetFont('helvetica','',35);
	$pdf->Cell(0,5,'COMPROBANTE ANULADO',0,0,'C'); 
	$pdf->SetFont('Times','',10);
	$pdf->SetTextColor(0);
	}

//-------------------------------------------------
$pdf->SetFont('Times','B',10);
$pdf->SetY(-35);
$pdf->SetFillColor(245);
$pdf->Cell(30,6,'',0,0,'L',0);
$pdf->Cell($a=60,6,'',0,0,'C',0);
$pdf->Cell(30,6,'');
$pdf->Cell(30,6,'Recibido por:',0,0,'L',0);
$pdf->Cell($a=60,6,'___________________________________',0,1,'C',0);
$pdf->Cell(30,6,'',0,0,'L',0);
$pdf->Cell($a,6,'Administración y Presupuesto',0,0,'C',0);
$pdf->Cell(60,6,'',0,0,'L',0);
$pdf->Cell($a,6,'Contribuyente',0,0,'C',0);

//--------------
$pdf->SetFont('Times','I',8);
$pdf->SetY(-13);
$pdf->SetTextColor(120);
//--------------
$pdf->Cell(80,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
$pdf->Cell(0,0,'SIACEBG'.' '.$pdf->PageNo().' de paginas',0,0,'R');

}	
$pdf->Output();
?>