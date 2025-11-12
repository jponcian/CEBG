<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
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
		$this->Cell(0,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
	}	
}

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(12,15,12);
$pdf->SetAutoPageBreak(1,17);
$pdf->SetTitle('Listado Pagos Nomina');

// ----------
$pdf->AddPage();
$pdf->SetFillColor(2, 117, 216);
$pdf->Image('../../images/logo_nuevo.jpg',35,10,38);
$pdf->Image('../../images/escudo.jpg',210,12,26);
//$pdf->Image('../../images/logo_web.png',100,80,100);
$pdf->SetFont('Times','',11);

// ---------------------
//$pdf->SetY(12);
//$instituto = instituto();
$pdf->SetFont('Times','I',11.5);
$pdf->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); 
$pdf->Ln(10);

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,5,'RELACIÓN DE PAGOS RRHH',0,1,'C'); 
$pdf->Cell(0,5,$_SESSION['titulo'],0,0,'C'); 
$pdf->Ln(7);

$pdf->SetTextColor(255);
$pdf->SetFont('Times','B',10.5);
$pdf->Cell($aa=5,7,'#',1,0,'C',1);
//$pdf->Cell($a=12,7,'Orden',1,0,'C',1);
$pdf->Cell($b=70,7,'Descripcion',1,0,'C',1);
//$pdf->Cell($c=48,7,'Contribuyente',1,0,'C',1);
$pdf->Cell($d=16,7,'Fecha',1,0,'C',1);
$pdf->Cell($f=26.5,7,'Total Orden',1,0,'C',1);
$pdf->Cell($f,7,'Total Pagado',1,0,'C',1);
$pdf->Cell($ff=18.5,7,'SSO',1,0,'C',1);
$pdf->Cell($ff,7,'PF',1,0,'C',1);
$pdf->Cell($ff,7,'LPH',1,0,'C',1);
$pdf->Cell($ff,7,'FEJ',1,0,'C',1);
$pdf->Cell($e=0,7,'Total Descuento',1,1,'C',1);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i=0;
//-----------------
$consulta = "SELECT a_tipo_nomina.pago_nomina, nomina_solicitudes.fecha, nomina_solicitudes.desde, nomina_solicitudes.hasta, SUM(nomina.descuentos) as descuentos, SUM(nomina.asignaciones) as asignaciones, SUM(nomina.total) as total, SUM(nomina.sso) as sso, sum(nomina.fp) as fp, sum(nomina.lph) as lph, sum(nomina.fej) as fej  FROM a_tipo_nomina, nomina_solicitudes, nomina WHERE  nomina_solicitudes.estatus>0 and nomina_solicitudes.estatus<99 AND a_tipo_nomina.cod_nomina=nomina_solicitudes.tipo_pago AND nomina_solicitudes.desde>='$fecha1' and nomina_solicitudes.desde<='$fecha2' and nomina_solicitudes.descuentos>0 and nomina_solicitudes.id = nomina.id_solicitud GROUP BY a_tipo_nomina.pago_nomina, nomina_solicitudes.desde, nomina_solicitudes.hasta;";
//$consulta = "SELECT a_tipo_nomina.pago_nomina, nomina_solicitudes.desde, nomina_solicitudes.hasta,	SUM(nomina.sso) as sso,	sum(nomina.fp) as fp, sum(nomina.lph) as lph, sum(nomina.fej) as fej , ordenes_pago.* FROM a_tipo_nomina, ordenes_pago,	nomina_solicitudes,	nomina WHERE a_tipo_nomina.cod_nomina=nomina_solicitudes.tipo_pago AND ordenes_pago.fecha>='$fecha1' and ordenes_pago.fecha<='$fecha2' and nomina_solicitudes.descuentos>0 and nomina_solicitudes.id = nomina.id_solicitud AND ordenes_pago.id = nomina_solicitudes.id_orden_pago GROUP BY ordenes_pago.id ORDER BY ordenes_pago.numero";
$tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
	{
	$pdf->SetFont('Times','',8.5);
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(235);}
	//----------
	$pdf->Cell($aa,5.5,$i+1,1,0,'C',1);
	//$pdf->Cell($a,5.5,$registro->numero,1,0,'C',1);
	$pdf->SetFont('Times','',7);
	$pdf->Cell($b+$a,5.5,substr($registro->pago_nomina.' ('.voltea_fecha($registro->desde).' al '.voltea_fecha($registro->hasta).')',0,50),1,0,'L',1);
	$pdf->SetFont('Times','',8.5);
	$pdf->Cell($d,5.5,voltea_fecha($registro->fecha),1,0,'C',1);
	$pdf->Cell($f,5.5,formato_moneda($registro->asignaciones),1,0,'R',1);
	$pdf->Cell($f,5.5,formato_moneda($registro->total),1,0,'R',1);
	$pdf->Cell($ff,5.5,formato_moneda($registro->sso),1,0,'R',1);
	$pdf->Cell($ff,5.5,formato_moneda($registro->fp),1,0,'R',1);
	$pdf->Cell($ff,5.5,formato_moneda($registro->lph),1,0,'R',1);
	$pdf->Cell($ff,5.5,formato_moneda($registro->fej),1,0,'R',1);
	$pdf->Cell($e,5.5,formato_moneda($registro->descuentos),1,0,'R',1);//$total

	$pdf->Ln(5.5);
	if ($registro->estatus<>99)	
		{
		$total += $registro->asignaciones;
		$pagado += $registro->total;
		$sso += $registro->sso;
		$fp += $registro->fp;
		$lph += $registro->lph;
		$fej += $registro->fej;
		$descuentos += $registro->descuentos;
		}
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',9.5);
$pdf->SetFillColor(230);
$pdf->Cell($aa+$a+$b+$c+$d,7,'TOTALES => ',1,0,'R',1);
$pdf->Cell($f,7,formato_moneda($total),1,0,'R',1);
$pdf->Cell($f,7,formato_moneda($pagado),1,0,'R',1);
$pdf->Cell($ff,7,formato_moneda($sso),1,0,'R',1);
$pdf->Cell($ff,7,formato_moneda($fp),1,0,'R',1);
$pdf->Cell($ff,7,formato_moneda($lph),1,0,'R',1);
$pdf->Cell($ff,7,formato_moneda($fej),1,0,'R',1);
$pdf->SetFont('Times','B',11);
$pdf->Cell(0,7,formato_moneda($descuentos),1,0,'R',1);

$pdf->Output();
?>