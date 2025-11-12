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
	{    
		//$this->SetY(10);
		$id = decriptar($_GET['id']);
		$aprobado = ($_GET['p']);
		//------------
	if ($aprobado==0)
		{$consultx = "SELECT	presupuesto.*, contribuyente.nombre FROM contribuyente, presupuesto WHERE presupuesto.estatus=0 AND id_contribuyente = $id AND presupuesto.id_contribuyente = contribuyente.id LIMIT 1;";}
	else
		{$consultx = "SELECT	presupuesto.*, contribuyente.nombre FROM contribuyente, presupuesto WHERE id_solicitud = $id AND presupuesto.id_contribuyente = contribuyente.id LIMIT 1;";}

		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		$fecha = voltea_fecha($registro->fecha_presupuesto);
		$anno = $registro->anno;
		$numero = $registro->numero;
		$concepto = $registro->concepto;
		$asignaciones = $registro->asignaciones;
		$_SESSION['estatus'] = $registro->estatus;
		//--------------
		$this->SetY(17);
		$this->SetFillColor(240);
		$this->Image('../../images/logo_nuevo.jpg',28,12,25);
		//$this->Image('../../images/bandera_linea.png',17,41,182,1);
		
		// ---------------------
		//$this->SetY(12);
		$this->SetFont('Times','I',12);
		$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Rif G-20001287-0 - Ejercicio Fiscal '.$anno,0,0,'C'); $this->Ln(15);
		
		$this->SetFont('Times','B',13);
		$this->Cell(0,5,'SOLICITUD DE CERTIFICACIÓN DE DISPONIBILIDAD',0,0,'C'); 
		$this->Ln(5);
		$this->Cell(0,5,'PRESUPUESTARIA',0,0,'C'); 
		$this->Ln(10);
		//-------------
		$this->SetFont('Arial','B',10);

		$this->Cell(0,6,'Fecha: '.$fecha,0,0,'R',0);
		$this->Ln(8);

		$this->SetFont('Arial','',10);
		$this->Cell(30,6,'SOLICITANTE:',0,0,'L',0);
		$this->SetFont('Arial','B',10);
		$this->Cell(0,6,'JOSÉ MIGUEL GARCÍA',0,0,'L',0);
		$this->Ln(7);

		$this->SetFont('Arial','',10);
		$this->Cell(30,6,'CARGO:',0,0,'L',0);
		$this->SetFont('Arial','B',10);
		$this->Cell(0,6,'JEFE DEL ÁREA DE GESTIÓN DE COMPRAS',0,0,'L',0);
		$this->Ln(12);

		$this->SetFont('Arial','',9);
		$this->SetFont('Times','B',8.5);
		$this->Cell($a=75,6,'DENOMINACIÓN DE LA PARTIDA',1,0,'C',1);
		$this->Cell($b=15,6,'ACT',1,0,'C',1);
		$this->Cell($b,6,'PART',1,0,'C',1);
		$this->Cell($b,6,'GEN',1,0,'C',1);	
		$this->Cell($b,6,'ESP',1,0,'C',1);	
		$this->Cell($b,6,'SUB-ESP',1,0,'C',1);	
		$this->Cell($g=0,6,'ESTIMACIÓN',1,0,'C',1);	
		$this->Ln();
	}	
	
	function Footer()
	{}	
}

$id = decriptar($_GET['id']);
//-------------	

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(17,80,17);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Solicitud de Disponibilidad Presupuestaria');

// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',9);
$a=75;
$b=15;
$id = decriptar($_GET['id']);
$aprobado = ($_GET['p']);

if ($aprobado==0)
	{$consulta = "SELECT a_partidas.codigo,	a_partidas.descripcion as partida,	SUM(total) as monto, categoria FROM	contribuyente,	presupuesto	,	a_partidas	WHERE 		presupuesto.partida = a_partidas.codigo AND	presupuesto.estatus = 0 AND	id_contribuyente =  $id AND	presupuesto.id_contribuyente = contribuyente.id GROUP BY a_partidas.codigo;";}
else
	{$consulta = "SELECT a_partidas.codigo,	a_partidas.descripcion as partida,	SUM(total) as monto, categoria FROM	contribuyente,	presupuesto	,	a_partidas	WHERE 		presupuesto.partida = a_partidas.codigo AND	id_solicitud =  $id AND	presupuesto.id_contribuyente = contribuyente.id GROUP BY a_partidas.codigo;";}
//echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$_SESSION['lineas'] = $tabla->num_rows;
//-----------------
$i=1;
$alto = 5;
$_SESSION['monto'] = 0;

while ($registro = $tabla->fetch_object())
	{
	$pdf->SetFont('Arial','',8);
	//----------
	$pdf->SetFillColor(255);
	$x=$pdf->GetX();
	$y=$pdf->GetY();
	$pdf->Multicell($a,6,$registro->partida,1,'J',0);
	$y2=$pdf->GetY();
	$pdf->SetY($y);
	$pdf->SetX($x+$a);
	
	$pdf->Cell($b,$y2-$y,substr($registro->categoria,8,2),1,0,'C',0);
	$pdf->Cell($b,$y2-$y,substr($registro->codigo,0,3),1,0,'C',0);
	$pdf->Cell($b,$y2-$y,substr($registro->codigo,3,2),1,0,'C',0);
	$pdf->Cell($b,$y2-$y,substr($registro->codigo,5,2),1,0,'C',0);
	$pdf->Cell($b,$y2-$y,substr($registro->codigo,7,2),1,0,'C',0);
	$pdf->Cell(0,$y2-$y,formato_moneda($registro->monto),1,0,'R',0);
	//-----------
	$i++;
	$pdf->Ln($y2-$y);
	}

$pdf->SetFont('Arial','B',9);
$pdf->Ln();
$pdf->Cell(40,15,'OBSERVACIONES:',1,0,'L',0);
$pdf->Cell(0,15,'',1,0,'L',0);

$pdf->SetY(-50);
$pdf->Cell(93,5,'SOLICITADO POR:',0,0,'C',0);
$pdf->Cell(0,5,'RECIBIDO POR:',0,0,'C',0);
$pdf->Ln(5);

$pdf->Cell(93,5,'ÁREA DE GESTIÓN DE COMPRAS',0,0,'C',0);
$pdf->Cell(0,5,'ÁREA DE PRESUPUESTO',0,0,'C',0);
$pdf->Ln(20);

$pdf->Cell(93,5,'FIRMA',0,0,'C',0);
$pdf->Cell(0,5,'FIRMA',0,0,'C',0);

$pdf->Output();
?>