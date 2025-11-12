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
$pdf=new PDF('L','mm','termica');
$pdf->AliasNbPages();
$pdf->SetMargins(6,1,6);
$pdf->SetDisplayMode($zoom=='real');
$pdf->SetAutoPageBreak(1, $margin=1);
$pdf->SetTitle('Etiquetas QR');

$_SESSION['id_dependencia'] = decriptar($_GET['division']);

//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$y = 10;
$x = 13;

$consulta_div = "SELECT bn_bienes.*, division, codigo FROM bn_bienes, bn_dependencias WHERE bn_bienes.id_dependencia = bn_dependencias.id AND bn_bienes.id_dependencia = ".$_SESSION['id_dependencia'].";"; 
$tabla_div = $_SESSION['conexionsql']->query($consulta_div);
while ($registro_div = $tabla_div->fetch_object())
	{
	$pdf->AddPage();
	//-------------
//	$pdf->Image('../../images/logo_nuevo.jpg',4,19,15);

	if ($_SERVER['HTTP_HOST']=='localhost')
		{$pdf->Image("http://localhost/cebg/scripts/qr_generador.php?code=".$registro_div->numero_bien,19,1,25,25,"png");}
	else
		{$pdf->Image("http://app.cebg.com.ve/scripts/qr_generador.php?code=".$registro_div->numero_bien,19,1,25,25,"png");}
	//-----------
	$pdf->Image('../../images/logo_nuevo_mejor.jpg',2,3,18);
//	$pdf->SetFont('Times','',5);
//	$pdf->Cell(0,5,('REPUBLICA BOLIVARIANA DE'),0,0,'C');
//	$pdf->Ln(2);
//	$pdf->Cell(0,5,('VENEZUELA'),0,0,'C');
//	$pdf->Ln(3);
//	$pdf->SetFont('Times','',7);
//	$pdf->Cell(0,5,date('d/m/Y'),0,0,'R');
	$pdf->Ln(24);
	$pdf->SetFont('Times','',17);
	$pdf->Cell(0,5,$registro_div->numero_bien,0,0,'C');
	//-----------
	}
$pdf->Output();
?>