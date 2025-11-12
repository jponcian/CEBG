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
$pdf->SetTitle('Listado Ordenes de Pagos');

// ----------
$pdf->AddPage();
$pdf->SetFillColor(2, 117, 216);
$pdf->Image('../../images/logo_nuevo.jpg',157,10,38);
$pdf->Image('../../images/escudo.jpg',20,12,26);
//$pdf->Image('../../images/logo_web.png',100,80,100);
$pdf->SetFont('Times','',11);

// ---------------------
//$pdf->SetY(12);
$pdf->SetFont('Times','I',11.5);
$pdf->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); 
$pdf->Ln(10);

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,5,$_SESSION['titulo'],0,0,'C'); 
$pdf->Ln(10);

$pdf->SetTextColor(255);
$pdf->SetFont('Times','B',10.5);
$pdf->Cell($aa=9,7,'Item',1,0,'C',1);
$pdf->Cell($a=15,7,'Decreto',1,0,'C',1);
$pdf->Cell($b=17,7,'Fecha',1,0,'C',1);
$pdf->Cell($c=110,7,'Concepto',1,0,'C',1);
$pdf->Cell($d=0,7,'Monto',1,1,'C',1);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i=0;
//-----------------
$tabla = $_SESSION['conexionsql']->query($_SESSION['consulta']);
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
	{
	$pdf->SetFont('Times','',8.5);
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(235);}
	//----------
	$pdf->SetX(56);
	$y1=$pdf->GetY();
	$pdf->SetFont('Times','',7.5);
	$pdf->MultiCell($c,5.5,($registro->concepto),1,'J');
	$pdf->SetFont('Times','',8.5);
	$y2=$pdf->GetY();
	$pdf->SetY($y1);
	$pdf->Cell($aa,$y2-$y1,$i+1,1,0,'C');
	$pdf->Cell($a,$y2-$y1,rellena_cero($registro->numero,8),1,0,'C');
	$pdf->Cell($b,$y2-$y1,voltea_fecha($registro->fecha),1,0,'C');
	$pdf->Cell($c,$y2-$y1,'',1,0,'C');
	$pdf->Cell(0,$y2-$y1,formato_moneda($registro->total1),1,0,'R',1);
	$pdf->Ln($y2-$y1);

	$monto = $monto + $registro->total1;
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',12);
$pdf->SetFillColor(230);
$pdf->Cell(0,6,'TOTAL => '.formato_moneda($monto),1,0,'R',1);

$pdf->Output();
?>