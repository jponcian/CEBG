<?php
session_start();
ob_end_clean();
session_start();

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}

include "../../conexion.php";
include('../../funciones/auxiliar_php.php');
require('../../lib/fpdf/fpdf.php');

class PDF extends FPDF
	{
	function Footer()
		{
		//Posici�n a 1,5 cm del final
		//$this->SetY(-15);
		//Arial it�lica 8
		//$this->SetFont('Times','I',9);
		//Color del texto en gris
		//$this->SetTextColor(120);
		//N�mero de p�gina
		//$this->Cell(0,0,'CEBG',0,0,'R');
		}	
	}

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(6,10,6);
$pdf->SetDisplayMode($zoom=='real');
$pdf->SetAutoPageBreak(1, $margin=10);
$pdf->SetTitle('Etiquetas QR');

$_SESSION['id_dependencia'] = decriptar($_GET['division']);

//--- COMIENZO DEL MEMO
$pdf->AddPage();
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$y = 10;
$x = 13;

$consulta_div = "SELECT bn_bienes.*, division, codigo FROM bn_bienes, bn_dependencias WHERE bn_bienes.id_dependencia = bn_dependencias.id AND bn_bienes.id_dependencia = ".$_SESSION['id_dependencia'].";"; 
$tabla_div = $_SESSION['conexionsql']->query($consulta_div);
while ($registro_div = $tabla_div->fetch_object())
	{
	if ($x>200)	{$x=13;$y += 33; }
	if ($y>180)	{$x=13;$y = 10; $pdf->AddPage(); }
	//-------------
	$pdf->Image('../../images/logo_nuevo.jpg',$x,$y,22);

	if ($_SERVER['HTTP_HOST']=='localhost')
		{$pdf->Image("http://localhost/cebg/scripts/qr_generador.php?code=".$registro_div->numero_bien,$x+53.5,$y-1,26,26,"png");}
	else
		{$pdf->Image("http://app.cebg.com.ve/scripts/qr_generador.php?code=".$registro_div->numero_bien,$x+53.5,$y-1,26,26,"png");}
	//-----------
	$pdf->SetFont('Times','',7);
	$pdf->SetXY($x+56,$y-1);
	$pdf->Cell(21,5,date('d/m/Y'),0,0,'C');
	$pdf->SetFont('Times','',5);
	$pdf->SetXY($x+15.5,$y);
	$pdf->Cell(46,5,('REPUBLICA BOLIVARINA DE VENEZUELA'),0,0,'C');
	$pdf->SetXY($x+15.5,$y+3);
	$pdf->Cell(46,5,('CONTRALORIA DEL ESTADO BOLIVARIANO'),0,0,'C');
	$pdf->SetXY($x+15.5,$y+5);
	$pdf->Cell(46,5,('DE GUARICO'),0,0,'C');
	$pdf->SetXY($x+15.5,$y+8);
	$pdf->Cell(46,5,('DIRECCION DE ADMINISTRACION Y'),0,0,'C');
	$pdf->SetXY($x+15.5,$y+10);
	$pdf->Cell(46,5,('PRESUPUESTO'),0,0,'C');
	//$pdf->Cell(50,5,($registro_div->division),0,0,'C');
	$pdf->SetXY($x+15.5,$y+17);
	$pdf->SetFont('Times','',20);
	$pdf->Cell(46,5,$registro_div->numero_bien,0,0,'C');//.' - '.$registro_div->codigo
	$pdf->SetXY($x+1,$y+22);
	$pdf->SetFont('Times','',6);
//	$pdf->Cell(76,5,($registro_div->division),0,0,'C');
	$pdf->SetXY($x+1,$y-2);
	$pdf->Cell(76,29,'',1,0,'C');
	//-----------
	$x += 85;
	}
$pdf->Output();
?>