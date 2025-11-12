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
	function Header()
	{
		$this->SetFillColor(2, 117, 216);
		$this->Image('../../images/escudo.jpg',40,12,26);
		if (anno($fecha)<2024)
		{$this->Image('../../images/logo_2023.jpg',200,10,30);}
		else
		{$this->Image('../../images/logo_nuevo.jpg',200,10,38);}
		//$this->Image('../../images/logo_web.png',100,80,100);
		$this->SetFont('Times','',11);

		// ---------------------
		//$this->SetY(12);
		//$instituto = instituto();
		$this->SetFont('Times','I',11.5);
		$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); 
		$this->Ln(10);

		$this->SetFont('Times','B',11);
		$this->Cell(0,5,'RELACIÓN DE COMPROBANTES DE PAGO',0,0,'C'); 
		$this->Ln(5);
		$this->Cell(0,5,$_SESSION['titulo'],0,0,'C'); 
		$this->Ln(7);

		$this->SetTextColor(255);
		$this->SetFont('Times','B',10.5);
		$this->Cell($_SESSION['aa']=9,7,'Item',1,0,'C',1);
		$this->Cell($_SESSION['a']=13,7,'Num',1,0,'C',1);
		$this->Cell($_SESSION['d']=18,7,'Fecha',1,0,'C',1);
		$this->Cell($_SESSION['b']=18,7,'Rif',1,0,'C',1);
		$this->Cell($_SESSION['c']=60,7,'Contribuyente',1,0,'C',1);
		$this->Cell($_SESSION['k']=28,7,'Banco',1,0,'C',1);
		$this->Cell($_SESSION['f']=22,7,'Fecha Pago',1,0,'C',1);
		$this->Cell($_SESSION['h']=22,7,'N° Ref',1,0,'C',1);
		$this->Cell($_SESSION['g']=28,7,'Total',1,0,'C',1);
		$this->Cell($_SESSION['e']=0,7,'Neto',1,1,'C',1);
	}
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
		$this->Cell(0,0,$_SESSION['CEDULA_USUARIO'].' '.date('c'),0,0,'L');//d/m/Y h:i:s a 
	}	
}

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(15,15,15);
$pdf->SetAutoPageBreak(1,17);
$pdf->SetTitle('Listado Comprobantes de Pagos');

// ----------
$pdf->AddPage();

$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i=0;
//-----------------
$tabla = $_SESSION['conexionsql']->query($_SESSION['consulta2']);
//-----------------
$i=0; $monto=0;	$monto_banco=0;	$banco='';
while ($registro = $tabla->fetch_object())
	{
	//----------
	if ($i>0 and $banco<>$registro->banco)	
		{
		$pdf->SetFont('Times','B',11);
		$pdf->SetFillColor(230);
		$pdf->Cell(0,6,"SUB-TOTAL $banco => ".formato_moneda($monto_banco),1,0,'R',1);
		$pdf->Ln(6);
		$banco=$registro->banco;
		$monto_banco=0;
		}
	$pdf->SetFont('Times','',8.5);
	//----------
	if ($i==0)		{$banco=$registro->banco;}
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(235);}
	if ($registro->estatus==99)	{ $total='A.N.U.L.A.D.A'; } else { $total=formato_moneda($registro->total); }
	//----------
	$pdf->Cell($_SESSION['aa'],5.5,$i+1,1,0,'C',1);
	$pdf->Cell($_SESSION['a'],5.5,$registro->num_comprobante,1,0,'C',1);
	$pdf->Cell($_SESSION['d'],5.5,voltea_fecha($registro->fecha),1,0,'C',1);
	$pdf->Cell($_SESSION['b'],5.5,$registro->rif,1,0,'C',1);
	$pdf->SetFont('Times','',7);
	$pdf->Cell($_SESSION['c'],5.5,substr($registro->nombre,0,50),1,0,'L',1);
	$pdf->SetFont('Times','',8.5);
	$pdf->Cell($_SESSION['k'],5.5,($registro->banco),1,0,'L',1);
	$pdf->Cell($_SESSION['h'],5.5,voltea_fecha($registro->fecha_pago),1,0,'C',1);
	$pdf->Cell($_SESSION['f'],5.5,($_SESSION['tipo_pago'][$registro->tipo_pago]).$registro->num_pago,1,0,'R',1);
	$pdf->Cell($_SESSION['g'],5.5,formato_moneda($registro->asignaciones),1,0,'R',1);
	$pdf->Cell($_SESSION['e'],5.5,($total),1,0,'R',1);

	$pdf->Ln(5.5);
	$monto = $monto + $registro->total;
	$monto_banco = $monto_banco + $registro->total;
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',11);
$pdf->SetFillColor(230);
$pdf->Cell(0,6,"SUB-TOTAL $banco => ".formato_moneda($monto_banco),1,0,'R',1);
$pdf->Ln(6);
$pdf->SetFont('Times','B',12);
$pdf->SetFillColor(230);
$pdf->Cell(0,6,'TOTAL PAGADO => '.formato_moneda($monto),1,0,'R',1);

$pdf->Output();
?>