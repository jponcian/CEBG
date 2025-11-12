<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once('../../funciones/auxiliar_php.php');
require_once ('../../lib/fpdf/fpdf.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Header()
	{
	$this->SetFillColor(2, 117, 216);
	$this->Image('../../images/logo_nuevo.jpg',20,10,30);
//	$this->Image('../../images/escudo.jpg',165,12,28);
	//$this->Image('../../images/logo_web.png',100,80,100);
	$this->SetFont('Times','',11);
	
	// ---------------------
	//$this->SetY(12);
	$this->SetFont('Times','I',11.5);
	$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Rif G-20001287-0',0,0,'C'); 
	$this->Ln(8);
	
	$this->SetFont('Times','B',11);
	$this->Cell(0,5,'LIBRO DE BANCO',0,1,'C'); 
	$this->SetFont('Times','B',11);$this->Cell(0,5,$_SESSION['titulo'],0,0,'C'); 
	$this->Ln(7);
	
	$this->SetTextColor(255);
	$this->SetFont('Times','B',10);
	//$this->Cell($aa=9,7,'Item',1,0,'C',1);
	$this->Cell($_SESSION['a']=18,7,'Fecha',1,0,'C',1);
	$this->Cell($_SESSION['b']=25,7,'Referencia',1,0,'C',1);
	$this->Cell($_SESSION['c']=55,7,'Beneficiario',1,0,'C',1);
	$this->Cell($_SESSION['d']=95,7,'Concepto',1,0,'C',1);
	$this->Cell($_SESSION['e']=22,7,'Debe',1,0,'C',1);
	$this->Cell($_SESSION['f']=22,7,'Haber',1,0,'C',1);
	$this->Cell(0,7,'Saldo',1,1,'C',1);
	}
	
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-15);
		$this->SetTextColor(120);
		//$this->Cell(0,5,'Resolución '.($_GET['id']));
		//--------------
			$this->Cell(30,10,'Fecha: '.date('d/m/Y h:i a'),0,0,'L');$s=$this->PageNo();
		while ($s>5)
		{	$s=$s-5;	}
		$this->Cell(0,0,'SIGEM'.' '.$this->PageNo().' de {nb}',0,0,'R');
	}	
}

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(10,15,10);
$pdf->SetAutoPageBreak(1,15);
$pdf->SetTitle('Libro de Banco');

// ----------
$pdf->AddPage();

$pdf->SetFont('Times','',9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i=0;
$nomina = '';
$ubicacion = '';
//------ SALDO INICIAL
$tablx = $_SESSION['conexionsql']->query($_SESSION['saldo']);
$registro = $tablx->fetch_object();
$saldo = $registro->saldo;
//-----------------
$tabla = $_SESSION['conexionsql']->query($_SESSION['consulta']);
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
	{
	$saldo += $registro->debe; 
	$saldo -= $registro->haber; 
	$debe += $registro->debe; 
	$haber += $registro->haber; 
	//----------
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(235);}
	//----------
	$pdf->SetFont('Times','',9);
	$pdf->Cell($_SESSION['a'],5.5,voltea_fecha($registro->fecha),1,0,'C',1);
	$pdf->Cell($_SESSION['b'],5.5,($registro->referencia),1,0,'C',1);
	$pdf->SetFont('Times','',8);
	$pdf->Cell($_SESSION['c'],5.5,($registro->nombre_orden),1,0,'L',1);
	$pdf->Cell($_SESSION['d'],5.5,($registro->concepto),1,0,'L',1);//$registro->referencia
	$pdf->SetFont('Times','',9);
	$pdf->Cell($_SESSION['e'],5.5,formato_moneda($registro->debe),1,0,'R',1);
	$pdf->Cell($_SESSION['f'],5.5,formato_moneda($registro->haber),1,0,'R',1);
	$pdf->Cell(0,5.5,formato_moneda($saldo),1,0,'R',1);

	$pdf->Ln(5.5);
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',9.5);
$pdf->SetFillColor(230);
$pdf->Cell($_SESSION['a']+$_SESSION['b']+$_SESSION['c']+$_SESSION['d'],6,'TOTAL => ',1,0,'R',1);
$pdf->Cell($_SESSION['f'],6,formato_moneda($debe),1,0,'R',1);
$pdf->Cell($_SESSION['e'],6,formato_moneda($haber),1,0,'R',1);
$pdf->Cell(0,6,$saldo,1,0,'R',1);

$pdf->Output();
?>