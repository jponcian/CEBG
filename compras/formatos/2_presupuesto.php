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
$pdf->SetMargins(20,50,20);
$pdf->SetAutoPageBreak(1,13);
$pdf->SetTitle('Presupuesto');

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
$memo = $registro->memo;
$punto_cuenta = $registro->punto_cuenta;
$oficina = info_area($registro->oficina);
$id_area = ($registro->oficina);
//--------------

$pdf->SetFillColor(240);
$pdf->Image('../../images/logo_nuevo.jpg',27,19,32);
$pdf->SetFont('Times','',11);
// ---------------------

$pdf->SetFont('Times','B',15);
$pdf->Cell(0,10,"PRESUPUESTO BASE",0,0,'C'); 
$pdf->Ln(7);

$pdf->SetFont('Times','B',14);
$pdf->Cell(0,10,"$fecha_presupuesto",0,0,'R'); 
$pdf->Ln(13);

$pdf->SetFont('Times','',12);
$pdf->MultiCell(0,7,"De acuerdo a lo establecido en el artículo 59 de la Ley de Contrataciones Públicas, a continuación detallo el presupuesto base en ocasión al requerimiento realizado en  el memorando N° $memo por la oficina de ".$oficina[2]."."); 		
$pdf->Ln(7);

$pdf->SetFont('Times','B',10);
$y=$pdf->GetY();
$pdf->Cell($a=6,12,'N°',1,0,'C',0);
$pdf->Cell($b=59,12,'DESCRIPCIÓN',1,0,'C',0);
$x=$pdf->GetX();
$pdf->Multicell($c=22,6,'UNIDAD DE MEDIDA',1,'C',0);
$pdf->SetY($y);
$pdf->SetX($x+$c);
$pdf->Cell($d=22,12,'CANTIDAD',1,0,'C',0);
$x=$pdf->GetX();
$pdf->Multicell($e=33,6,'PRECIO     UNITARIO Bs.',1,'C',0);
$pdf->SetY($y);
$pdf->SetX($x+$e);
$x=$pdf->GetX();
$pdf->Multicell(0,6,'PRECIO TOTAL Bs.',1,'C',0);
//$pdf->Ln(6);
$pdf->SetFont('Times','',10);
$i=0;
$total =0;

//-------------
if ($aprobado==0)
	{$consultx = "SELECT	presupuesto.*, contribuyente.nombre FROM contribuyente, presupuesto WHERE left(partida,9)<>'403180100' AND presupuesto.estatus=0 AND id_contribuyente = $id AND presupuesto.id_contribuyente = contribuyente.id;";}
else
	{$consultx = "SELECT	presupuesto.*, contribuyente.nombre FROM contribuyente, presupuesto WHERE left(partida,9)<>'403180100' AND id_solicitud = $id AND presupuesto.id_contribuyente = contribuyente.id;";}
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	$y=$pdf->GetY();
	$pdf->Cell($a=6,12,'',0,0,'C',0);
	$x=$pdf->GetX();
	$pdf->SetFont('Times','',9);
	$pdf->Multicell($b,6,$registro->descripcion,1,'J',0);
	$pdf->SetFont('Times','',10);
	$y2=$pdf->GetY();
	$pdf->SetY($y);
	$pdf->Cell($a,$y2-$y,$i,1,0,'C',0);
	$pdf->SetX($x+$b);
	$pdf->Cell($c,$y2-$y,$registro->medida,1,0,'C',0);
	$pdf->Cell($d,$y2-$y,$registro->cantidad,1,0,'C',0);
	$x=$pdf->GetX();
	$pdf->Cell($e,$y2-$y,formato_moneda($registro->precio_uni),1,0,'R',0);
	$pdf->Cell(0,$y2-$y,formato_moneda($registro->total),1,0,'R',0);
	$pdf->Ln($y2-$y);
	$total += $registro->total;
	}
//-------------
	$pdf->SetFont('Times','B',10);
	$pdf->SetX($x);
	$pdf->Cell($e,7,"Sub-Total",1,0,'R',0);
	$pdf->Cell(0,7,formato_moneda($total),1,0,'R',0);
	$pdf->Ln(7);

if ($aprobado==0)
	{$consultx = "SELECT sum(presupuesto.total) as total, (porcentaje_iva) as porcentaje_iva FROM contribuyente, presupuesto WHERE left(partida,9)='403180100' AND presupuesto.estatus=0 AND id_contribuyente = $id AND presupuesto.id_contribuyente = contribuyente.id GROUP BY partida LIMIT 1;";}
else
	{$consultx = "SELECT sum(presupuesto.total) as total, (porcentaje_iva) as porcentaje_iva FROM contribuyente, presupuesto WHERE left(partida,9)='403180100' AND id_solicitud = $id AND presupuesto.id_contribuyente = contribuyente.id GROUP BY partida LIMIT 1;";}

$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
	
	$pdf->SetX($x);
	$pdf->Cell($e,7,"IVA ".formato_cedula($registro->porcentaje_iva)."%",1,0,'R',0);
	$pdf->Cell(0,7,formato_moneda($registro->total),1,0,'R',0);
	$pdf->Ln(7);

	$pdf->SetX($x);
	$pdf->Cell($e,7,"Total",1,0,'R',0);
	$pdf->Cell(0,7,formato_moneda($total+$registro->total),1,0,'R',0);
	$pdf->Ln(6);

$pdf->SetY(-50);
$pdf->Cell(93,5,'ELABORADO  POR:',0,0,'C',0);
$pdf->Cell(0,5,'SOLICITADO POR:',0,0,'C',0);
$pdf->Ln(5);

$pdf->Cell(93,5,'',0,0,'C',0);
$pdf->Cell(0,5,'',0,0,'C',0);
$pdf->Ln(18);

$firma1 = firma(9);
$jefe_area = jefe_direccion_x_area($id_area);

$pdf->Cell(93,5,$firma1[1],0,0,'C',0);
$pdf->Cell(0,5,(oraciones($jefe_area[1])),0,0,'C',0);
$pdf->Ln();

$pdf->Cell(93,5,$firma1[2],0,0,'C',0);
$pdf->Cell(0,5,($jefe_area[2]),0,0,'C',0);
$pdf->Ln();

$pdf->Output();
?>