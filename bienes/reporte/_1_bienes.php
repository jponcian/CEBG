<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'utf8'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Header()
	{
	$this->SetFillColor(2, 117, 216);
	$this->Image('../../images/logo_nuevo.jpg',30,10,42);
	////$this->Image('../../images/escudo.jpg',30,12,28);
	//$this->Image('../../images/logo_web.png',100,80,100);
	$this->SetFont('Times','',11);
	
	// ---------------------
	//$this->SetY(12);
	//$instituto = instituto();
	$this->SetFont('Times','I',11.5);
	$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Dirección de Bienes, Materiales, Suministros y Archivo',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Rif G-20001287-0',0,0,'C'); 
	$this->Ln(8);
	
	$this->SetFont('Times','B',11);
	$this->Cell(0,5,'RELACIÓN DE BIENES EN SISTEMA',0,0,'C'); 
	$this->Ln(7);
	
	$this->SetTextColor(255);
	$this->SetFont('Times','B',10.5);
	$this->Cell($aa=10,7,'Item',1,0,'C',1);
	$this->Cell($a=60,7,'Direccion',1,0,'C',1);
	$this->Cell($b=20,7,'N° Bien',1,0,'C',1);
	$this->Cell($c=110,7,'Descripcion',1,0,'C',1);
	$this->Cell($d=15,7,'Estado',1,0,'C',1);
	$this->Cell($e=0,7,'Valor',1,1,'C',1);
	}
	
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-18);
		$this->SetTextColor(120);
		//$this->Cell(0,5,'Resolución '.($_GET['id']));
		//--------------
		$s=$this->PageNo();
		while ($s>5)
		{	$s=$s-5;	}
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
	}	
}

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,15,17);
$pdf->SetAutoPageBreak(1,23);
$pdf->SetTitle('Bienes en Sistema');

// ----------
$pdf->AddPage();

$aa=10;
$a=60;
$b=20;
$c=110;
$d=15;
$e=0;

$pdf->SetFont('Times','',9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i=0;
$nomina = '';
$ubicacion = '';
//-----------------
$tabla = $_SESSION['conexionsql']->query($_SESSION['consulta']);
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
	{
	//----------
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(250);}
	//----------
	$pdf->SetFont('Times','',9);
	$pdf->Cell($aa,5.5,$i+1,1,0,'C',1);
	$pdf->SetFont('Times','',8);
	$pdf->Cell($a,5.5,$registro->division,1,0,'L',1);
	$pdf->SetFont('Times','',9);
	$pdf->Cell($b,5.5,substr($registro->numero_bien,0,50),1,0,'C',1);
	$pdf->SetFont('Times','',8);
	$pdf->Cell($c,5.5,$registro->descripcion_bien,1,0,'L',1);
	$pdf->SetFont('Times','',9);
	$pdf->Cell($d,5.5,$registro->conservacion,1,0,'C',1);
	$pdf->Cell($e,5.5,formato_moneda($registro->valor),1,0,'R',1);

	$pdf->Ln(5.5);
	$monto = $monto + $registro->sueldo;
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',12);
$pdf->SetFillColor(230);
//$pdf->Cell($aa+$a+$b+$c+$d+$d1,6,'TOTAL =>',1,0,'R',1);
//$pdf->Cell(0,6,'TOTAL => '.formato_moneda($monto),1,0,'R',1);

$pdf->Output();
?>