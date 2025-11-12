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

$id = decriptar($_GET['id']);
$tipo = ($_GET['tipo']);
$consultx = "SELECT * FROM nomina_solicitudes WHERE id = $id LIMIT 1;"; 
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
//--------------
if (substr($fecha_sol,0,1)=='')
	{	$fecha_sol=date('Y/m/d');	}
//--------------
$consulta = "SELECT COUNT(cedula) FROM nomina WHERE id_solicitud = $id GROUP BY cedula;";
$tabla = $_SESSION['conexionsql']->query($consulta);
$trabajadores = $tabla->num_rows;
//-----------------
$quincena = 'CESTATICKETS DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
//-------------	

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(8,12,8);
$pdf->SetAutoPageBreak(1,20);
$pdf->SetTitle('Nomina Cestatickets');

// ----------
$pdf->AddPage();
$pdf->SetFillColor(235);
$pdf->Image('../../images/logo_nuevo.jpg',27,7,40);
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
$pdf->Cell(0,5,'Ejercicio Fiscal '.$anno,0,0,'C'); $pdf->Ln(7);

$pdf->SetFont('Times','B',12);
if ($solicitud>0)
	{
	if ($tipo_pago=='002')	{	$pdf->Cell(0,5,'DEFINITIVO NOMINA CESTATICKETS - LISTADO',0,0,'C'); 	}
	}
else
	{
	if ($tipo_pago=='002')	{	$pdf->Cell(0,5,'PRELIMINAR NOMINA CESTATICKETS - LISTADO',0,0,'C'); 	} 
	}
//if ($tipo_pago=='002')	{	$pdf->Cell(0,5,'PRELIMINAR NOMINA CESTATICKETS - LISTADO',0,0,'C'); 	}
//	else	{	$pdf->Cell(0,5,'PRELIMINAR NOMINA - LISTADO (sin cuenta bancaria)',0,0,'C'); 	}
$pdf->Ln(10);

$y=$pdf->GetY();
$pdf->SetY(20);
//$pdf->SetX(150);
$pdf->SetFont('Arial','B',13);
if ($solicitud>0)
	{
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(0,5,'Solicitud: '.rellena_cero($solicitud,5),0,0,'R'); $pdf->Ln(7);
	$pdf->SetTextColor(255,0,0);
	$pdf->Cell(0,5,'Periodo: '.rellena_cero($numero,5),0,0,'R'); $pdf->Ln(7);
	}
$pdf->SetFont('Arial','B',11);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,5,'Fecha: '.voltea_fecha($fecha_sol),0,0,'R'); //$pdf->Ln(10);
$pdf->SetTextColor(0);
$pdf->SetY($y);

$pdf->SetFont('Times','B',8);
$pdf->MultiCell(0,5,"Pago de la Nomina $nomina correspondiente a: $quincena, Desde: ".voltea_fecha($desde)." Hasta: ".voltea_fecha($hasta).".",0,'J');// ".rellena_cero($numero,3)."
$pdf->Ln(3);

//$pdf->SetFont('Times','B',10.5);
//$pdf->Cell(0,8,'DESCRIPCION',1,0,'C',0);
//$pdf->Ln();
$pdf->SetFillColor(245);
$pdf->SetFont('Times','B',8);
$pdf->Cell($aa=7,5,'N°',1,0,'C',1);
$pdf->Cell($a=17,5,'Cedula',1,0,'C',1);
$pdf->Cell($b=52,5,'Empleado',1,0,'C',1);
$pdf->Cell($c=15,5,'Ingreso',1,0,'C',1);
$pdf->Cell($d=52+12+20,5,'Cargo',1,0,'C',1);
$pdf->Cell($e=23,5,'CestaTicket',1,0,'C',1);
$pdf->Cell($f=28,5,'Bono Alimentacion',1,0,'C',1);
//$pdf->Cell($g=12,5,'Dias',1,0,'C',1);
//$pdf->Cell($h=25,5,'Bono',1,0,'C',1);
$pdf->Cell($p=0,5,'Neto a Pagar',1,1,'C',1);
$pdf->SetFont('Times','',8);
//$pdf->Ln();
$pdf->SetFillColor(255);
$alto = 5;
//-----------------
$consultax = "SELECT Sum(nomina.sueldo) as sueldo, Sum(nomina.prof) as prof, Sum(nomina.antiguedad) as antiguedad, Sum(nomina.hijos) as hijos, Sum(nomina.bono) as bono, Sum(nomina.asignaciones) as asignaciones, Sum(nomina.sso) as sso, Sum(nomina.fp) as fp, Sum(nomina.lph) as lph, Sum(nomina.fej) as fej, Sum(nomina.fusamieg) as fusamieg, Sum(nomina.descuentos) as descuentos, Sum(nomina.total) as total FROM nomina WHERE nomina.id_solicitud = $id GROUP BY nomina.id_solicitud;";
$tablax = $_SESSION['conexionsql']->query($consultax);
$registrox = $tablax->fetch_object();
//-----------------
$pdf->SetFont('Times','B',9);
$pdf->Cell($aa+$a+$b+$c+$d+$e+$f+$g+$h,$alto,'TOTAL GENERAL => ',1,0,'R',1);
$pdf->Cell($p,$alto,formato_moneda($registrox->total),1,1,'R',0);
$pdf->SetFont('Times','',8);
	
$consulta = "SELECT nomina.*, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, rac.fecha_ingreso FROM nomina, rac WHERE nomina.id_solicitud=$id AND rac.cedula = nomina.cedula ORDER BY (rac.cedula+0);";	
$tabla = $_SESSION['conexionsql']->query($consulta);
//-----------------
$z=0;
$monto = 0;
$ubicacion = '';
while ($registro = $tabla->fetch_object())
{
	//if ($ubicacion<>$registro->ubicacion)
//		{	
//		$pdf->SetFillColor(245);
//		$pdf->Cell($aa+$a+$b+$c+$d,$alto,$registro->ubicacion,1,0,'L',1);	
//		$ubicacion = $registro->ubicacion ;
//		//-----------------
//		$consultax = "SELECT Sum(nomina.sueldo) as sueldo, Sum(nomina.prof) as prof, Sum(nomina.antiguedad) as antiguedad, Sum(nomina.hijos) as hijos, Sum(nomina.bono) as bono, Sum(nomina.asignaciones) as asignaciones, Sum(nomina.sso) as sso, Sum(nomina.fp) as fp, Sum(nomina.lph) as lph, Sum(nomina.fej) as fej, Sum(nomina.fusamieg) as fusamieg, Sum(nomina.descuentos) as descuentos, Sum(nomina.total) as total FROM nomina WHERE nomina.id_solicitud = $id AND ubicacion='$ubicacion' GROUP BY nomina.id_solicitud;";	
//		$tablax = $_SESSION['conexionsql']->query($consultax);
//		$registrox = $tablax->fetch_object();
//		//-----------------
//		$pdf->SetFont('Times','B',7);
//		$pdf->Cell($e+$f+$g+$h,$alto,'Sub Total Categoría => ',1,0,'R',1);
//		$pdf->Cell($p,$alto,formato_moneda($registrox->total),1,1,'R',0);
//		$pdf->SetFont('Times','',8);
//			//}		
//		$pdf->SetFillColor(255);
//		}
	//----------
	$pdf->Cell($aa,$alto,$z+1,1,0,'C',0);
	$pdf->Cell($a,$alto,$registro->cedula,1,0,'C',0);
	$pdf->SetFont('Times','',6);
	$pdf->Cell($b,$alto,$registro->nombre,1,0,'L',0);
	$pdf->SetFont('Times','',8);
	$pdf->Cell($c,$alto,voltea_fecha($registro->fecha_ingreso),1,0,'C',1);
	$pdf->Cell($d,$alto,($registro->cargo),1,0,'L',1);
	$pdf->Cell($e,$alto,formato_moneda($registro->tickets),1,0,'R',0);
	$pdf->Cell($f,$alto,formato_moneda($registro->bono),1,0,'R',0);
	//$pdf->Cell($g,$alto,($registro->asignaciones/($registro->sueldo/30)),1,0,'C',0);
	//$pdf->Cell($h,$alto,formato_moneda($registro->asignaciones),1,0,'R',0);
	$pdf->Cell($p,$alto,formato_moneda($registro->total),1,0,'R',0);
	//-----------
	$pdf->Ln();
	$z++;
	}

$pdf->SetAutoPageBreak(1,0);
$pdf->SetY(-23);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell($a=43,5,'Elaborado por:',0,0,'C');
$pdf->Cell($a,5,'Revisado por:',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->Cell($a,5,'Conformado por:',0,0,'C');
$pdf->Cell($a+2,5,'Conformado presupuestariamente por:',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->SetY(-23);
$pdf->Cell(20,20,'',0,0);
$pdf->Cell($a,20,'',1,0,'L');
$pdf->Cell($a,20,'',1,0,'C');
$pdf->Cell($a,20,'',1,0,'C');
$pdf->Cell($a,20,'',1,0,'C');
$pdf->Cell($a+2,20,'',1,0,'C');
$pdf->SetY(-14);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell($a,5,'',0,0,'L');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->Cell($a,5,'Sello',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->SetY(-10);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell($a,5,'',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');

$pdf->Output();
?>