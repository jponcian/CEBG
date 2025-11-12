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

$direccion = decriptar($_GET['direccion']);
if ($direccion==0)	{ $filtro = '';}
	else { $filtro = ' AND bn_solicitudes.division='.$direccion;}

$tipo = decriptar($_GET['tipo']);
if ($tipo==0)	{ $filtro2 = '';}
	elseif ($tipo==5) { $filtro2 = ' AND bn_materiales.bien=1';}
	elseif ($tipo==6) { $filtro2 = ' AND bn_materiales.bien=0';}

class PDF extends FPDF
{
  function Header()
  {
	$desde = ($_GET['desde']);
	$hasta = ($_GET['hasta']);
$aa=10;
$b=70;
$c=20;
$d=20;
$e=80;
$f=25;
$g=0;

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
  $this->Ln(13);
  
  $this->SetFont('Times','B',11);
  $this->Cell(0,5,'SALIDA DE MATERIALES Y SUMINISTROS POR DIRECCION',0,1,'C'); 
  $this->Cell(0,5,"DESDE EL $desde HASTA EL $hasta",0,0,'C'); 
  $this->Ln(7);
  
  $this->SetTextColor(255);
  $this->SetFont('Times','B',10.5);
  $this->Cell($aa=10,7,'Item',1,0,'C',1);
  $this->Cell($b,7,'Direccion',1,0,'C',1);
  $this->Cell($c,7,'Solicitud',1,0,'C',1);
  $this->Cell($d,7,'Fecha',1,0,'C',1);
  $this->Cell($e,7,'Descripcion',1,0,'C',1);
  $this->Cell($f,7,'Solicitado',1,0,'C',1);
  $this->Cell($g,7,'Aprobado',1,1,'C',1);
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
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,15,17);
$pdf->SetAutoPageBreak(1,23);
$pdf->SetTitle('Relacion de Salidas');

// ----------
$pdf->AddPage();

$aa=10;
$b=70;
$c=20;
$d=20;
$e=80;
$f=25;
$g=0;

$pdf->SetFont('Times','',9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i=0;
$nomina = '';
$ubicacion = '';

//-----------------
$consultx = "SELECT a_direcciones.direccion, bn_solicitudes.anno, bn_solicitudes.numero, bn_solicitudes.fecha, bn_materiales.descripcion_bien, 	bn_solicitudes_detalle.cantidad, bn_solicitudes_detalle.cant_aprobada, bn_solicitudes.estatus FROM bn_solicitudes, a_direcciones, bn_solicitudes_detalle, bn_materiales WHERE bn_solicitudes_detalle.id_bien = bn_materiales.id_bien AND bn_solicitudes.division = a_direcciones.id	AND bn_solicitudes.id = bn_solicitudes_detalle.id_solicitud AND bn_solicitudes.fecha >= '$desde' AND bn_solicitudes.fecha <= '$hasta' $filtro $filtro2 ORDER BY bn_solicitudes.id DESC;";
$tabla = $_SESSION['conexionsql']->query($consultx);   
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
  {
  //----------
  if ($i%2==0)  {$pdf->SetFillColor(255);} else {$pdf->SetFillColor(250);}
  //----------
	$pdf->SetFont('Times','',9);
	$pdf->Cell($aa,5.5,$i+1,1,0,'C',1);
	$pdf->SetFont('Times','',8);
	$pdf->Cell($b,5.5,substr($registro->direccion,0,50),1,0,'L',1);
	$pdf->SetFont('Times','',9);
	$pdf->Cell($c,5.5,rellena_cero($registro->numero,5),1,0,'C',1);
	$pdf->Cell($d,5.5,voltea_fecha($registro->fecha),1,0,'C',1);
	$pdf->SetFont('Times','',8);
	$pdf->Cell($e,5.5,substr($registro->descripcion_bien,0,50),1,0,'L',1);
	$pdf->SetFont('Times','',9);
	$pdf->Cell($f,5.5,formato_moneda($registro->cantidad),1,0,'R',1);
	$pdf->Cell($g,5.5,formato_moneda($registro->cant_aprobada),1,0,'R',1);

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