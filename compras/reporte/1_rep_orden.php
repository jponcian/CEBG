<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-15);
		$this->SetTextColor(120);
		//$this->Cell(0,5,'Resolución '.($_GET['id']));
		//--------------
		$s=$this->PageNo();
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
		$this->SetY(-15);
		$this->Cell(0,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
	}	
}

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(15,15,15);
$pdf->SetAutoPageBreak(1,17);
$pdf->SetTitle('Listado Ordenes de Compra y/o Servicio');
//$instituto = instituto();

//-----------------
$tabla = $_SESSION['conexionsql']->query($_SESSION['consulta1']);
// ----------
if ($tabla->num_rows>0)	{
$pdf->AddPage();
$pdf->SetFillColor(2, 117, 216);
$pdf->Image('../../images/logo_nuevo.jpg',157,10,42);
$pdf->Image('../../images/escudo.jpg',20,12,28);
//$pdf->Image('../../images/logo_web.png',100,80,100);
$pdf->SetFont('Times','',11);

// ---------------------
//$instituto = instituto();
$pdf->SetFont('Times','I',11.5);
$pdf->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C');
$pdf->Ln(10);

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,5,'RELACIÓN DE ORDENES DE COMPRA',0,0,'C'); 
$pdf->Ln(9);

$pdf->SetTextColor(255);
$pdf->SetFont('Times','B',10.5);
$pdf->Cell($aa=9,7,'Item',1,0,'C',1);
$pdf->Cell($a=16,7,'Orden',1,0,'C',1);
$pdf->Cell($a,7,'OrdenP',1,0,'C',1);
$pdf->Cell($b=18,7,'Rif',1,0,'C',1);
$pdf->Cell($c=74,7,'Contribuyente',1,0,'C',1);
$pdf->Cell($d=18,7,'Fecha',1,0,'C',1);
$pdf->Cell($e=0,7,'Total',1,1,'C',1);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
	{
	$pdf->SetFont('Times','',8.5);
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(235);}
	//----------
	$pdf->Cell($aa,5.5,$i+1,1,0,'C',1);
	$pdf->Cell($a,5.5,($_SESSION['tipo_orden2'][$registro->tipo_orden]).rellena_cero($registro->numero,8),1,0,'C',1);
	$pdf->Cell($a,5.5,rellena_cero($registro->num_orden_pago,8),1,0,'C',1);
	$pdf->Cell($b,5.5,$registro->rif,1,0,'C',1);
	$pdf->SetFont('Times','',7);
	$pdf->Cell($c,5.5,substr($registro->nombre,0,50),1,0,'L',1);
	$pdf->SetFont('Times','',8.5);
	$pdf->Cell($d,5.5,voltea_fecha($registro->fecha),1,0,'C',1);
	$pdf->Cell($e,5.5,formato_moneda($registro->total1),1,0,'R',1);

	$pdf->Ln(5.5);
	$monto = $monto + $registro->total1;
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',12);
$pdf->SetFillColor(230);
$pdf->Cell(0,6,'TOTAL => '.formato_moneda($monto),1,0,'R',1);

}
//-----------------
$tabla = $_SESSION['conexionsql']->query($_SESSION['consulta2']);
// ----------
if ($tabla->num_rows>0)	{
$pdf->AddPage();
$pdf->SetFillColor(2, 117, 216);
$pdf->Image('../../images/logo_nuevo.jpg',157,10,42);
$pdf->Image('../../images/escudo.jpg',20,12,28);
//$pdf->Image('../../images/logo_web.png',100,80,100);
$pdf->SetFont('Times','',11);

// ---------------------
//$instituto = instituto();
$pdf->SetFont('Times','I',11.5);
$pdf->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); 
$pdf->Ln(10);

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,5,'RELACIÓN DE ORDENES DE SERVICIO',0,0,'C'); 
$pdf->Ln(9);

$pdf->SetTextColor(255);
$pdf->SetFont('Times','B',10.5);
$pdf->Cell($aa=9,7,'Item',1,0,'C',1);
$pdf->Cell($a=16,7,'Orden',1,0,'C',1);
$pdf->Cell($a,7,'OrdenP',1,0,'C',1);
$pdf->Cell($b=18,7,'Rif',1,0,'C',1);
$pdf->Cell($c=74,7,'Contribuyente',1,0,'C',1);
$pdf->Cell($d=18,7,'Fecha',1,0,'C',1);
$pdf->Cell($e=0,7,'Total',1,1,'C',1);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
	{
	$pdf->SetFont('Times','',8.5);
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(235);}
	//----------
	$pdf->Cell($aa,5.5,$i+1,1,0,'C',1);
	$pdf->Cell($a,5.5,($_SESSION['tipo_orden2'][$registro->tipo_orden]).rellena_cero($registro->numero,8),1,0,'C',1);
	$pdf->Cell($a,5.5,rellena_cero($registro->num_orden_pago,8),1,0,'C',1);
	$pdf->Cell($b,5.5,$registro->rif,1,0,'C',1);
	$pdf->SetFont('Times','',7);
	$pdf->Cell($c,5.5,substr($registro->nombre,0,50),1,0,'L',1);
	$pdf->SetFont('Times','',8.5);
	$pdf->Cell($d,5.5,voltea_fecha($registro->fecha),1,0,'C',1);
	$pdf->Cell($e,5.5,formato_moneda($registro->total1),1,0,'R',1);

	$pdf->Ln(5.5);
	$monto = $monto + $registro->total1;
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',12);
$pdf->SetFillColor(230);
$pdf->Cell(0,6,'TOTAL => '.formato_moneda($monto),1,0,'R',1);

}

$pdf->Output();
?>