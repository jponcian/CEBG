<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once('../../lib/fpdf/fpdf.php');
//date_default_timezone_set('America/Caracas');

//setlocale(LC_TIME, 'sp_ES','sp', 'es');
//$_SESSION['conexionsql']->mysql_query("SET NAMES 'utf8'");

//if ($_SESSION['VERIFICADO'] != "SI") { 
//    header ("Location: ../index.php?errorusuario=val"); 
//    exit(); 
//	}

class PDF extends FPDF
{
  function Header()
  {
    $fecha = ($_GET['fecha1']);
    $fechaf = ($_GET['fecha2']);
    $fecha1 = voltea_fecha($_GET['fecha1']);
    $fecha2 = voltea_fecha($_GET['fecha2']);
    $dependencia = decriptar($_GET['division']);

    $this->SetFillColor(230);
    $this->Image('../../images/logo_nuevo.jpg', 20, 15, 37);
    //	$this->Image('../../images/admon.png',158,18,35);
    $this->SetFont('Times', '', 11);

    // ---------------------
    //$instituto = instituto();
    $this->SetY(20);
    $this->SetFont('Times', 'I', 11.5);
    $this->Cell(0, 5, utf8_decode('República Bolivariana de Venezuela'), 0, 0, 'C');
    $this->Ln(5);
    $this->Cell(0, 5, utf8_decode('Contraloría del Estado Bolivariano de Guárico'), 0, 0, 'C');
    $this->Ln(5);
    $this->Cell(0, 5, utf8_decode('Dirección de Bienes, Materiales, Suministros y Archivo'), 0, 0, 'C');
    $this->Ln(5);
    $this->Cell(0, 5, 'Rif G-20001287-0', 0, 0, 'C');
    $this->Ln(8);

    $this->SetFont('Times', 'B', 12);
    $this->Cell(0, 5, utf8_decode('Incorporaciones de Bienes del ' . ($fecha) . ' al ' . ($fechaf)), 0, 0, 'C');
    if ($dependencia > 0) {
      $this->Ln(6);
      $dependencia = division_bienes($dependencia);
      $this->Cell(0, 5, utf8_decode($dependencia[0]), 0, 0, 'C');
    }
    $this->Ln(10);
  }

  function Footer()
  {
    $this->SetFont('Times', 'I', 8);
    $this->SetY(-13);
    $this->SetTextColor(120);
    //--------------
    $this->Cell(100, 10, 'Impreso: ' . $_SESSION['CEDULA_USUARIO'] . ' ' . date('d/m/Y h:m'), 0, 0, 'L');
    $this->Cell(0, 10, 'SIACEBG ' . $this->PageNo() . ' de {nb}', 0, 0, 'R');
  }
}

$fecha = ($_GET['fecha1']);
$fechaf = ($_GET['fecha2']);
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
$dependencia = decriptar($_GET['division']);

// ENCABEZADO
$pdf = new PDF('P', 'mm', 'LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17, 12, 17);
$pdf->SetAutoPageBreak(1, 15);
$pdf->SetTitle('Incorporaciones Realizadas');
$pdf->SetFillColor(230);

$pdf->AddPage();
$pdf->SetFont('Times', 'B', 11);

// ----------
$pdf->Cell(20, 7, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(85, 7, 'Descripcion', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Cod. Inv.', 1, 0, 'C', 1);
$pdf->Cell(28, 7, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(0, 7, 'Precio Total', 1, 0, 'C', 1);
$pdf->Ln(7);
// ----------
if ($dependencia > 0) {
  $dependencia = ' AND id_dependencia=' . $dependencia;
}
$consulta1 = "SELECT * FROM bn_bienes WHERE fecha_adquisicion >= '$fecha1' AND fecha_adquisicion <= '$fecha2' $dependencia ORDER BY fecha_adquisicion";
//echo $consulta1;
$tabla1 = $_SESSION['conexionsql']->query($consulta1);
//-----------------
$pdf->SetFont('Times', '', 8);
$i = 0;

while ($registro1 = $tabla1->fetch_object()) {
  $i++;
  $total += $registro1->valor;
  // ----------
  if ($pdf->GetY() > 245) {
    $pdf->AddPage();
  }
  //----- PARA ARRANCAR CON LA LINEA
  $y1 = $pdf->GetY();
  $x = $pdf->GetX();
  $pdf->SetX(37);
  //-----------------------------------------MULTICELL
  $pdf->MultiCell(85, 5, (($registro1->descripcion_bien)), 1, 'J');
  $y2 = $pdf->GetY();
  //- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
  $pdf->SetY($y1);
  $pdf->SetX($x);
  $alto2 = $y2 - $y1;
  //-------------------
  // ----------
  $pdf->Cell(20, $alto2, voltea_fecha($registro1->fecha_adquisicion), 1, 0, 'C', 0);
  $pdf->Cell(85, $alto2, '');
  $pdf->Cell(20, $alto2, $registro1->numero_bien, 1, 0, 'C', 0);
  $pdf->Cell(28, $alto2, "1", 1, 0, 'C', 0);
  $pdf->Cell(0, $alto2, formato_moneda($registro1->valor), 1, 0, 'R', 0);
  // ----------
  $pdf->Ln($alto2);
}

if ($i == 0) {
  $pdf->SetFont('Times', 'B', 12);
  $pdf->Cell(0, 10, 'NO HUBO', 1, 0, 'C', 0);
  // ----------
  $pdf->Ln();
}

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(40, 7, '');
$pdf->Cell(65, 7, 'Cant. Bienes Incorporados => ', 1, 0, 'R', 1);
$pdf->Cell(20, 7, $i, 1, 0, 'C', 1);
$pdf->Cell(0, 7, 'Total Bs => ' . formato_moneda($total), 1, 1, 'R', 1);

//$firma1 = firma(14);
//
//$pdf->SetFont('Times','B',10);
//$pdf->SetY(-45);
//$pdf->Cell(70,6,'ELABORADO POR:',1,1,'L',1);
//$pdf->Cell(70,6,$firma1[1],0,1,'L',0);
//$pdf->Cell(70,6,$firma1[2],0,1,'L',0);

$pdf->Output();
