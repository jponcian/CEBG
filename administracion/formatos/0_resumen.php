<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Header()
	{    
		$this->SetY(10);
		$fecha1 = ($_GET['fecha1']);//decriptar
		$fecha2 = ($_GET['fecha2']);
		$anno = anno($_GET['fecha2']);
		// ---------------------
	
		$this->SetFillColor(240);
		if (anno($fecha2)<2024)
		{$this->Image('../../images/logo_2023.jpg',27,7,32);}
		else
		{$this->Image('../../images/logo_nuevo.jpg',27,7,40);}
		$this->Image('../../images/bandera_linea.png',17,41,182,1);
		$this->SetFont('Times','',11);
		
		$municipio = 'Francisco de Miranda';
		// ---------------------
		$instituto= instituto();
		$this->SetFont('Times','I',11.5);
		$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Rif G-20001287-0 - Ejercicio Fiscal '.$anno,0,0,'C'); $this->Ln(8);
		
		$this->SetFont('Times','B',14);
		$this->Cell(0,5,'RESUMEN ADMINISTRATIVO',0,0,'C'); 
		$this->Ln(10);
		
		$y=$this->GetY();
		$this->SetY(25);
		$this->SetFont('Arial','B',12);
		$this->SetTextColor(255,0,0);
		$this->Cell(0,5,'Desde: '.voltea_fecha($fecha1),0,0,'R');
		$this->Ln();
		$this->Cell(0,5,'Hasta: '.voltea_fecha($fecha2),0,0,'R');
		$this->SetTextColor(0);
		$this->SetY($y);
		
	}	
	
	function Footer()
	{    
		//--------------
		$this->SetFont('Times','I',8);
		$this->SetY(-13);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['empleado'],0,0,'L');
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de paginas',0,0,'R');
	}	
}

$fecha1 = ($_GET['fecha1']);//decriptar
$fecha2 = ($_GET['fecha2']);
$anno = anno($_GET['fecha2']);
//-------------	

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(12,20,12);
$pdf->SetAutoPageBreak(1,17);
$pdf->SetTitle('Resumen');

//-----------------
$consulta = "CALL actualizar_orden_pago();";
//$tabla = $_SESSION['conexionsql']->query($consulta);
//----------------- ORDENES PAGO
$consulta = "SELECT contribuyente.rif,	contribuyente.nombre,	ordenes_pago.numero,	ordenes_pago.fecha,	ordenes_pago.asignaciones,	ordenes_pago.descuentos,	ordenes_pago.total,	ordenes_pago.tipo_solicitud,	ordenes_pago.estatus FROM	ordenes_pago,	contribuyente WHERE ordenes_pago.id_contribuyente = contribuyente.id 	AND fecha >= '$fecha1' 	AND fecha <= '$fecha2' AND tipo_solicitud <> 'FINANCIERA' AND tipo_solicitud <> 'NOMINA' ORDER BY	numero ASC;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($tabla->num_rows>0)	
	{// ----------
	$pdf->AddPage();
	
	$pdf->SetFillColor(235);
	$alto = 6;
	$pdf->Ln(5);
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(0,5,'ORDENES DE PAGO',0,0,'C'); 
	$pdf->SetFont('Times','B',11);
	$pdf->Ln(10);
	$pdf->Cell($a=20,$alto,'Rif',1,0,'C',1);
	$pdf->Cell($b=45,$alto,'Contribuyente',1,0,'C',1);
	$pdf->Cell($c=15,$alto,'Numero',1,0,'C',1);
	$pdf->Cell($d=18,$alto,'Fecha',1,0,'C',1);
	$pdf->Cell($h=18,$alto,'Estatus',1,0,'C',1);
	$pdf->Cell($e=25,$alto,'Monto',1,0,'C',1);
	$pdf->Cell($f=25,$alto,'Retenciones',1,0,'C',1);
	$pdf->Cell($g=0,$alto,'Total',1,0,'C',1);
	$pdf->Ln($alto);
	//----------
	$alto = 5;
	$pdf->SetFillColor(255);
	$pendientes =0; 
	$asignacionese=0;	
	$descuentose=0;	
	$totale=0;	
	$pagadas =0; 
	$asignacionesp=0;	
	$descuentosp=0;	
	$totalp=0;	
	$anuladas =0; 
	$asignacionesa=0;	
	$descuentosa=0;	
	$totala=0;	
	$i=1;
	while ($registro = $tabla->fetch_object())
		{
		$pdf->SetFont('Times','',9);
		$pdf->Cell($a,$alto,($registro->rif),1,0,'C',1);
		$pdf->Cell($b,$alto,$registro->nombre,1,0,'L',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($c,$alto,rellena_cero($registro->numero,4),1,0,'C',1);
		$pdf->SetFont('Times','',9);
		$pdf->Cell($d,$alto,voltea_fecha($registro->fecha),1,0,'C',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($h,$alto,estatus_op($registro->estatus),1,0,'C',1);
		$pdf->SetFont('Times','',9);
		$pdf->Cell($e,$alto,formato_moneda($registro->asignaciones),1,0,'R',1);
		$pdf->Cell($f,$alto,formato_moneda($registro->descuentos),1,0,'R',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($g,$alto,formato_moneda($registro->total),1,0,'R',1);
		$pdf->Ln($alto);
		//-----------
		if ($registro->estatus<10)
			{	
			$pendientes += 1; 
			$asignacionese += $registro->asignaciones;	
			$descuentose += $registro->descuentos;	
			$totale += $registro->total;	
			}
		//-----------
		if ($registro->estatus>=10 and $registro->estatus<99)
			{	
			$pagadas += 1; 
			$asignacionesp += $registro->asignaciones;	
			$descuentosp += $registro->descuentos;	
			$totalp += $registro->total;	
			}		
		//-----------
		if ($registro->estatus==99)
			{	
			$anuladas += 1; 
			$asignacionesa += $registro->asignaciones;	
			$descuentosa += $registro->descuentos;	
			$totala += $registro->total;	
			}
		$i++;
		}
	$pdf->Ln($alto);
	$alto = 6;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell($a=50,$alto,"ORDEN(ES) DE PAGO",1,0,'C',1);
	$pdf->Cell($e=20,$alto,"CANT",1,0,'C',1);
	$pdf->Cell($f=40,$alto,"MONTO",1,0,'C',1);
	$pdf->Cell($g=40,$alto,"RETENCIONES",1,0,'C',1);
	$pdf->Cell(0,$alto,"TOTAL BS.",1,0,'C',1);
	$pdf->SetFillColor(255);
	$pdf->SetFont('Times','',10.5);
	$pdf->Ln($alto);
	if ($pendientes>0)
		{
		$pdf->Cell($a,$alto,"PENDIENTES",1,0,'C',1);
		$pdf->Cell($e,$alto,($pendientes),1,0,'C',1);
		$pdf->Cell($f,$alto,formato_moneda($asignacionese),1,0,'R',1);
		$pdf->Cell($g,$alto,formato_moneda($descuentose),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totale),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($pagadas>0)
		{
		$pdf->Cell($a,$alto,"PAGADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($pagadas),1,0,'C',1);
		$pdf->Cell($f,$alto,formato_moneda($asignacionesp),1,0,'R',1);
		$pdf->Cell($g,$alto,formato_moneda($descuentosp),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totalp),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($anuladas>0)
		{	
		$pdf->Cell($a,$alto,"ANULADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($anuladas),1,0,'C',1);
		$pdf->Cell($f,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Cell($g,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Ln($alto);
		}
	$pdf->SetFont('Times','B',11);
	$pdf->SetFillColor(235);
	$pdf->Cell($a,$alto,"T.O.T.A.L",1,0,'C',1);
	$pdf->Cell($e,$alto,($pendientes + $pagadas + $anuladas),1,0,'C',1);
	$pdf->Cell($f,$alto,formato_moneda($asignacionese + $asignacionesp),1,0,'R',1);
	$pdf->Cell($g,$alto,formato_moneda($descuentose + $descuentosp),1,0,'R',1);
	$pdf->Cell(0,$alto,formato_moneda($totale + $totalp),1,0,'R',1);
	$pdf->Ln($alto);
	}

//----------------- ORDENES PAGO NOMINA
$consulta = "SELECT contribuyente.rif,	contribuyente.nombre,	ordenes_pago.numero,	ordenes_pago.fecha,	ordenes_pago.asignaciones,	ordenes_pago.descuentos,	ordenes_pago.total,	ordenes_pago.tipo_solicitud,	ordenes_pago.estatus FROM	ordenes_pago,	contribuyente WHERE ordenes_pago.id_contribuyente = contribuyente.id 	AND fecha >= '$fecha1' 	AND fecha <= '$fecha2' AND tipo_solicitud = 'NOMINA' ORDER BY	numero ASC;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($tabla->num_rows>0)	
	{// ----------
	$pdf->AddPage();
	
	$pdf->SetFillColor(235);
	$alto = 6;
	$pdf->Ln(5);
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(0,5,'ORDENES DE PAGO (NOMINA)',0,0,'C'); 
	$pdf->SetFont('Times','B',11);
	$pdf->Ln(10);
	$pdf->Cell($a=20,$alto,'Rif',1,0,'C',1);
	$pdf->Cell($b=45,$alto,'Contribuyente',1,0,'C',1);
	$pdf->Cell($c=15,$alto,'Numero',1,0,'C',1);
	$pdf->Cell($d=18,$alto,'Fecha',1,0,'C',1);
	$pdf->Cell($h=18,$alto,'Estatus',1,0,'C',1);
	$pdf->Cell($e=25,$alto,'Monto',1,0,'C',1);
	$pdf->Cell($f=25,$alto,'Descuentos',1,0,'C',1);
	$pdf->Cell($g=0,$alto,'Total',1,0,'C',1);
	$pdf->Ln($alto);
	//----------
	$alto = 5;
	$pdf->SetFillColor(255);
	$pendientes =0; 
	$asignacionese=0;	
	$descuentose=0;	
	$totale=0;	
	$pagadas =0; 
	$asignacionesp=0;	
	$descuentosp=0;	
	$totalp=0;	
	$anuladas =0; 
	$asignacionesa=0;	
	$descuentosa=0;	
	$totala=0;	
	$i=1;
	while ($registro = $tabla->fetch_object())
		{
		$pdf->SetFont('Times','',9);
		$pdf->Cell($a,$alto,($registro->rif),1,0,'C',1);
		$pdf->Cell($b,$alto,$registro->nombre,1,0,'L',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($c,$alto,rellena_cero($registro->numero,4),1,0,'C',1);
		$pdf->SetFont('Times','',9);
		$pdf->Cell($d,$alto,voltea_fecha($registro->fecha),1,0,'C',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($h,$alto,estatus_op($registro->estatus),1,0,'C',1);
		$pdf->SetFont('Times','',9);
		$pdf->Cell($e,$alto,formato_moneda($registro->asignaciones),1,0,'R',1);
		$pdf->Cell($f,$alto,formato_moneda($registro->descuentos),1,0,'R',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($g,$alto,formato_moneda($registro->total),1,0,'R',1);
		$pdf->Ln($alto);
		//-----------
		if ($registro->estatus<10)
			{	
			$pendientes += 1; 
			$asignacionese += $registro->asignaciones;	
			$descuentose += $registro->descuentos;	
			$totale += $registro->total;	
			}
		//-----------
		if ($registro->estatus>=10 and $registro->estatus<99)
			{	
			$pagadas += 1; 
			$asignacionesp += $registro->asignaciones;	
			$descuentosp += $registro->descuentos;	
			$totalp += $registro->total;	
			}		
		//-----------
		if ($registro->estatus==99)
			{	
			$anuladas += 1; 
			$asignacionesa += $registro->asignaciones;	
			$descuentosa += $registro->descuentos;	
			$totala += $registro->total;	
			}
		$i++;
		}
	$pdf->Ln($alto);
	$alto = 6;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell($a=50,$alto,"ORDEN(ES) DE PAGO",1,0,'C',1);
	$pdf->Cell($e=20,$alto,"CANT",1,0,'C',1);
	$pdf->Cell($f=40,$alto,"MONTO",1,0,'C',1);
	$pdf->Cell($g=40,$alto,"DESCUENTOS",1,0,'C',1);
	$pdf->Cell(0,$alto,"TOTAL BS.",1,0,'C',1);
	$pdf->SetFillColor(255);
	$pdf->SetFont('Times','',10.5);
	$pdf->Ln($alto);
	if ($pendientes>0)
		{
		$pdf->Cell($a,$alto,"EMITIDAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($pendientes),1,0,'C',1);
		$pdf->Cell($f,$alto,formato_moneda($asignacionese),1,0,'R',1);
		$pdf->Cell($g,$alto,formato_moneda($descuentose),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totale),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($pagadas>0)
		{
		$pdf->Cell($a,$alto,"PAGADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($pagadas),1,0,'C',1);
		$pdf->Cell($f,$alto,formato_moneda($asignacionesp),1,0,'R',1);
		$pdf->Cell($g,$alto,formato_moneda($descuentosp),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totalp),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($anuladas>0)
		{	
		$pdf->Cell($a,$alto,"ANULADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($anuladas),1,0,'C',1);
		$pdf->Cell($f,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Cell($g,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Ln($alto);
		}
	$pdf->SetFont('Times','B',11);
	$pdf->SetFillColor(235);
	$pdf->Cell($a,$alto,"T.O.T.A.L",1,0,'C',1);
	$pdf->Cell($e,$alto,($pendientes + $pagadas + $anuladas),1,0,'C',1);
	$pdf->Cell($f,$alto,formato_moneda($asignacionese + $asignacionesp),1,0,'R',1);
	$pdf->Cell($g,$alto,formato_moneda($descuentose + $descuentosp),1,0,'R',1);
	$pdf->Cell(0,$alto,formato_moneda($totale + $totalp),1,0,'R',1);
	$pdf->Ln($alto);
	}

//----------------- ORDENES PAGO FINANCIERA
$consulta = "SELECT contribuyente.rif,	contribuyente.nombre,	ordenes_pago.numero,	ordenes_pago.fecha,	ordenes_pago.asignaciones,	ordenes_pago.descuentos,	ordenes_pago.total,	ordenes_pago.tipo_solicitud,	ordenes_pago.estatus FROM	ordenes_pago,	contribuyente WHERE ordenes_pago.id_contribuyente = contribuyente.id 	AND fecha >= '$fecha1' 	AND fecha <= '$fecha2' AND tipo_solicitud = 'FINANCIERA' ORDER BY	numero ASC;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($tabla->num_rows>0)	
	{// ----------
	$pdf->AddPage();
	
	$pdf->SetFillColor(235);
	$alto = 6;
	$pdf->Ln(5);
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(0,5,'ORDENES DE PAGO (FINANCIERA)',0,0,'C'); 
	$pdf->SetFont('Times','B',11);
	$pdf->Ln(10);
	$pdf->Cell($a=20,$alto,'Rif',1,0,'C',1);
	$pdf->Cell($b=95,$alto,'Contribuyente',1,0,'C',1);
	$pdf->Cell($c=15,$alto,'Numero',1,0,'C',1);
	$pdf->Cell($d=18,$alto,'Fecha',1,0,'C',1);
	$pdf->Cell($h=18,$alto,'Estatus',1,0,'C',1);
//	$pdf->Cell($e=25,$alto,'Monto',1,0,'C',1);
//	$pdf->Cell($f=25,$alto,'Descuentos',1,0,'C',1);
	$pdf->Cell($g=0,$alto,'Total',1,0,'C',1);
	$pdf->Ln($alto);
	//----------
	$alto = 5;
	$pdf->SetFillColor(255);
	$pendientes =0; 
	$asignacionese=0;	
	$descuentose=0;	
	$totale=0;	
	$pagadas =0; 
	$asignacionesp=0;	
	$descuentosp=0;	
	$totalp=0;	
	$anuladas =0; 
	$asignacionesa=0;	
	$descuentosa=0;	
	$totala=0;	
	$i=1;
	while ($registro = $tabla->fetch_object())
		{
		$pdf->SetFont('Times','',9);
		$pdf->Cell($a,$alto,($registro->rif),1,0,'C',1);
		$pdf->Cell($b,$alto,$registro->nombre,1,0,'L',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($c,$alto,rellena_cero($registro->numero,4),1,0,'C',1);
		$pdf->SetFont('Times','',9);
		$pdf->Cell($d,$alto,voltea_fecha($registro->fecha),1,0,'C',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($h,$alto,estatus_op($registro->estatus),1,0,'C',1);
		$pdf->SetFont('Times','',9);
//		$pdf->Cell($e,$alto,formato_moneda($registro->asignaciones),1,0,'R',1);
//		$pdf->Cell($f,$alto,formato_moneda($registro->descuentos),1,0,'R',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($g,$alto,formato_moneda($registro->total),1,0,'R',1);
		$pdf->Ln($alto);
		//-----------
		if ($registro->estatus<10)
			{	
			$pendientes += 1; 
			$asignacionese += $registro->asignaciones;	
			$descuentose += $registro->descuentos;	
			$totale += $registro->total;	
			}
		//-----------
		if ($registro->estatus>=10 and $registro->estatus<99)
			{	
			$pagadas += 1; 
			$asignacionesp += $registro->asignaciones;	
			$descuentosp += $registro->descuentos;	
			$totalp += $registro->total;	
			}		
		//-----------
		if ($registro->estatus==99)
			{	
			$anuladas += 1; 
			$asignacionesa += $registro->asignaciones;	
			$descuentosa += $registro->descuentos;	
			$totala += $registro->total;	
			}
		$i++;
		}
	$pdf->Ln($alto);
	$alto = 6;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell($a=50,$alto,"ORDEN(ES) DE PAGO",1,0,'C',1);
	$pdf->Cell($e=20,$alto,"CANT",1,0,'C',1);
	$pdf->Cell($f=40,$alto,"MONTO",1,0,'C',1);
	$pdf->Cell($g=40,$alto,"RETENCIONES",1,0,'C',1);
	$pdf->Cell(0,$alto,"TOTAL BS.",1,0,'C',1);
	$pdf->SetFillColor(255);
	$pdf->SetFont('Times','',10.5);
	$pdf->Ln($alto);
	if ($pendientes>0)
		{
		$pdf->Cell($a,$alto,"PENDIENTES",1,0,'C',1);
		$pdf->Cell($e,$alto,($pendientes),1,0,'C',1);
		$pdf->Cell($f,$alto,formato_moneda($asignacionese),1,0,'R',1);
		$pdf->Cell($g,$alto,formato_moneda($descuentose),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totale),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($pagadas>0)
		{
		$pdf->Cell($a,$alto,"PAGADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($pagadas),1,0,'C',1);
		$pdf->Cell($f,$alto,formato_moneda($asignacionesp),1,0,'R',1);
		$pdf->Cell($g,$alto,formato_moneda($descuentosp),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totalp),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($anuladas>0)
		{	
		$pdf->Cell($a,$alto,"ANULADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($anuladas),1,0,'C',1);
		$pdf->Cell($f,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Cell($g,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Ln($alto);
		}
	$pdf->SetFont('Times','B',11);
	$pdf->SetFillColor(235);
	$pdf->Cell($a,$alto,"T.O.T.A.L",1,0,'C',1);
	$pdf->Cell($e,$alto,($pendientes + $pagadas + $anuladas),1,0,'C',1);
	$pdf->Cell($f,$alto,formato_moneda($asignacionese + $asignacionesp),1,0,'R',1);
	$pdf->Cell($g,$alto,formato_moneda($descuentose + $descuentosp),1,0,'R',1);
	$pdf->Cell(0,$alto,formato_moneda($totale + $totalp),1,0,'R',1);
	$pdf->Ln($alto);
	}

//----------------- ORDENES COMPRA
$consulta = "SELECT contribuyente.rif,	contribuyente.nombre,	orden_solicitudes.numero,	orden_solicitudes.fecha,	orden_solicitudes.asignaciones,	orden_solicitudes.descuentos,	orden_solicitudes.total,	orden_solicitudes.tipo_orden,	orden_solicitudes.estatus, orden_solicitudes.num_orden_pago FROM	orden_solicitudes,	contribuyente WHERE orden_solicitudes.id_contribuyente = contribuyente.id 	AND fecha >= '$fecha1' 	AND fecha <= '$fecha2' AND tipo_orden = 1 ORDER BY	numero ASC;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($tabla->num_rows>0)	
	{// ----------
	$pdf->AddPage();
	
	$pdf->SetFillColor(235);
	$alto = 6;
	$pdf->Ln(5);
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(0,5,'ORDENES DE COMPRA',0,0,'C'); 
	$pdf->SetFont('Times','B',11);
	$pdf->Ln(10);
	$pdf->Cell($e=20,$alto,'#OP',1,0,'C',1);
	$pdf->Cell($a=20,$alto,'Rif',1,0,'C',1);
	$pdf->Cell($b=65,$alto,'Contribuyente',1,0,'C',1);
	$pdf->Cell($c=20,$alto,'Numero',1,0,'C',1);
	$pdf->Cell($d=18,$alto,'Fecha',1,0,'C',1);
	$pdf->Cell($h=20,$alto,'Estatus',1,0,'C',1);
//	$pdf->Cell($f=25,$alto,'Descuentos',1,0,'C',1);
	$pdf->Cell($g=0,$alto,'Total',1,0,'C',1);
	$pdf->Ln($alto);
	//----------
	$alto = 5;
	$pdf->SetFillColor(255);
	$pendientes =0; 
	$asignacionese=0;	
	$descuentose=0;	
	$totale=0;	
	$pagadas =0; 
	$asignacionesp=0;	
	$descuentosp=0;	
	$totalp=0;	
	$anuladas =0; 
	$asignacionesa=0;	
	$descuentosa=0;	
	$totala=0;	
	$i=1;
	while ($registro = $tabla->fetch_object())
		{
		$pdf->SetFont('Times','',9);
		$pdf->Cell($e,$alto,rellena_cero($registro->num_orden_pago,4),1,0,'C',1);
		$pdf->Cell($a,$alto,($registro->rif),1,0,'C',1);
		$pdf->Cell($b,$alto,$registro->nombre,1,0,'L',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($c,$alto,rellena_cero($registro->numero,4),1,0,'C',1);
		$pdf->SetFont('Times','',9);
		$pdf->Cell($d,$alto,voltea_fecha($registro->fecha),1,0,'C',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($h,$alto,estatus_op($registro->estatus),1,0,'C',1);
		$pdf->SetFont('Times','',9);
//		$pdf->Cell($f,$alto,formato_moneda($registro->descuentos),1,0,'R',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($g,$alto,formato_moneda($registro->total),1,0,'R',1);
		$pdf->Ln($alto);
		//-----------
		if ($registro->estatus<10)
			{	
			$pendientes += 1; 
			$asignacionese += $registro->asignaciones;	
			$descuentose += $registro->descuentos;	
			$totale += $registro->total;	
			}
		//-----------
		if ($registro->estatus>=10 and $registro->estatus<99)
			{	
			$pagadas += 1; 
			$asignacionesp += $registro->asignaciones;	
			$descuentosp += $registro->descuentos;	
			$totalp += $registro->total;	
			}		
		//-----------
		if ($registro->estatus==99)
			{	
			$anuladas += 1; 
			$asignacionesa += $registro->asignaciones;	
			$descuentosa += $registro->descuentos;	
			$totala += $registro->total;	
			}
		$i++;
		}
	$pdf->Ln($alto);
	$alto = 6;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell($a=120,$alto,"ORDEN(ES) DE COMPRA",1,0,'C',1);
	$pdf->Cell($e=20,$alto,"CANT",1,0,'C',1);
//	$pdf->Cell($f=40,$alto,"MONTO",1,0,'C',1);
//	$pdf->Cell($g=40,$alto,"RETENCIONES",1,0,'C',1);
	$pdf->Cell(0,$alto,"TOTAL BS.",1,0,'C',1);
	$pdf->SetFillColor(255);
	$pdf->SetFont('Times','',10.5);
	$pdf->Ln($alto);
	if ($pendientes>0)
		{
		$pdf->Cell($a,$alto,"PENDIENTES",1,0,'C',1);
		$pdf->Cell($e,$alto,($pendientes),1,0,'C',1);
//		$pdf->Cell($f,$alto,formato_moneda($asignacionese),1,0,'R',1);
//		$pdf->Cell($g,$alto,formato_moneda($descuentose),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totale),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($pagadas>0)
		{
		$pdf->Cell($a,$alto,"PAGADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($pagadas),1,0,'C',1);
//		$pdf->Cell($f,$alto,formato_moneda($asignacionesp),1,0,'R',1);
//		$pdf->Cell($g,$alto,formato_moneda($descuentosp),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totalp),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($anuladas>0)
		{	
		$pdf->Cell($a,$alto,"ANULADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($anuladas),1,0,'C',1);
//		$pdf->Cell($f,$alto,formato_moneda(0),1,0,'R',1);
//		$pdf->Cell($g,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Ln($alto);
		}
	$pdf->SetFont('Times','B',11);
	$pdf->SetFillColor(235);
	$pdf->Cell($a,$alto,"T.O.T.A.L",1,0,'C',1);
	$pdf->Cell($e,$alto,($pendientes + $pagadas + $anuladas),1,0,'C',1);
	//$pdf->Cell($f,$alto,formato_moneda($asignacionese + $asignacionesp),1,0,'R',1);
	//$pdf->Cell($g,$alto,formato_moneda($descuentose + $descuentosp),1,0,'R',1);
	$pdf->Cell(0,$alto,formato_moneda($totale + $totalp),1,0,'R',1);
	$pdf->Ln($alto);
	}

//----------------- ORDENES SERVICIO
$consulta = "SELECT contribuyente.rif,	contribuyente.nombre,	orden_solicitudes.numero,	orden_solicitudes.fecha,	orden_solicitudes.asignaciones,	orden_solicitudes.descuentos,	orden_solicitudes.total,	orden_solicitudes.tipo_orden,	orden_solicitudes.estatus, orden_solicitudes.num_orden_pago FROM	orden_solicitudes,	contribuyente WHERE orden_solicitudes.id_contribuyente = contribuyente.id 	AND fecha >= '$fecha1' 	AND fecha <= '$fecha2' AND tipo_orden = 2 ORDER BY	numero ASC;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($tabla->num_rows>0)	
	{// ----------
	$pdf->AddPage();
	
	$pdf->SetFillColor(235);
	$alto = 6;
	$pdf->Ln(5);
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(0,5,'ORDENES DE SERVICIO',0,0,'C'); 
	$pdf->SetFont('Times','B',11);
	$pdf->Ln(10);
	$pdf->Cell($e=20,$alto,'#OP',1,0,'C',1);
	$pdf->Cell($a=20,$alto,'Rif',1,0,'C',1);
	$pdf->Cell($b=65,$alto,'Contribuyente',1,0,'C',1);
	$pdf->Cell($c=20,$alto,'Numero',1,0,'C',1);
	$pdf->Cell($d=18,$alto,'Fecha',1,0,'C',1);
	$pdf->Cell($h=20,$alto,'Estatus',1,0,'C',1);
//	$pdf->Cell($f=25,$alto,'Descuentos',1,0,'C',1);
	$pdf->Cell($g=0,$alto,'Total',1,0,'C',1);
	$pdf->Ln($alto);
	//----------
	$alto = 5;
	$pdf->SetFillColor(255);
	$pendientes =0; 
	$asignacionese=0;	
	$descuentose=0;	
	$totale=0;	
	$pagadas =0; 
	$asignacionesp=0;	
	$descuentosp=0;	
	$totalp=0;	
	$anuladas =0; 
	$asignacionesa=0;	
	$descuentosa=0;	
	$totala=0;	
	$i=1;
	while ($registro = $tabla->fetch_object())
		{
		$pdf->SetFont('Times','',9);
		$pdf->Cell($e,$alto,rellena_cero($registro->num_orden_pago,4),1,0,'C',1);
		$pdf->Cell($a,$alto,($registro->rif),1,0,'C',1);
		$pdf->Cell($b,$alto,$registro->nombre,1,0,'L',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($c,$alto,rellena_cero($registro->numero,4),1,0,'C',1);
		$pdf->SetFont('Times','',9);
		$pdf->Cell($d,$alto,voltea_fecha($registro->fecha),1,0,'C',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($h,$alto,estatus_op($registro->estatus),1,0,'C',1);
		$pdf->SetFont('Times','',9);
//		$pdf->Cell($f,$alto,formato_moneda($registro->descuentos),1,0,'R',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($g,$alto,formato_moneda($registro->total),1,0,'R',1);
		$pdf->Ln($alto);
		//-----------
		if ($registro->estatus<10)
			{	
			$pendientes += 1; 
			$asignacionese += $registro->asignaciones;	
			$descuentose += $registro->descuentos;	
			$totale += $registro->total;	
			}
		//-----------
		if ($registro->estatus>=10 and $registro->estatus<99)
			{	
			$pagadas += 1; 
			$asignacionesp += $registro->asignaciones;	
			$descuentosp += $registro->descuentos;	
			$totalp += $registro->total;	
			}		
		//-----------
		if ($registro->estatus==99)
			{	
			$anuladas += 1; 
			$asignacionesa += $registro->asignaciones;	
			$descuentosa += $registro->descuentos;	
			$totala += $registro->total;	
			}
		$i++;
		}
	$pdf->Ln($alto);
	$alto = 6;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell($a=120,$alto,"ORDEN(ES) DE SERVICIO",1,0,'C',1);
	$pdf->Cell($e=20,$alto,"CANT",1,0,'C',1);
//	$pdf->Cell($f=40,$alto,"MONTO",1,0,'C',1);
//	$pdf->Cell($g=40,$alto,"RETENCIONES",1,0,'C',1);
	$pdf->Cell(0,$alto,"TOTAL BS.",1,0,'C',1);
	$pdf->SetFillColor(255);
	$pdf->SetFont('Times','',10.5);
	$pdf->Ln($alto);
	if ($pendientes>0)
		{
		$pdf->Cell($a,$alto,"PENDIENTES",1,0,'C',1);
		$pdf->Cell($e,$alto,($pendientes),1,0,'C',1);
//		$pdf->Cell($f,$alto,formato_moneda($asignacionese),1,0,'R',1);
//		$pdf->Cell($g,$alto,formato_moneda($descuentose),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totale),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($pagadas>0)
		{
		$pdf->Cell($a,$alto,"PAGADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($pagadas),1,0,'C',1);
//		$pdf->Cell($f,$alto,formato_moneda($asignacionesp),1,0,'R',1);
//		$pdf->Cell($g,$alto,formato_moneda($descuentosp),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($totalp),1,0,'R',1);
		$pdf->Ln($alto);
		}
	if ($anuladas>0)
		{	
		$pdf->Cell($a,$alto,"ANULADAS",1,0,'C',1);
		$pdf->Cell($e,$alto,($anuladas),1,0,'C',1);
//		$pdf->Cell($f,$alto,formato_moneda(0),1,0,'R',1);
//		$pdf->Cell($g,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda(0),1,0,'R',1);
		$pdf->Ln($alto);
		}
	$pdf->SetFont('Times','B',11);
	$pdf->SetFillColor(235);
	$pdf->Cell($a,$alto,"T.O.T.A.L",1,0,'C',1);
	$pdf->Cell($e,$alto,($pendientes + $pagadas + $anuladas),1,0,'C',1);
	//$pdf->Cell($f,$alto,formato_moneda($asignacionese + $asignacionesp),1,0,'R',1);
	//$pdf->Cell($g,$alto,formato_moneda($descuentose + $descuentosp),1,0,'R',1);
	$pdf->Cell(0,$alto,formato_moneda($totale + $totalp),1,0,'R',1);
	$pdf->Ln($alto);
	}

//----------------- CTAS BANCARIAS
$consulta = "SELECT 	a_cuentas.banco, 	right(a_cuentas.cuenta,4) as cuenta, 	estado_cuenta.numero_orden, 	estado_cuenta.nombre_orden, 	estado_cuenta.concepto, 	estado_cuenta.referencia, 	estado_cuenta.debe, 	estado_cuenta.monto, 	estado_cuenta.fecha FROM 	a_cuentas	,	estado_cuenta WHERE 		a_cuentas.id = estado_cuenta.id_banco AND	estado_cuenta.fecha >= '$fecha1' AND	estado_cuenta.fecha <= '$fecha2' ORDER BY banco, cuenta, fecha, numero_orden";
//echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($tabla->num_rows>0)	
	{// ----------
	$pdf->AddPage();
	$total = 0;
	$pdf->SetFillColor(235);
	$alto = 6;
	$pdf->Ln(5);
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(0,5,'CUENTAS BANCARIAS AFECTADAS',0,0,'C'); 
	$pdf->SetFont('Times','B',11);
	$pdf->Ln(10);
	$pdf->Cell($a=30,$alto,'Banco',1,0,'C',1);
	$pdf->Cell($b=15,$alto,'Cuenta',1,0,'C',1);
	$pdf->Cell($e=12,$alto,'#OP',1,0,'C',1);
	$pdf->Cell($f=65,$alto,'Contribuyente',1,0,'C',1);
	$pdf->Cell($c=18,$alto,'Fecha',1,0,'C',1);
	$pdf->Cell($d=20,$alto,'Referencia',1,0,'C',1);
	//$pdf->Cell($h=20,$alto,'Monto',1,0,'C',1);
	$pdf->Cell($g=0,$alto,'Monto',1,0,'C',1);
	$pdf->Ln($alto);
	//----------
	$alto = 5;
	$pdf->SetFillColor(255);
	$i=0;
	while ($registro = $tabla->fetch_object())
		{
		$pdf->SetFont('Times','',9);
		$pdf->Cell($a,$alto,($registro->banco),1,0,'C',1);
		$pdf->Cell($b,$alto,($registro->cuenta),1,0,'C',1);
		$pdf->Cell($e,$alto,rellena_cero($registro->numero_orden,4),1,0,'C',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($f,$alto,($registro->nombre_orden),1,0,'L',1);
		$pdf->SetFont('Times','',9);
		$pdf->Cell($c,$alto,voltea_fecha($registro->fecha),1,0,'C',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($d,$alto,($registro->referencia),1,0,'C',1);
		$pdf->SetFont('Times','',9);
//		$pdf->Cell($f,$alto,formato_moneda($registro->descuentos),1,0,'R',1);
		$pdf->SetFont('Times','B',9);
		$pdf->Cell($g,$alto,formato_moneda($registro->debe),1,0,'R',1);
		$pdf->Ln($alto);
		//-----------
		$i++;
		}
	$pdf->Ln($alto);
	$alto = 6;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell($a=60,$alto,"BANCO",1,0,'C',1);
	$pdf->Cell($e=50,$alto,"CUENTA",1,0,'C',1);
	$pdf->Cell($f=30,$alto,"MOVIMIENTOS",1,0,'C',1);
//	$pdf->Cell($g=40,$alto,"RETENCIONES",1,0,'C',1);
	$pdf->Cell(0,$alto,"TOTAL BS.",1,0,'C',1);
	$pdf->SetFillColor(255);
	$pdf->SetFont('Times','',10.5);
	$pdf->Ln($alto);
	
	$consulta = "SELECT 	a_cuentas.banco, 	(a_cuentas.cuenta) as cuenta, 	SUM(estado_cuenta.debe) as monto, count(referencia) as cantidad FROM 	a_cuentas	,	estado_cuenta WHERE a_cuentas.id = estado_cuenta.id_banco AND	estado_cuenta.fecha >= '$fecha1' AND estado_cuenta.fecha <= '$fecha2' GROUP BY banco, cuenta ORDER BY banco, cuenta";
	//echo $consulta;
	$tabla = $_SESSION['conexionsql']->query($consulta);
	while ($registro = $tabla->fetch_object())
		{
		$pdf->Cell($a,$alto,($registro->banco),1,0,'C',1);
		$pdf->Cell($e,$alto,($registro->cuenta),1,0,'C',1);
		$pdf->Cell($f,$alto,($registro->cantidad),1,0,'C',1);
		$pdf->Cell(0,$alto,formato_moneda($registro->monto),1,0,'R',1);
		$pdf->Ln($alto);
		$total += ($registro->monto);
		}
	
	$pdf->SetFont('Times','B',11);
	$pdf->SetFillColor(235);
	$pdf->Cell($a+$e,$alto,"T.O.T.A.L",1,0,'C',1);
	$pdf->Cell($f,$alto,($i),1,0,'C',1);
	$pdf->Cell(0,$alto,formato_moneda($total),1,0,'R',1);
	$pdf->Ln($alto);
	}

$pdf->AddPage();

//----------------- RETENCIONES
$consulta = "SELECT ordenes_pago.estatus FROM ordenes_pago, ordenes_pago_descuentos WHERE ordenes_pago.id = ordenes_pago_descuentos.id_orden_pago AND fecha >= '$fecha1' AND fecha <= '$fecha2' AND tipo_solicitud <> 'FINANCIERA' AND tipo_solicitud <> 'NOMINA' GROUP BY ordenes_pago.estatus ORDER BY ordenes_pago.estatus;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($tabla->num_rows>0)	
	{
	// ----------
	//$pdf->AddPage();
	$pdf->SetFillColor(235);
	$alto = 6;
	$pdf->Ln(5);
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(0,5,'RETENCIONES ORDENES DE PAGO',0,0,'C'); 
	//----------
	$pdf->SetFillColor(255);
	$pdf->Ln(10);
	$alto = 6;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell($a=62,$alto,"ORDEN(ES) DE PAGO",1,0,'C',1);
	//$pdf->Cell($b=17,$alto,"CANT",1,0,'C',1);
	$pdf->Cell($c=35,$alto,"IVA",1,0,'C',1);
	$pdf->Cell($d=35,$alto,"ISLR",1,0,'C',1);
	$pdf->Cell($e=35,$alto,"TIMBRE",1,0,'C',1);
	$pdf->Cell(0,$alto,"TOTAL BS.",1,0,'C',1);
	$pdf->Ln($alto);
	$pdf->SetFillColor(255);
	$i=1;
	while ($registro = $tabla->fetch_object())
		{
		//--------- IVA
		$consultaX = "SELECT sum(ordenes_pago_descuentos.descuento) as monto FROM ordenes_pago, ordenes_pago_descuentos WHERE ordenes_pago.estatus = ".$registro->estatus." AND ordenes_pago_descuentos.id_descuento=7 AND ordenes_pago.id = ordenes_pago_descuentos.id_orden_pago AND fecha >= '$fecha1' AND fecha <= '$fecha2' AND tipo_solicitud <> 'FINANCIERA' AND tipo_solicitud <> 'NOMINA' GROUP BY ordenes_pago.estatus, id_descuento ORDER BY ordenes_pago.estatus, id_descuento ASC;"; 
		//echo $consulta;
		$tablaX = $_SESSION['conexionsql']->query($consultaX);
		$registroX1 = $tablaX->fetch_object();
		$iva = $registroX1->monto;
		//--------- ISLR
		$consultaX = "SELECT sum(ordenes_pago_descuentos.descuento) as monto FROM ordenes_pago, ordenes_pago_descuentos WHERE ordenes_pago.estatus = ".$registro->estatus." AND ordenes_pago_descuentos.id_descuento=6 AND ordenes_pago.id = ordenes_pago_descuentos.id_orden_pago AND fecha >= '$fecha1' AND fecha <= '$fecha2' AND tipo_solicitud <> 'FINANCIERA' AND tipo_solicitud <> 'NOMINA' GROUP BY ordenes_pago.estatus, id_descuento ORDER BY ordenes_pago.estatus, id_descuento ASC;"; 
		//echo $consulta;
		$tablaX = $_SESSION['conexionsql']->query($consultaX);
		$registroX2 = $tablaX->fetch_object();
		//--------- TIMBRE
		$consultaX = "SELECT sum(ordenes_pago_descuentos.descuento) as monto FROM ordenes_pago, ordenes_pago_descuentos WHERE ordenes_pago.estatus = ".$registro->estatus." AND ordenes_pago_descuentos.id_descuento=8 AND ordenes_pago.id = ordenes_pago_descuentos.id_orden_pago AND fecha >= '$fecha1' AND fecha <= '$fecha2' AND tipo_solicitud <> 'FINANCIERA' AND tipo_solicitud <> 'NOMINA' GROUP BY ordenes_pago.estatus, id_descuento ORDER BY ordenes_pago.estatus, id_descuento ASC;"; 
		//echo $consulta;
		$tablaX = $_SESSION['conexionsql']->query($consultaX);
		$registroX3 = $tablaX->fetch_object();
		
		$pdf->SetFont('Times','',9);
		$pdf->Cell($a,$alto,mayuscula(estatus_op($registro->estatus)),1,0,'C',1);
		//$pdf->Cell($b,$alto,$registro->estatus,1,0,'C',1);
		$pdf->Cell($c,$alto,formato_moneda($registroX1->monto),1,0,'R',1);
		$pdf->Cell($d,$alto,formato_moneda($registroX2->monto),1,0,'R',1);
		$pdf->Cell($e,$alto,formato_moneda($registroX3->monto),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($registroX1->monto+$registroX2->monto+$registroX3->monto),1,0,'R',1);
		$pdf->Ln($alto);
		$i++;
		}
	}

//----------------- PARAFISCALES
$consulta = "SELECT ordenes_pago.estatus FROM ordenes_pago,	nomina_descuentos,	nomina,	nomina_solicitudes WHERE ordenes_pago.id = nomina_solicitudes.id_orden_pago AND nomina_solicitudes.id = nomina.id_solicitud AND nomina.id = nomina_descuentos.id_nomina AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' GROUP BY	ordenes_pago.estatus ORDER BY ordenes_pago.estatus ASC;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($tabla->num_rows>0)	
	{
	// ----------
	//$pdf->AddPage();
	$pdf->SetFillColor(235);
	$alto = 6;
	$pdf->Ln(5);
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(0,5,'DESCUENTOS PARAFISCALES ORDENES DE PAGO',0,0,'C'); 
	//----------
	$pdf->SetFillColor(255);
	$pdf->Ln(10);
	$alto = 6;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell($a=30,$alto,"ORDEN(ES)",1,0,'C',1);
	$pdf->Cell($b=30,$alto,"SSO",1,0,'C',1);
	$pdf->Cell($c=30,$alto,"PF",1,0,'C',1);
	$pdf->Cell($d=30,$alto,"FAOV",1,0,'C',1);
	$pdf->Cell($e=30,$alto,"FEJ",1,0,'C',1);
	$pdf->Cell(0,$alto,"TOTAL BS.",1,0,'C',1);
	$pdf->Ln($alto);
	$pdf->SetFillColor(255);
	$i=1;
	while ($registro = $tabla->fetch_object())
		{
		//--------- 
		$consultaX = "SELECT	SUM(nomina_descuentos.descuento) AS monto FROM ordenes_pago, nomina_descuentos,	nomina,	nomina_solicitudes WHERE ordenes_pago.id = nomina_solicitudes.id_orden_pago AND	nomina_solicitudes.id = nomina.id_solicitud AND	nomina.id = nomina_descuentos.id_nomina AND	ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' AND ordenes_pago.estatus = ".$registro->estatus." AND	nomina_descuentos.id_descuento = 1 GROUP BY	ordenes_pago.estatus;"; //echo $consulta;
		//echo $consulta;
		$tablaX = $_SESSION['conexionsql']->query($consultaX);
		$registroX1 = $tablaX->fetch_object();
		//$sso = $registroX1->monto;
		//--------- 
		$consultaX = "SELECT SUM(nomina_descuentos.descuento) AS monto FROM ordenes_pago,	nomina_descuentos,	nomina,	nomina_solicitudes WHERE ordenes_pago.id = nomina_solicitudes.id_orden_pago AND nomina_solicitudes.id = nomina.id_solicitud AND nomina.id = nomina_descuentos.id_nomina AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' AND	ordenes_pago.estatus = ".$registro->estatus." AND nomina_descuentos.id_descuento = 2 GROUP BY	ordenes_pago.estatus ORDER BY ordenes_pago.estatus ASC;"; //echo $consulta;
		//echo $consulta;
		$tablaX = $_SESSION['conexionsql']->query($consultaX);
		$registroX2 = $tablaX->fetch_object();
		//--------- 
		$consultaX = "SELECT SUM(nomina_descuentos.descuento) AS monto FROM ordenes_pago,	nomina_descuentos,	nomina,	nomina_solicitudes WHERE ordenes_pago.id = nomina_solicitudes.id_orden_pago AND nomina_solicitudes.id = nomina.id_solicitud AND nomina.id = nomina_descuentos.id_nomina AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' AND	ordenes_pago.estatus = ".$registro->estatus." AND nomina_descuentos.id_descuento = 3 GROUP BY	ordenes_pago.estatus ORDER BY ordenes_pago.estatus ASC;"; //echo $consulta;
		//echo $consulta;
		$tablaX = $_SESSION['conexionsql']->query($consultaX);
		$registroX3 = $tablaX->fetch_object();
		//--------- 
		$consultaX = "SELECT SUM(nomina_descuentos.descuento) AS monto FROM ordenes_pago,	nomina_descuentos,	nomina,	nomina_solicitudes WHERE ordenes_pago.id = nomina_solicitudes.id_orden_pago AND nomina_solicitudes.id = nomina.id_solicitud AND nomina.id = nomina_descuentos.id_nomina AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' AND	ordenes_pago.estatus = ".$registro->estatus." AND nomina_descuentos.id_descuento = 4 GROUP BY	ordenes_pago.estatus ORDER BY ordenes_pago.estatus ASC;"; //echo $consulta;
		//echo $consulta;
		$tablaX = $_SESSION['conexionsql']->query($consultaX);
		$registroX4 = $tablaX->fetch_object();
		//--------- 
		$pdf->SetFont('Times','',9);
		$pdf->Cell($a,$alto,mayuscula(estatus_op($registro->estatus)),1,0,'C',1);
		$pdf->Cell($b,$alto,formato_moneda($registroX1->monto),1,0,'C',1);
		$pdf->Cell($c,$alto,formato_moneda($registroX2->monto),1,0,'R',1);
		$pdf->Cell($d,$alto,formato_moneda($registroX3->monto),1,0,'R',1);
		$pdf->Cell($e,$alto,formato_moneda($registroX4->monto),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($registroX1->monto+$registroX2->monto+$registroX3->monto+$registroX4->monto),1,0,'R',1);
		$pdf->Ln($alto);
		$i++;
		}
	}

//----------------- PARTIDAS
$consulta = "SELECT
	todo.*,
	original,
	modificado,
	disponible 
FROM
	(
	SELECT
		categoria,
		a_categoria.descripcion AS descripcionc,
		LEFT ( partida, 3 ) AS partida,
		SUM( monto ) AS causado,
		SUM( monto2 ) AS pagado 
	FROM
		(
			(
			SELECT
				categoria,
				partida,
				SUM( monto ) AS monto,
				SUM( monto2 ) AS monto2 
			FROM
				(
				SELECT
					nomina_asignaciones.categoria,
					nomina_asignaciones.partida,
					SUM( nomina_asignaciones.asignaciones ) AS monto,
					0 AS monto2 
				FROM
					ordenes_pago,
					nomina_asignaciones,
					nomina,
					nomina_solicitudes 
				WHERE
					ordenes_pago.id = nomina_solicitudes.id_orden_pago 
					AND nomina_solicitudes.id = nomina.id_solicitud 
					AND nomina.id = nomina_asignaciones.id_nomina 
					AND ordenes_pago.fecha >= '$fecha1' 
					AND ordenes_pago.fecha <= '$fecha2' AND ordenes_pago.estatus >= 0 
					AND ordenes_pago.estatus < 10 
				GROUP BY
					nomina_asignaciones.categoria,
					nomina_asignaciones.partida UNION
				SELECT
					orden.categoria,
					orden.partida,
					SUM( orden.total ) AS monto,
					0 
				FROM
					ordenes_pago,
					orden,
					orden_solicitudes 
				WHERE
					ordenes_pago.id = orden_solicitudes.id_orden_pago 
					AND orden_solicitudes.id = orden.id_solicitud 
					AND ordenes_pago.fecha >= '$fecha1' 
					AND ordenes_pago.fecha <= '$fecha2' AND ordenes_pago.estatus >= 0 
					AND ordenes_pago.estatus < 10 
				GROUP BY
					orden.categoria,
					orden.partida 
				) AS causado 
			GROUP BY
				categoria,
				partida 
			) UNION
			(
			SELECT
				categoria,
				partida,
				SUM( monto ) AS monto,
				SUM( monto2 ) AS monto2 
			FROM
				(
				SELECT
					nomina_asignaciones.categoria,
					nomina_asignaciones.partida,
					0 AS monto,
					SUM( nomina_asignaciones.asignaciones ) AS monto2 
				FROM
					ordenes_pago,
					nomina_asignaciones,
					nomina,
					nomina_solicitudes 
				WHERE
					ordenes_pago.id = nomina_solicitudes.id_orden_pago 
					AND nomina_solicitudes.id = nomina.id_solicitud 
					AND nomina.id = nomina_asignaciones.id_nomina 
					AND ordenes_pago.fecha >= '$fecha1' 
					AND ordenes_pago.fecha <= '$fecha2' AND ordenes_pago.estatus >= 10 
					AND ordenes_pago.estatus < 99 
				GROUP BY
					nomina_asignaciones.categoria,
					nomina_asignaciones.partida UNION
				SELECT
					orden.categoria,
					orden.partida,
					0,
					SUM( orden.total ) AS monto 
				FROM
					ordenes_pago,
					orden,
					orden_solicitudes 
				WHERE
					ordenes_pago.id = orden_solicitudes.id_orden_pago 
					AND orden_solicitudes.id = orden.id_solicitud 
					AND ordenes_pago.fecha >= '$fecha1' 
					AND ordenes_pago.fecha <= '$fecha2' AND ordenes_pago.estatus >= 10 
					AND ordenes_pago.estatus < 99 
				GROUP BY
					orden.categoria,
					orden.partida 
				) AS pagado 
			GROUP BY
				categoria,
				partida 
			) 
		) AS lista,
		a_categoria 
	WHERE
		lista.categoria = a_categoria.codigo 
	GROUP BY
		categoria,
	LEFT ( partida, 3 )) AS todo,
	(
	SELECT
		categoria,
		LEFT ( codigo, 3 ) AS partida,
		SUM( original ) AS original,
		SUM( modificado ) AS modificado,
		SUM( disponible ) AS disponible 
	FROM
		`a_presupuesto_2021` 
	GROUP BY
		categoria,
	LEFT ( codigo, 3 )) AS presupuesto 
WHERE
	todo.categoria = presupuesto.categoria 
	AND todo.partida = presupuesto.partida;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($tabla->num_rows>0)	
	{
	// ----------
	$pdf->AddPage();
	$pdf->SetFillColor(235);
	$alto = 6;
	$pdf->Ln(5);
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(0,5,'PARTIDAS AFECTADAS',0,0,'C'); 
	//----------
	$pdf->Ln(10);
	$alto = 6;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell($a=22,$alto,"CODIGO",1,0,'C',1);
	$pdf->Cell($b=19,$alto,"PARTIDA",1,0,'C',1);
	$pdf->Cell($c=33,$alto,"MODIFICADO",1,0,'C',1);
	$pdf->Cell($d=30,$alto,"CAUSADO",1,0,'C',1);
	$pdf->Cell($e=30,$alto,"PAGADO",1,0,'C',1);
	$pdf->Cell($d,$alto,"PENDIENTE",1,0,'C',1);
	$pdf->Cell(0,$alto,"DISPONIBLE",1,0,'C',1);
	$pdf->Ln($alto);
	$pdf->SetFillColor(255);
	$alto = 5;
	$pdf->SetFont('Times','',8.5);
	//--------- 
	$categoria = '';
	while ($registro = $tabla->fetch_object())
		{
//		if ($pdf->GetY()>=249)
//			{
//			$alto = 6;
//			$pdf->SetFillColor(235);
//			$pdf->SetFont('Times','B',10);
//			$pdf->Cell($a=22,$alto,"CODIGO",1,0,'C',1);
//			$pdf->Cell($b=19,$alto,"PARTIDA",1,0,'C',1);
//			$pdf->Cell($c=33,$alto,"MODIFICADO",1,0,'C',1);
//			$pdf->Cell($d=30,$alto,"CAUSADO",1,0,'C',1);
//			$pdf->Cell($e=30,$alto,"PAGADO",1,0,'C',1);
//			$pdf->Cell($d,$alto,"PENDIENTE",1,0,'C',1);
//			$pdf->Cell(0,$alto,"DISPONIBLE",1,0,'C',1);
//			$pdf->Ln($alto);
//			$pdf->SetFillColor(255);
//			$alto = 5;
//			$pdf->SetFont('Times','',8.5);
//			}
		if ($categoria<>$registro->descripcionc) 
			{
			$pdf->SetFillColor(235);
			$pdf->Cell(0,$alto,($registro->descripcionc),1,0,'L',1);
			$pdf->Ln($alto);
			$categoria = $registro->descripcionc;
			$pdf->SetFillColor(255);
			}
		$pdf->Cell($a,$alto,($registro->categoria),1,0,'C',1);//$pdf->GetY().' '.
		$pdf->Cell($b,$alto,($registro->partida),1,0,'C',1);
		$pdf->Cell($c,$alto,formato_moneda($registro->modificado),1,0,'R',1);
		$pdf->Cell($d,$alto,formato_moneda($registro->causado+$registro->pagado),1,0,'R',1);
		$pdf->Cell($e,$alto,formato_moneda($registro->pagado),1,0,'R',1);
		$pdf->Cell($d,$alto,formato_moneda($registro->causado),1,0,'R',1);
		$pdf->Cell(0,$alto,formato_moneda($registro->disponible),1,0,'R',1);
		$pdf->Ln($alto);
		$modificado += $registro->modificado;
		$causado += $registro->causado;
		$pagado += $registro->pagado;
		$disponible += $registro->disponible;
		$i++;
		}
	}

$alto +=2;
$pdf->SetFillColor(235);
$pdf->SetFont('Times','B',10);
$pdf->Cell($a+$b,$alto*2,('T.O.T.A.L'),1,0,'C',1);
$pdf->Cell($c,$alto,"MODIFICADO",1,0,'C',1);
$pdf->Cell($d,$alto,"CAUSADO",1,0,'C',1);
$pdf->Cell($e,$alto,"PAGADO",1,0,'C',1);
$pdf->Cell($d,$alto,"PENDIENTE",1,0,'C',1);
$pdf->Cell(0,$alto,"DISPONIBLE",1,0,'C',1);
$pdf->Ln($alto);
$pdf->SetFillColor(255);

$pdf->SetFont('Times','B',8.5);
$pdf->Cell($a+$b,$alto,'',0,0,'C',0);
$pdf->Cell($c,$alto,formato_moneda($modificado),1,0,'R',1);
$pdf->Cell($d,$alto,formato_moneda($causado+$pagado),1,0,'R',1);
$pdf->Cell($e,$alto,formato_moneda($pagado),1,0,'R',1);
$pdf->Cell($d,$alto,formato_moneda($causado),1,0,'R',1);
$pdf->Cell(0,$alto,formato_moneda($disponible),1,0,'R',1);

$pdf->Output();
?>