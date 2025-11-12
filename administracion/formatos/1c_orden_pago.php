<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
require '../../vendor/autoload.php';
use Luecano\NumeroALetras\NumeroALetras;
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

//if ($_SESSION['VERIFICADO'] != "SI") { 
//    header ("Location: ../index.php?errorusuario=val"); 
//    exit(); 
//	}
	
class PDF extends FPDF
{
	function Header()
	{    
		$this->SetY(10);
		$id = decriptar($_GET['id']);
		$consultx = "SELECT ordenes_pago.*, contribuyente.rif, contribuyente.nombre as sujeto FROM ordenes_pago , contribuyente WHERE ordenes_pago.id = $id AND ordenes_pago.id_contribuyente = contribuyente.id LIMIT 1;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		$rif = $registro->rif;
		$sujeto = $registro->sujeto;
		$tipo_solicitud = $registro->tipo_solicitud;
		$fecha = $registro->fecha;
		$numero = $registro->numero;
		$descripcion = $registro->descripcion;
		$asignaciones = $registro->asignaciones;
		$_SESSION['retenciones'] = $registro->descuentos;
		$_SESSION['total']= $registro->total;
		$_SESSION['empleado'] = $registro->usuario;
		//--------------
		$id_solicitud = 99999999999999;
		$consultx = "SELECT anno, id FROM nomina_solicitudes WHERE id_orden_pago = $id;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro = $tablx->fetch_object())
			{
			$anno = $registro->anno;
			$id_solicitud = $id_solicitud .','. $registro->id;
			}
		$_SESSION['id_solicitud'] = $id_solicitud;
		//--------------
		$consultx = "SELECT sum(sso) as sso, sum(fp) as fp, sum(lph) as lph, sum(fej) as fej FROM nomina WHERE id_solicitud in ($id_solicitud);"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		$sso = $registro->sso;
		$pf = $registro->fp;
		$lph = $registro->lph;
		$fej = $registro->fej;
		//--------------
	
		$this->SetFillColor(240);
		if (anno($fecha)<2024)
		{$this->Image('../../images/logo_2023.jpg',27,7,32);}
		else
		{$this->Image('../../images/logo_nuevo.jpg',27,7,40);}
		$this->Image('../../images/bandera_linea.png',17,41,182,1);
		$this->SetFont('Times','',11);
		
		$municipio = 'Francisco de Miranda';
		// ---------------------
		//$this->SetY(12);
		//$instituto= instituto();
		$this->SetFont('Times','I',11.5);
		$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Rif G-20001287-0 - Ejercicio Fiscal '.$anno,0,0,'C'); $this->Ln(7);
		
		$this->SetFont('Times','B',14);
		$this->Cell(0,5,'ORDEN DE PAGO',0,0,'C'); 
		//$this->Ln(5);
		//$this->SetFont('Times','',10);
		//$this->Cell(0,5,'NOMINA',0,0,'C'); 
		$this->Ln(10);
		
		$y=$this->GetY();
		$this->SetY(20);
		//$this->SetX(150);
		$this->SetFont('Arial','B',13);
		$this->SetTextColor(0,0,255);
		$this->Cell(0,5,'Nro: '.rellena_cero($numero,5),0,0,'R'); $this->Ln(7);
		$this->SetFont('Arial','B',11);
		$this->SetTextColor(255,0,0);
		$this->Cell(0,5,'Fecha: '.voltea_fecha($fecha),0,0,'R'); //$this->Ln(10);
		$this->SetTextColor(0);
		$this->SetY($y);
		
		$this->SetFont('Times','',10);
		$this->Cell(3,5,''); 
		$this->Cell(28,5,'BENEFICIARIO:',0,0,'L'); 
		$this->SetFont('Times','B',10);
		$this->Cell(118,5,$sujeto,0,0,'L'); 
		$this->SetFont('Times','',11);
		$this->Cell(7,5,'Rif:',0,0,'L');
		$this->SetFont('Times','B',11);
		$this->Cell(0,5,formato_rif($rif),0,0,'C'); 
		$this->SetFont('Times','',10);
		$this->Ln(5);
		$this->Cell(3,5,''); 
		$this->Cell(44,5,'AUTORIZADO A COBRAR:',0,0,'L'); 
		$this->SetFont('Times','B',10);
		$this->Cell(0,5,$sujeto,0,0,'L'); 
		$this->Ln(6);

		$this->SetFont('Times','',10);
		$this->Cell($a=130,6,'DETALLES',1,0,'L',1);
		$this->Cell(0,6,'RETENCIONES',1,0,'C',1); 
		$this->Ln(6);
		
		$y=$this->GetY();
		$this->SetFont('Times','B',9);
		$this->MultiCell($a,4,$descripcion,1,'J');
		$y2=$this->GetY();
		
		$this->SetY($y);
		$this->Cell($a,5,'');
		$this->Cell(20,5,'4,00% SSO',1,0,'R');
		$this->Cell(0,5,formato_moneda($sso),1,0,'R');
		$this->Ln(5);
		$this->Cell($a,5,'');
		$this->Cell(20,5,'0,50%    PF',1,0,'R');
		$this->Cell(0,5,formato_moneda($pf),1,0,'R');
		$this->Ln(5);
		$this->Cell($a,5,'');
		$this->Cell(20,5,'1,00% FAOV',1,0,'R');
		$this->Cell(0,5,formato_moneda($lph),1,0,'R');
		$this->Ln(5);
		$this->Cell($a,5,'');
		$this->Cell(20,5,'3,00% FJE',1,0,'R');
		$this->Cell(0,5,formato_moneda($fej),1,0,'R');
		$this->Ln(5);
		$y3=$this->GetY();
		
		if ($y2>$y3)
			{
			$this->SetY($y2);
			}
		$this->Ln(2);
		//$this->SetFillColor(250);
		$this->SetFont('Times','B',9.5);
		$this->Cell(8,6,'Item',1,0,'C',1);
		$this->Cell($a=20,6,'Compromiso',1,0,'C',1);
		$this->Cell($a,6,'Categoria',1,0,'C',1);
		$this->Cell($a,6,'Partida',1,0,'C',1);
		$this->Cell($b=90-8,6,'Detalle',1,0,'C',1);	
		$this->Cell($c=0,6,'Total',1,0,'C',1);	
		$this->Ln();
	}	
	
	function Footer()
	{    
		$formatter = new NumeroALetras();
		//-------------------------------------------------
		$this->SetY(-73);
		$this->SetFillColor(245);
		$alto = 7;
		$this->Cell($a=120,6,'MONTO A PAGAR EN LETRAS',1,0,'L',1);
		$y=$this->GetY();
		$this->Ln(6);
		$this->SetFont('Times','B',9);
		if ($_SESSION['lineas']==0)	
			{$this->MultiCell($a,4,strtoupper($formatter->toMoney($_SESSION['total'],2,'BOLIVARES','CENTIMOS')),1);}
		else
			{$this->MultiCell($a,12,'',1);}
		$y2=$this->GetY();
		
		$this->SetY($y);
		$this->Cell($a,6,'');
		$this->Cell(30,6,'Van... Bs->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['monto']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(30,6,'Retención Bs->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['retenciones']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(30,6,'Neto a Pagar Bs->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['total']),1,0,'R');
		$this->Ln(6);
		$y3=$this->GetY();
		
		if ($y2>$y3)
			{
			$this->SetY($y2);
			}
		$this->Ln(2);
		
		//$this->Ln(8+$altura);
		
		$this->SetFont('Times','',9.5);
		$this->Cell($a=124,5,'RECIBÍ CONFORME',1,0,'C',1);
		$this->Cell(0,5,'Asiento Contable',1,1,'C',1);
		//------------
		$this->Cell($a/4+10,4.5,'Nombre y Apellido',1,0,'C',1);
		$this->Cell($a/4,4.5,'Firma',1,0,'C',1);
		$this->Cell($a/4-5,4.5,'C.I.',1,0,'C',1);
		$this->Cell($a/4-5,4.5,'Fecha',1,0,'C',1);
		$x=$this->GetX(); $y=$this->GetY();
		$this->Cell(0,4.5,'103 Gastos por Pagar',0,1,'C',0);
		//------------
		$this->Cell($a/4+10,6,'',1,0,'C',0);
		$this->Cell($a/4,6,'',1,0,'C',0);
		$this->Cell($a/4-5,6,'',1,0,'C',0);
		$this->Cell($a/4-5,6,'',1,0,'C',0);
		$this->Cell(0,5,'101 Orden de Pago',0,0,'C',0);
		$this->SetX($x); $this->SetY($y);
		$this->Cell(0,10.5,'',1,1);
		$this->Cell(0,20,'',1);
		$this->SetY($y+11);
		$this->SetFont('Times','',9);
		$this->Cell(0,5,'CONFORME FIRMAN:',0,0,'L');
		$this->SetY($y+23);
		$this->SetFont('Times','',9.5);
//		$instituto = instituto();
//		$this->Cell(182/3,5,$instituto[2],0,0,'C');
//		$this->Cell(182/3,5,$instituto[3],0,0,'C');
//		$this->Cell(182/3,5,$instituto[4],0,0,'C');
//		//$this->Cell(0,5,'TESORERIA',0,0,'C');
		//--------------
		$this->SetFont('Times','I',8);
		$this->SetY(-13);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['empleado'],0,0,'L');
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de paginas',0,0,'R');
		
		//--------------
		if ($_SESSION['estatus']==99)	
			{
			$this->SetY(140);
			$this->SetTextColor(255,0,0);
			$this->SetFont('helvetica','',35);
			$this->Cell(0,5,'ORDEN ANULADA',0,0,'C'); 
			$this->SetFont('Times','',10);
			$this->SetTextColor(0);
			}
	}	
}

$id = decriptar($_GET['id']);
//-------------	

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(17,80,17);
$pdf->SetAutoPageBreak(1,73);
$pdf->SetTitle('Orden de Pago');

// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',9);
$a=20;
$b=90-8;
$c=0;

//----------------- ASIGNACIONES
$consulta = "SELECT nomina_asignaciones.categoria, nomina_asignaciones.partida, left(a_partidas.descripcion,70) as descripcion,
sum(nomina_asignaciones.asignaciones) as asignaciones, sum(nomina_asignaciones.total_asignacion) as total_asignaciones, nomina_solicitudes.num_sol_pago, nomina.tipo_pago FROM nomina_solicitudes, nomina , nomina_asignaciones, a_partidas WHERE nomina_solicitudes.id = nomina.id_solicitud AND nomina.id_solicitud in (".$_SESSION['id_solicitud'].") AND nomina.id = nomina_asignaciones.id_nomina AND a_partidas.codigo = nomina_asignaciones.partida GROUP BY nomina_asignaciones.categoria, nomina_asignaciones.partida ORDER BY num_sol_pago, nomina_asignaciones.categoria, nomina_asignaciones.partida;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$_SESSION['lineas'] = $tabla->num_rows;
//-----------------
$i=1;
$_SESSION['monto'] = 0;
$alto = 5;
while ($registro = $tabla->fetch_object())
	{
	//----------
	$pdf->Cell(8,$alto,$i,1,0,'C',0);
	$pdf->Cell($a,$alto,rellena_cero($registro->num_sol_pago,6).'n',1,0,'C',0);
	$pdf->Cell($a,$alto,$registro->categoria,1,0,'C',0);
	$pdf->Cell($a,$alto,($registro->partida),1,0,'C',0);
	$pdf->Cell($b,$alto,ucfirst(strtolower($registro->descripcion)),1,0,'L',0);
	$pdf->SetFillColor(255);
	$pdf->Cell($c,$alto,formato_moneda($registro->asignaciones),1,1,'R',1);
	//-----------
	$_SESSION['monto'] += $registro->asignaciones;
	$_SESSION['lineas']--;
	$i++;
	}
////----------------- DEDUCCIONES
//$consulta = "SELECT nomina_descuentos.categoria, nomina_descuentos.partida, a_partidas.descripcion,	sum( nomina_descuentos.descuento ) AS descuento,	nomina_solicitudes.num_sol_pago, nomina.tipo_pago FROM nomina_solicitudes, nomina, nomina_descuentos, a_partidas WHERE 	nomina_solicitudes.id = nomina.id_solicitud AND nomina.id_solicitud IN ( ".$_SESSION['id_solicitud']." ) AND nomina.id = nomina_descuentos.id_nomina AND a_partidas.codigo = nomina_descuentos.partida GROUP BY nomina_descuentos.categoria, nomina_descuentos.partida ORDER BY num_sol_pago, 	nomina_descuentos.categoria, nomina_descuentos.partida;"; //echo $consulta;
//$tabla = $_SESSION['conexionsql']->query($consulta);
//$_SESSION['lineas'] = $tabla->num_rows;
////-----------------
//while ($registro = $tabla->fetch_object())
//	{
//	//----------
//	$pdf->Cell(8,$alto,$i,1,0,'C',0);
//	$pdf->Cell($a,$alto,rellena_cero($registro->num_sol_pago,6).'n',1,0,'C',0);
//	$pdf->Cell($a,$alto,$registro->categoria,1,0,'C',0);
//	$pdf->Cell($a+1,$alto,$registro->partida,1,0,'C',0);
//	$pdf->Cell($b-1,$alto,$registro->descripcion,1,0,'L',0);
//	$pdf->SetFillColor(255);
//	$pdf->Cell($c,$alto,formato_moneda($registro->descuento),1,1,'R',1);
//	//-----------
//	$_SESSION['monto']= $_SESSION['monto'];
//	$_SESSION['lineas']--;
//	$i++;
//	}

if ($pdf->GetY()<$y=205)
	{
	$pdf->Cell(8,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($a,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($a,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($a,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell(0,$y-$pdf->GetY(),'',1,1,'C',0);
	}

$pdf->Output();
?>