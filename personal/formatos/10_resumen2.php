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
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-18);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
	}	
}
//------------
$fecha = ($_GET['id']);
$quincena = ($_GET['tipo']);
//------------
//$fecha = voltea_fecha($fecha);
$mes = mes(voltea_fecha($fecha));
$anno = anno(voltea_fecha($fecha));
$desde = $anno.'-'.$mes.'-'.$quincena;
if ($quincena=='01')
	{ 	$hasta= $anno.'-'.$mes.'-15'; 	} else 	{ 	$hasta=baja_dia(sube_mes(voltea_fecha($fecha))); 	}
//------------
$consultx = "SELECT *, num_nomina as numero FROM nomina WHERE MONTH(desde) = '$mes' AND YEAR(desde) = '$anno' AND tipo_pago='001' LIMIT 1;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------
$tipo_pago = $registro->tipo_pago;
$fecha_sol = $registro->fecha_sol;
$numero = $registro->numero;
$nomina = $registro->nomina;
$anno = $registro->anno;
$fecha = $registro->fecha;
$desde = $registro->desde;
$hasta = $registro->hasta;
$asignaciones = $registro->asignaciones;
$descuentos = $registro->descuentos;
$total = $registro->total;
$solicitud = $registro->num_sol_pago;
$fecha_sol = date('Y-m-d'); //$numero = 0; 	
//--------------
$consulta = "SELECT COUNT(cedula) FROM nomina WHERE MONTH(desde) = '$mes' AND YEAR(desde) = '$anno' AND tipo_pago='001' GROUP BY cedula;";  	
$tabla = $_SESSION['conexionsql']->query($consulta);
$trabajadores = $tabla->num_rows;
//-----------------
if ($tipo_pago=='001')
	{
	$quincena = ''.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
	if ((dia($desde)=='1' or dia($desde)=='01') and dia($hasta)=='15')
		{	$quincena = ''.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
	if (dia($desde)=='16' and intval(dia($hasta))>=28)
		{	$quincena = ''.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
	}
//-----------------
if ($tipo_pago=='004')
	{
	$quincena = 'DIFERENCIA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
	if ((dia($desde)=='1' or dia($desde)=='01') and dia($hasta)=='15')
		{	$quincena = 'DIFERENCIA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
	if (dia($desde)=='16' and intval(dia($hasta))>=28)
		{	$quincena = 'DIFERENCIA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
	}
//-------------

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(8,12,8);
$pdf->SetAutoPageBreak(1,23);
$pdf->SetTitle('Preliminar Nomina');

// ----------
$pdf->AddPage();
$pdf->Image('../../images/logo_nuevo.jpg',40,7,38);
//$pdf->Image('../../images/bandera_linea.png',17,41,182,0);
$pdf->SetFont('Times','',11);

$municipio = 'Francisco de Miranda';
// ---------------------
//$pdf->SetY(12);
//$instituto = instituto();
$pdf->SetFont('Times','I',11.5);
$pdf->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Talento Humano',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Calabozo Dirección de Talento Humano',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Ejercicio Fiscal '.$anno,0,0,'C'); $pdf->Ln(7);

$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,'PRELIMINAR NOMINA - LISTADO',0,0,'C'); 
$pdf->Ln(6);

$y=$pdf->GetY();
$pdf->SetY(20);
//$pdf->SetX(150);
$pdf->SetFont('Arial','B',13);
if ($solicitud>0)
	{
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(0,5,'Solicitud: '.rellena_cero($solicitud,5),0,0,'R'); $pdf->Ln(7);
	$pdf->SetTextColor(255,0,0);
	$pdf->Cell(0,5,'Nomina: '.rellena_cero($numero,5),0,0,'R'); $pdf->Ln(7);
	}
$pdf->SetFont('Arial','B',11);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,5,'Fecha: '.voltea_fecha($fecha_sol),0,0,'R'); //$pdf->Ln(10);
$pdf->SetTextColor(0);
$pdf->SetY($y);

$pdf->SetFont('Times','B',8);
$pdf->MultiCell(0,5,"Preliminar Pago de la Nomina correspondiente a: $quincena.",0,'J');// ".rellena_cero($numero,3)."
$pdf->Ln(3);

//$pdf->SetFont('Times','B',10.5);
//$pdf->Cell(0,8,'DESCRIPCION',1,0,'C',0);
//$pdf->Ln();
$pdf->SetFillColor(255,215,196);
$pdf->SetFont('Times','B',8);
$pdf->Cell($aa=7,5,'N°',1,0,'C',1);
//$pdf->Cell($a=17,5,'Cedula',1,0,'C',1);
$pdf->Cell($b=25,5,'Empleado',1,0,'C',1);
//$pdf->Cell($c=14,5,'Ingreso',1,0,'C',1);
$pdf->Cell($d=19,5,'Sueldo',1,0,'C',1);
$pdf->Cell($e=18,5,'Prof.',1,0,'C',1);
$pdf->Cell($f=18,5,'Antiguedad',1,0,'C',1);
$pdf->Cell($g=16,5,'Hijos',1,0,'C',1);
$pdf->Cell($h=18,5,'Bono',1,0,'C',1);
$pdf->Cell($m=18,5,'Tickets',1,0,'C',1);
$pdf->Cell($i=20,5,'Asignacion',1,0,'C',1);
$pdf->Cell($j=15.5,5,'SSO',1,0,'C',1);
$pdf->Cell($j,5,'PF',1,0,'C',1);
$pdf->Cell($j,5,'FAOV',1,0,'C',1);
$pdf->Cell($j,5,'FEJ',1,0,'C',1);
$pdf->Cell($o=19,5,'Deduccion',1,0,'C',1);
$pdf->Cell($p=0,5,'Neto a Pagar',1,1,'C',1);
$pdf->SetFont('Times','',8);
//$pdf->Ln();
$pdf->SetFillColor(255);
$alto = 5;
//-----------------
$consultax = "SELECT Sum(nomina.sueldo) as sueldo FROM nomina WHERE tipo_pago='002' AND nomina.hasta = '".($hasta)."' GROUP BY nomina.tipo_pago;";
$tablax = $_SESSION['conexionsql']->query($consultax);
$registrox = $tablax->fetch_object();
$tickets = $registrox->sueldo;
//-----------------
$consultax = "SELECT Sum(nomina.sueldo) as sueldo, Sum(nomina.prof) as prof, Sum(nomina.antiguedad) as antiguedad, Sum(nomina.hijos) as hijos, Sum(nomina.bono) as bono, Sum(nomina.tickets) as tickets, Sum(nomina.asignaciones) as asignaciones, Sum(nomina.sso) as sso, Sum(nomina.fp) as fp, Sum(nomina.lph) as lph, Sum(nomina.fej) as fej, Sum(nomina.fusamieg) as fusamieg, Sum(nomina.descuentos) as descuentos, Sum(nomina.total) as total FROM nomina WHERE tipo_pago='001' AND MONTH(nomina.desde) = '$mes' AND YEAR(nomina.desde)= '$anno' GROUP BY nomina.tipo_pago;";
$tablax = $_SESSION['conexionsql']->query($consultax);
$registrox = $tablax->fetch_object();
//-----------------
$pdf->SetFont('Times','',7);
$pdf->Cell($aa+$b+$c,$alto,'TOTAL GENERAL => ',1,0,'R',1);
$pdf->Cell($d,$alto,formato_moneda($registrox->sueldo),1,0,'R',1);
$pdf->Cell($e,$alto,formato_moneda($registrox->prof),1,0,'R',0);
$pdf->Cell($f,$alto,formato_moneda($registrox->antiguedad),1,0,'R',0);
$pdf->Cell($g,$alto,formato_moneda($registrox->hijos),1,0,'R',0);
$pdf->Cell($h,$alto,formato_moneda($registrox->bono),1,0,'R',0);
$pdf->Cell($m,$alto,formato_moneda($tickets),1,0,'R',0);
$pdf->Cell($i,$alto,formato_moneda($registrox->asignaciones+$tickets),1,0,'R',0);
$pdf->Cell($j,$alto,formato_moneda($registrox->sso),1,0,'R',0);
$pdf->Cell($j,$alto,formato_moneda($registrox->fp),1,0,'R',0);
$pdf->Cell($j,$alto,formato_moneda($registrox->lph),1,0,'R',0);
$pdf->Cell($j,$alto,formato_moneda($registrox->fej),1,0,'R',0);
$pdf->Cell($o,$alto,formato_moneda($registrox->descuentos),1,0,'R',0);
$pdf->Cell($p,$alto,formato_moneda($registrox->total+$tickets),1,1,'R',0);
$pdf->SetFont('Times','',8);

$consulta = "SELECT SUM(nomina.total) as ttotal, SUM(nomina.fp) as tfp, SUM(nomina.lph) as tlph, SUM(nomina.fej) as tfej, SUM(nomina.descuentos) as tdescuentos, SUM(nomina.sso) as tsso, SUM(nomina.asignaciones) as tasignaciones, SUM(nomina.bono) as tbono, SUM(nomina.hijos) as thijos, SUM(nomina.antiguedad) as tantiguedad, SUM(nomina.sueldo) as tsueldo, SUM(nomina.prof) as tprof, nomina.*, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, rac.ing_dia, rac.ing_mes, rac.ing_ano FROM nomina, rac WHERE nomina.tipo_pago='001' AND rac.cedula = nomina.cedula AND MONTH(nomina.desde) = '$mes' AND YEAR(nomina.desde)= '$anno' GROUP BY rac.cedula ORDER BY nomina.ubicacion, nomina.nomina, rac.cedula ASC;";
//echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
//-----------------
$z=0;
$monto = 0;
$ubicacion = '';
while ($registro = $tabla->fetch_object())
{
	if ($ubicacion<>$registro->ubicacion)
		{	
		$pdf->SetFont('Times','B',7);
		$pdf->SetFillColor(255,215,196);
		$pdf->Cell(0,$alto,$registro->ubicacion,1,1,'L',1);	
		$ubicacion = $registro->ubicacion ;
		//-----------------
		//if ($totalizar)
			//{	
				//-----------------
				$consultax = "SELECT Sum(nomina.sueldo) as sueldo FROM nomina WHERE tipo_pago='002' AND nomina.hasta = '".($hasta)."' AND ubicacion='$ubicacion';";
				$tablax = $_SESSION['conexionsql']->query($consultax);
				$registrox = $tablax->fetch_object();
				$tickets = $registrox->sueldo;
				//-----------------
				$consultax = "SELECT Sum(nomina.tickets) as tickets, Sum(nomina.sueldo) as sueldo, Sum(nomina.prof) as prof, Sum(nomina.antiguedad) as antiguedad, Sum(nomina.hijos) as hijos, Sum(nomina.bono) as bono, Sum(nomina.asignaciones) as asignaciones, Sum(nomina.sso) as sso, Sum(nomina.fp) as fp, Sum(nomina.lph) as lph, Sum(nomina.fej) as fej, Sum(nomina.fusamieg) as fusamieg, Sum(nomina.descuentos) as descuentos, Sum(nomina.total) as total FROM nomina WHERE MONTH(nomina.desde) = '$mes' AND YEAR(nomina.desde)= '$anno' AND tipo_pago='001' AND ubicacion='$ubicacion';";
				$tablax = $_SESSION['conexionsql']->query($consultax);
				$registrox = $tablax->fetch_object();
				//-----------------
				$pdf->SetFont('Times','B',7.5);
				$pdf->Cell($aa+$b+$c,$alto,'Sub Total Categoría => ',1,0,'R',1);
				$pdf->Cell($d,$alto,formato_moneda($registrox->sueldo),1,0,'R',1);
				$pdf->Cell($e,$alto,formato_moneda($registrox->prof),1,0,'R',1);
				$pdf->Cell($f,$alto,formato_moneda($registrox->antiguedad),1,0,'R',1);
				$pdf->Cell($g,$alto,formato_moneda($registrox->hijos),1,0,'R',1);
				$pdf->Cell($h,$alto,formato_moneda($registrox->bono),1,0,'R',1);
				$pdf->Cell($m,$alto,formato_moneda($tickets),1,0,'R',1);
				$pdf->Cell($i,$alto,formato_moneda($registrox->asignaciones+$tickets),1,0,'R',1);
				$pdf->Cell($j,$alto,formato_moneda($registrox->sso),1,0,'R',1);
				$pdf->Cell($j,$alto,formato_moneda($registrox->fp),1,0,'R',1);
				$pdf->Cell($j,$alto,formato_moneda($registrox->lph),1,0,'R',1);
				//$pdf->Cell($m,$alto,formato_moneda($registrox->fusamieg),1,0,'R',1);
				$pdf->Cell($j,$alto,formato_moneda($registrox->fej),1,0,'R',1);
				$pdf->Cell($o,$alto,formato_moneda($registrox->descuentos),1,0,'R',1);
				$pdf->Cell($p,$alto,formato_moneda($registrox->total+$tickets),1,1,'R',1);
				$pdf->SetFont('Times','',8);
			//}		
		$pdf->SetFillColor(255);
		}
	//-----------------
	$tickets=0;
	$consulta_x = "SELECT sueldo FROM nomina WHERE hasta = '".($hasta)."' AND tipo_pago='002' AND cedula='".$registro->cedula."';"; 
	//echo $consulta_x;
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	$tickets = $registro_x->sueldo;
	//----------
	$pdf->Cell($aa,$alto,$z+1,1,0,'C',0);
	//$pdf->Cell($a,$alto,$registro->cedula,1,0,'C',0);
	$pdf->SetFont('Times','',6);
	$pdf->Cell($b,$alto,$registro->nombre,1,0,'L',0);
	$pdf->SetFont('Times','',8);
	$pdf->Cell($d,$alto,formato_moneda($registro->tsueldo),1,0,'R',1);
	$pdf->Cell($e,$alto,formato_moneda($registro->tprof),1,0,'R',0);
	$pdf->Cell($f,$alto,formato_moneda($registro->tantiguedad),1,0,'R',0);
	$pdf->Cell($g,$alto,formato_moneda($registro->thijos),1,0,'R',0);
	$pdf->Cell($h,$alto,formato_moneda($registro->tbono),1,0,'R',0);
	$pdf->Cell($m,$alto,formato_moneda($tickets),1,0,'R',0);
	$pdf->Cell($i,$alto,formato_moneda($registro->tasignaciones+$tickets),1,0,'R',0);
	$pdf->Cell($j,$alto,formato_moneda($registro->tsso),1,0,'R',0);
	$pdf->Cell($j,$alto,formato_moneda($registro->tfp),1,0,'R',0);
	$pdf->Cell($j,$alto,formato_moneda($registro->tlph),1,0,'R',0);
	$pdf->Cell($j,$alto,formato_moneda($registro->tfej),1,0,'R',0);
	$pdf->Cell($o,$alto,formato_moneda($registro->tdescuentos),1,0,'R',0);
	$pdf->Cell($p,$alto,formato_moneda($registro->ttotal+$tickets),1,0,'R',0);
	//-----------
	$pdf->Ln();
	$z++;
	}

$pdf->Output();
?>