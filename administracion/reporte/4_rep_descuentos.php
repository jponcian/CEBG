<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
//$_SESSION['conexionsql']->mysql_query("SET NAMES 'utf8'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Header()
	{
	$fecha = ($_GET['fecha1']);
	$fechaf = ($_GET['fecha2']);
	$fecha1 = voltea_fecha($_GET['fecha1']);
	$fecha2 = voltea_fecha($_GET['fecha2']);
	$tipo = ($_GET['tipo']);
	
	$consulta = "SELECT * FROM a_retenciones WHERE id = $tipo;";
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$registro = $tabla->fetch_object();
	$rif_beneficiario = $registro->rif_beneficiario;
	$beneficiario = $registro->beneficiario;
	$titulo = $registro->decripcion;
	
	$this->SetFillColor(230);
	$this->Image('../../images/logo_nuevo.jpg',20,10,35);
	$this->Image('../../images/escudo.jpg',164,10,26);
	//$this->Image('../../images/logo_web.png',100,80,100);
	$this->SetFont('Times','',11);
	
	// ---------------------
	//$instituto = instituto();
	$this->SetFont('Times','I',11.5);
	$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Rif G-20001287-0 - Ejercicio Fiscal '.anno($fecha1),0,0,'C');
	$this->Ln(10);
	
	//$this->SetFont('Times','',12);
	//$this->Cell(0,5,'Relación de Planillas en Sistema por concepto de:',0,0,'C'); $this->Ln(6);
	$this->SetFont('Times','B',12);
	$this->Cell(0,5,'Descuentos por: '.$titulo,0,0,'C'); $this->Ln(6);
	$this->Cell(0,5,'del '.($fecha).' al '.($fechaf),0,0,'C'); 
	$this->Ln(10);
	
	$this->SetFont('Times','B',10);
	$this->Cell($_SESSION['aa']=10,7,'Item',1,0,'C',1);
	$this->Cell($_SESSION['a']=17,7,'N° Orden',1,0,'C',1);
	$this->Cell($_SESSION['c']=100,7,'Descripcion',1,0,'C',1);
	$this->Cell($_SESSION['b']=23,7,'Fecha',1,0,'C',1);
	$this->Cell($_SESSION['d']=0,7,'Monto',1,1,'C',1);
	$this->SetFont('Times','',9);
	}	

	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-13);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
	}	
}

$fecha = ($_GET['fecha1']);
$fechaf = ($_GET['fecha2']);
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
$tipo = ($_GET['tipo']);

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,17,17);
$pdf->SetAutoPageBreak(1,23);
$pdf->SetTitle('Retenciones Realizadas');
$pdf->SetFillColor(230);

// ----------
$pdf->AddPage();
//-----------------
$consulta = "SELECT nomina_solicitudes.id_orden_pago, nomina_solicitudes.fecha, nomina_solicitudes.desde, nomina_solicitudes.hasta, SUM( nomina_descuentos.descuento ) AS monto, nomina_solicitudes.tipo_pago, nomina_solicitudes.tipo_pago, a_tipo_nomina.pago_nomina FROM	nomina,	nomina_solicitudes,	nomina_descuentos, a_tipo_nomina WHERE a_tipo_nomina.cod_nomina = nomina_solicitudes.tipo_pago AND nomina.id_solicitud = nomina_solicitudes.id AND nomina.id = nomina_descuentos.id_nomina AND ( nomina_solicitudes.desde BETWEEN '$fecha1' AND '$fecha2' ) AND nomina_descuentos.id_descuento = $tipo GROUP BY nomina_solicitudes.tipo_pago, nomina_solicitudes.desde, nomina_solicitudes.hasta ORDER BY nomina_solicitudes.desde, nomina_solicitudes.tipo_pago;";
//echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
	{
	//----------	
	$pdf->SetX($_SESSION['aa']+$_SESSION['a']+17);
	$x=$pdf->GetX();
	$y1=$pdf->GetY();
	$pdf->SetFont('Times','',10);
	$pdf->MultiCell($_SESSION['c'],6,$registro->pago_nomina.' '.voltea_fecha($registro->desde).' al '.voltea_fecha($registro->hasta),1,'L');
	$pdf->SetFont('Times','',12);
	$y2=$pdf->GetY();
	$pdf->SetY($y1);
	$pdf->Cell($_SESSION['aa'],$y2-$y1,$i+1,1,0,'C',0);
	$pdf->SetFont('Times','B',12);
	$pdf->Cell($_SESSION['a'],$y2-$y1,rellena_cero(num_orden($registro->id_orden_pago),6),1,0,'C',0);
	$pdf->SetFont('Times','',12);
	$pdf->SetX($x+$_SESSION['c']);
	$pdf->Cell($_SESSION['b'],$y2-$y1,voltea_fecha($registro->fecha),1,0,'C');
	//$pdf->Cell($_SESSION['d'],$y2-$y1,formato_moneda($registro->monto));
	$pdf->Cell(0,$y2-$y1,formato_moneda($registro->monto),1,0,'R',1);
	$pdf->Ln($y2-$y1);
	
	$monto = $monto + $registro->monto;
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',12);
//$pdf->SetFillColor(230);
$pdf->Cell($e,7,'Total Bs => '.formato_moneda($monto),1,1,'R',1);

$pdf->Output();
?>