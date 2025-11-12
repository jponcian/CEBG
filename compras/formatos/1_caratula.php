<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Header()
	{}	
	
	function Footer()
	{}	
}

$id = decriptar($_GET['id']);
$aprobado = ($_GET['p']);
//-------------	

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(30,26,30);
$pdf->SetAutoPageBreak(1,13);
$pdf->SetTitle('Caratula');

// ----------
$pdf->AddPage();

if ($aprobado==0)
	{$consultx = "SELECT	presupuesto.*, contribuyente.nombre FROM contribuyente, presupuesto WHERE presupuesto.estatus=0 AND id_contribuyente = $id AND presupuesto.id_contribuyente = contribuyente.id LIMIT 1;";}
else
	{$consultx = "SELECT	presupuesto.*, contribuyente.nombre FROM contribuyente, presupuesto WHERE id_solicitud = $id AND presupuesto.id_contribuyente = contribuyente.id LIMIT 1;";}
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------
$anno = $registro->anno;
$numero = rellena_cero($registro->numero,3);
$tipo = $registro->tipo_orden;
$fecha_presupuesto = voltea_fecha($registro->fecha_presupuesto);
$fecha_orden = voltea_fecha($registro->fecha_orden);
$concepto = $registro->concepto;
$punto_cuenta = $registro->punto_cuenta;
//--------------

$pdf->SetFillColor(240);
$pdf->Image('../../images/logo_nuevo.jpg',27,19,32);
$pdf->SetFont('Times','',11);
// ---------------------

$pdf->SetFont('Times','B',21);
$pdf->Cell(30,5,''); 
$pdf->Cell(0,10,'EXPEDIENTE Nº CEBG-'."$tipo-$numero-$anno",1,0,'C'); 
$pdf->Ln(10);

$pdf->SetFont('Times','B',15);
$pdf->Cell(30,5,''); 
$pdf->Cell(0,10,'PUNTO DE CUENTA N° '.$punto_cuenta,1,0,'C'); 
$pdf->Ln(16);

$pdf->SetFont('Times','B',16);
$pdf->Cell(0,10,'NOMBRE DE LA DEPENDENCIA:',1,0,'C'); 
$pdf->Ln(10);

$pdf->SetFont('Times','B',16);
$pdf->Cell(0,10,'DIRECCIÓN DE ADMINISTRACIÓN Y PRESUPUESTO',1,0,'C'); 
$pdf->Ln(16);
//-------------
//$y=$pdf->GetY();
//$pdf->Cell(150,5,'');
//$pdf->SetY($y);
//-------------
$pdf->SetFont('Times','B',14);
$pdf->MultiCell(0,7,'"'.$concepto.'"',1); 		
$pdf->Ln(7);

$pdf->SetFont('Times','',10);
$pdf->Cell(60,6,'FECHA DE:',1,0,'C',0);
$pdf->Cell(60,6,'ELABORADO POR:',1,0,'C',0);
$pdf->Cell(0,6,'REFERENCIA:',1,0,'C',0);
$pdf->Ln(6);

$pdf->Cell(30,6,'COMIENZO',1,0,'C',0);
$pdf->Cell(30,6,'TERMINACION',1,0,'C',0);
$pdf->Cell(60,25,'',1,0,'C',0);
$pdf->Cell(0,25,'',1,0,'C',0);
$pdf->Ln(6);

$pdf->Cell(30,19,$fecha_presupuesto,1,0,'C',0);
$pdf->Cell(30,19,$fecha_orden,1,0,'C',0);

$pdf->Output();
?>