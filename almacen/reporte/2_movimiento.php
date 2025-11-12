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
 
$desde = voltea_fecha($_GET['desde']);
$hasta = voltea_fecha($_GET['hasta']);
$tipo = decriptar($_GET['tipo']);
$direccion = decriptar($_GET['direccion']);

class PDF extends FPDF
{
  function Header()
  {
  $this->SetFillColor(2, 117, 216);
  $this->Image('../../images/logo_nuevo.jpg',30,10,32);
  //$this->Image('../../images/escudo.jpg',30,12,28);
  //$this->Image('../../images/logo_web.png',100,80,100);
  $this->SetFont('Times','',11);
  
  // ---------------------
  //$this->SetY(12);
  //$instituto = instituto();
  $this->SetFont('Times','I',11.5);
  $this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
  $this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
  $this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
  $this->Cell(0,5,'Rif G-20001287-0',0,0,'C'); 
  $this->Ln(8);
  
  $this->SetFont('Times','B',11);
  $this->Cell(0,5,'RELACIÓN DE MOVIMIENTO DE MATERIALES',0,0,'C'); 
  $this->Ln(7);
  
  $this->SetTextColor(255);
  $this->SetFont('Times','B',10.5);
  $this->Cell($aa=10,7,'Item',1,0,'C',1);
  $this->Cell($b=110,7,'Descripcion',1,0,'C',1);
  $this->Cell($c=20,7,'Medida',1,0,'C',1);
  $this->Cell($d=20,7,'Ingresos',1,0,'C',1);
  $this->Cell($e=0,7,'Salidas',1,1,'C',1);
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
 {  $s=$s-5;  }
 $this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
  }  
}

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,15,17);
$pdf->SetAutoPageBreak(1,23);
$pdf->SetTitle('Relacion de Ingresos y Salidas');

// ----------
$pdf->AddPage();

$aa=10;
$b=110;
$c=20;
$d=20;
$e=0;

$pdf->SetFont('Times','',9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i=0;
$nomina = '';
$ubicacion = '';
//-----------------
$consultx = "DROP TABLE IF EXISTS ingresos;";
$tablx = $_SESSION['conexionsql']->query($consultx);  
//-----------------
$consultx = "CREATE TEMPORARY TABLE ingresos ( SELECT id_bien, sum(cantidad) as cantidad, fecha FROM bn_ingresos_detalle WHERE estatus = 10 AND fecha >= '$desde' AND fecha <= '$hasta' GROUP BY id_bien );"; 
$tablx = $_SESSION['conexionsql']->query($consultx);  
//-----------------
$consultx = "DROP TABLE IF EXISTS ingresos_listo;";
$tablx = $_SESSION['conexionsql']->query($consultx);  
//-----------------
$consultx = "CREATE TEMPORARY TABLE ingresos_listo (  SELECT bn_materiales.id_bien,  bn_materiales.unidad, bn_materiales.descripcion_bien, ingresos.cantidad AS ingreso,  ingresos.fecha   FROM bn_materiales  LEFT JOIN ingresos ON ingresos.id_bien = bn_materiales.id_bien  );";
$tablx = $_SESSION['conexionsql']->query($consultx);  
//-----------------
$consultx = "DROP TABLE IF EXISTS salidas;";
$tablx = $_SESSION['conexionsql']->query($consultx);  
//-----------------
$consultx = "CREATE TEMPORARY TABLE salidas ( SELECT * FROM bn_solicitudes_detalle WHERE estatus = 10 AND fecha >= '$desde' AND fecha <= '$hasta' );";
$tablx = $_SESSION['conexionsql']->query($consultx);  
//-----------------
$consultx = "DROP TABLE IF  EXISTS salidas_listo;";
$tablx = $_SESSION['conexionsql']->query($consultx);  
//-----------------
$consultx = "CREATE TEMPORARY TABLE salidas_listo ( SELECT bn_materiales.id_bien, bn_materiales.unidad,
 bn_materiales.descripcion_bien, salidas.cant_aprobada AS salida, salidas.fecha  FROM bn_materiales
 LEFT JOIN salidas ON salidas.id_bien = bn_materiales.id_bien  );";
$tablx = $_SESSION['conexionsql']->query($consultx);  
//-----------------
$consultx = "SELECT  ingresos_listo.* , salida FROM ingresos_listo, salidas_listo WHERE ingresos_listo.id_bien = salidas_listo.id_bien;";
$tabla = $_SESSION['conexionsql']->query($consultx);   
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
  {
	if ($registro->ingreso>0 or $registro->salida>0)
	  {//----------
	  if ($i%2==0)  {$pdf->SetFillColor(255);} else {$pdf->SetFillColor(250);}
	  //----------
	  $pdf->SetFont('Times','',9);
	  $pdf->Cell($aa,5.5,$i+1,1,0,'C',1);
	  $pdf->SetFont('Times','',8);
	  $pdf->Cell($b,5.5,substr($registro->descripcion_bien,0,50),1,0,'L',1);
	  $pdf->SetFont('Times','',8);
	  $pdf->Cell($c,5.5,$registro->unidad,1,0,'L',1);
	  $pdf->SetFont('Times','',9);
	  $pdf->Cell($d,5.5,formato_moneda($registro->ingreso),1,0,'R',1);
	  $pdf->Cell($e,5.5,formato_moneda($registro->salida),1,0,'R',1);

	  $pdf->Ln(5.5);
	  $monto = $monto + $registro->sueldo;
	  //-----------
	  $i++;}
  }

$pdf->SetFont('Times','B',12);
$pdf->SetFillColor(230);
//$pdf->Cell($aa+$a+$b+$c+$d+$d1,6,'TOTAL =>',1,0,'R',1);
//$pdf->Cell(0,6,'TOTAL => '.formato_moneda($monto),1,0,'R',1);

$pdf->Output();
?>