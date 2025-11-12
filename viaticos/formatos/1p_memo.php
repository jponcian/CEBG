<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
require '../../vendor/autoload.php';
use Luecano\NumeroALetras\NumeroALetras;
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");


if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Header()
	{    
		$this->SetY(10);
		$id = decriptar($_GET['id']);
		$consultx = "SELECT * FROM patria_nomina_solicitudes WHERE id = $id LIMIT 1;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		$descripcion = $registro->descripcion;
		$tipo_pago = $registro->tipo_pago;
		$fecha_sol = $registro->fecha_sol;
		$numero = $registro->numero;
		$num_sol_pago = $registro->num_sol_pago;
		$nomina = $registro->nomina;
		$anno = $registro->anno;
		$fecha = $registro->fecha;
		$desde = $registro->desde;
		$hasta = $registro->hasta;
		$asignaciones = $registro->asignaciones;
		$descuentos = $registro->descuentos;
		$total = $registro->total;
		$_SESSION['retenciones'] = $registro->descuentos;
		$_SESSION['total']= $registro->total;
		//--------------
		$consulta = "SELECT COUNT(cedula) FROM patria_nomina WHERE id_solicitud = $id GROUP BY cedula;";
		$tabla = $_SESSION['conexionsql']->query($consulta);
		$trabajadores = $tabla->num_rows;
		//-----------------
		if ($tipo_pago=='001')
			{
			$quincena = 'PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			if ((dia($desde)=='1' or dia($desde)=='01') and dia($hasta)=='15')
				{	$quincena = 'PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
			if (dia($desde)=='16' and intval(dia($hasta))>=28)
				{	$quincena = 'SEGUNDA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
			}
		if ($tipo_pago=='001' and mes($desde)<>mes($hasta))
			{
			$quincena = $descripcion . ' DEL ' . anno($desde);
			}
		if ($tipo_pago=='002')
			{
			$quincena = 'CESTATICKETS DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			}
		if ($tipo_pago=='003')
			{
			$quincena = 'BONO VACACIONAL DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			}
		if ($tipo_pago=='004')
			{
			$quincena = 'DIFERENCIA PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			if ((dia($desde)=='1' or dia($desde)=='01') and dia($hasta)=='15')
				{	$quincena = 'DIFERENCIA PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
			if (dia($desde)=='16' and intval(dia($hasta))>=28)
				{	$quincena = 'DIFERENCIA SEGUNDA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
			}
		if ($tipo_pago=='005')
			{
				$quincena = 'DIFERENCIA BONO VACACIONAL DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			}
		if ($tipo_pago=='006')
			{
				$quincena = 'BONO ESCOLAR BOMBEROS MES DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			}
		if ($tipo_pago=='007')
			{
				$quincena = "$descripcion DESDE EL ".voltea_fecha($desde)." AL ".voltea_fecha($hasta)."";
			}
		if ($tipo_pago=='008')
			{
				$quincena = "$descripcion de fecha ".voltea_fecha($desde)."";
			}
		if ($tipo_pago=='009' or $tipo_pago=='010' or $tipo_pago=='011' or $tipo_pago=='012' or $tipo_pago=='013')
			{
				$quincena = "$descripcion";
			}
		//Desde: ".voltea_fecha($desde)." Hasta: ".voltea_fecha($hasta)."
		$this->SetFillColor(235);
		$this->Image('../../images/logo_nuevo.jpg',27,7,40);
		$this->Image('../../images/bandera_linea.png',17,41,182,1);
		$this->SetFont('Times','',11);
		
		$municipio = 'Francisco de Miranda';
		// ---------------------
		//$this->SetY(12);
		//$instituto = instituto();
		$this->SetFont('Times','I',11.5);
		$this->Cell(0,5,'Republica Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Ejercicio Fiscal '.$anno,0,0,'C'); $this->Ln(7);
		
		$this->SetFont('Times','B',12);
		if ($tipo_pago=='001')
			{	$this->Cell(0,5,'SOLICITUD DE PAGO DE NOMINA',0,0,'C'); 	}
		else
			{	$this->Cell(0,5,'SOLICITUD DE PAGO',0,0,'C'); 	}
		$this->Ln(10);
		
		$y=$this->GetY();
		$this->SetY(20);
		//$this->SetX(150);
		$this->SetFont('Arial','B',13);
		$this->SetTextColor(0,0,255);
		$this->Cell(0,5,'Nro: '.rellena_cero($num_sol_pago,5),0,0,'R'); $this->Ln(7);
		$this->SetFont('Arial','B',11);
		$this->SetTextColor(255,0,0);
		$this->Cell(0,5,'Fecha: '.voltea_fecha($fecha_sol),0,0,'R'); //$this->Ln(10);
		$this->SetTextColor(0);
		$this->SetY($y);
		
		$this->SetFont('Times','',10);
		$this->Cell(6,5,''); 
		$this->Cell(0,5,'FAVOR REALIZAR EL(LOS) PAGO(S) QUE A CONTINUACION SE DETALLA POR CONCEPTO DE:',0,0,'L'); 
		$this->Ln(5);
		$this->SetFont('Times','B',10);
		$this->MultiCell(0,5,"Pago de Nomina $nomina Pago Tipo: ".$tipo_pago." corresponde a: $quincena a $trabajadores Trabajadores. Monto Total Bs: ".formato_moneda($asignaciones)." - Menos Retenciones Bs: ".formato_moneda($descuentos)." = Monto Neto Bs: ".formato_moneda($total).".",0,'J');// 
		$this->Ln(3);
		
		$this->SetFont('Times','B',10.5);
		$this->Cell(0,8,'DESCRIPCION',1,0,'C',1);
		$this->Ln();
		$this->SetFillColor(245);
		$this->SetFont('Times','B',9.5);
		$this->Cell($aa=10,7,'Item',1,0,'C',1);
		$this->Cell($a=40,7,'Imputacion Presup.',1,0,'C',1);
		$this->Cell($b=100,7,'Detalle',1,0,'C',1);
		$this->Cell($c=0,7,'Total',1,0,'C',1);	
		$this->Ln();
	}	
	
	function Footer()
	{    
		$formatter = new NumeroALetras();
		//-------------------------------------------------
		$this->SetY(-68);
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
		if ($_SESSION['lineas']==0)	{$this->Cell(30,6,'Monto Total Bs->',1,0,'R',1);}
			else	{$this->Cell(30,6,'Van... Bs->',1,0,'R',1);}
		$this->Cell(0,6,formato_moneda($_SESSION['monto']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(30,6,'Retencion Bs->',1,0,'R',1);
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
		
		
		$this->SetFont('Times','',9.5);
		$this->Cell($a=124,5,'CONTABILIZADO',1,0,'C',1);
		$this->Cell(0,5,'Asiento Contable',1,1,'C',1);
		//------------
		$this->Cell($a/4,4.5,'Fecha',1,0,'C',1);
		$this->Cell($a/4,4.5,'Orden de Pago',1,0,'C',1);
		$this->Cell($a/4,4.5,'Comprobante',1,0,'C',1);
		$this->Cell($a/4,4.5,'Nro Cuenta',1,0,'C',1);
		$x=$this->GetX(); $y=$this->GetY();
		$this->Cell(0,4.5,'300 Gastos Presupuestarios',0,1,'C',0);
		//------------
		$this->Cell($a/4,5,'',1,0,'C',0);
		$this->Cell($a/4,5,'',1,0,'C',0);
		$this->Cell($a/4,5,'',1,0,'C',0);
		$this->Cell($a/4,5,'',1,0,'C',0);
		$this->Cell(0,5,'103 Gastos por Pagar',0,0,'C',0);
		$this->SetX($x); $this->SetY($y);
		$this->Cell(0,9.5,'',1,1);
		$this->Cell(0,17,'',1);
		$this->SetY($y+21);
		$this->Cell(0,5,'JEFE DE RECURSOS HUMANOS',0,0,'C');
		//--------------
		$this->SetFont('Times','I',8);
		$this->SetY(-13);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de paginas',0,0,'R');
		
	}	
}

$id = decriptar($_GET['id']);
//-------------	

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(17,80,17);
$pdf->SetAutoPageBreak(1,68);
$pdf->SetTitle('Solicitud de Pago de Nomina');

// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',9);
$aa=10;
$a=40;
$b=100;
$c=0;

//-----------------
$consulta = "SELECT patria_nomina_asignaciones.categoria, patria_nomina_asignaciones.partida, a_partidas.descripcion,
sum(patria_nomina_asignaciones.asignaciones) as asignaciones, sum(patria_nomina_asignaciones.total_asignacion) as total_asignaciones FROM patria_nomina , patria_nomina_asignaciones , a_partidas WHERE patria_nomina.id_solicitud = $id AND patria_nomina.id = patria_nomina_asignaciones.id_nomina AND a_partidas.codigo = patria_nomina_asignaciones.partida GROUP BY patria_nomina_asignaciones.categoria, patria_nomina_asignaciones.partida;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$_SESSION['lineas'] = $tabla->num_rows;
//-----------------
$i=0;
$_SESSION['monto'] = 0;
$alto = 5;
while ($registro = $tabla->fetch_object())
	{
	//----------
	$pdf->Cell($aa,$alto,$i+1,1,0,'C',0);
	$pdf->Cell($a,$alto,$registro->categoria.$registro->partida,1,0,'C',0);
	$pdf->Cell($b,$alto,$registro->descripcion,1,0,'L',0);
	$pdf->Cell($c,$alto,formato_moneda($registro->asignaciones),1,1,'R',0);
	//-----------
	$_SESSION['monto'] += $registro->asignaciones;
	$_SESSION['lineas']--;
	$i++;
	}

if ($pdf->GetY()<$y=205)
	{
	$pdf->Cell($aa,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($a,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell(0,$y-$pdf->GetY(),'',1,1,'C',0);
	}

$pdf->Output();
?>