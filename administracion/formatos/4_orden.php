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
		$this->SetY(10);
		$id = decriptar($_GET['id']);
		$consultx = "SELECT	orden.usuario, orden.id, orden.id_contribuyente, orden.rif, orden.fecha, orden.anno, orden.concepto, orden.numero, contribuyente.nombre FROM contribuyente, orden  WHERE orden.estatus=0 AND tipo_orden='M' AND id_contribuyente = $id AND orden.id_contribuyente = contribuyente.id LIMIT 1;"; //
		//echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		$rif = formato_rif($registro->rif);
		$contribuyente = $registro->nombre;
		$fecha = $registro->fecha;
		$anno = $registro->anno;
		$numero = $registro->numero;
		$concepto = $registro->concepto;
		$asignaciones = $registro->asignaciones;
		$_SESSION['empleado'] = $registro->usuario;
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
		//$instituto = instituto();
		$this->SetFont('Times','I',11.5);
		$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Rif G-20001287-0 - Ejercicio Fiscal '.$anno,0,0,'C'); $this->Ln(7);
		
		$this->SetFont('Times','B',14);
		$this->Cell(0,5,'SOLICITUD ORDEN DE PAGO',0,0,'C'); 
		$this->Ln(12);
		
		$y=$this->GetY();
		$this->SetY(20);
		//$this->SetX(150);
		$this->SetFont('Arial','B',13);
		$this->SetTextColor(0,0,255);
		//$this->Cell(0,5,'Nro: '.rellena_cero($numero,5),0,0,'R'); $this->Ln(7);
		$this->Cell(0,5,'Preliminar',0,0,'R'); $this->Ln(7);
		$this->SetFont('Arial','B',11);
		$this->SetTextColor(255,0,0);
		$this->Cell(0,5,'Fecha: '.voltea_fecha($fecha),0,0,'R'); //$this->Ln(10);
		$this->SetTextColor(0);
		$this->SetY($y);
		
		$this->SetFillColor(255);
		$this->SetFont('Times','',10);
		$this->Cell(3,5,''); 
		$this->Cell(28,5,'BENEFICIARIO:',0,0,'L');
		//$this->Cell(2,5,'',0,0,'L'); 
		$this->SetFont('Times','B',10);
		$this->Cell(118,5,$contribuyente,0,0,'L'); 
		$this->SetFont('Times','',10);
		$this->Cell(7,5,'Rif:',0,0,'L',1);
		$this->SetFont('Times','B',10);
		$this->Cell(0,5,$rif,0,0,'C',1); 
		$this->SetFont('Times','',10);
		$this->Ln(7);
		$this->SetFillColor(240);
		//$this->Cell(3,5,''); 
//		$this->Cell(44,5,'AUTORIZADO A COBRAR:',0,0,'L'); 
//		$this->SetFont('Times','B',10);
//		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'L'); 
//		$this->Ln(6);

		$this->SetFont('Times','',10);
		$this->Cell($a=0,6,'FAVOR REALIZAR EL(LOS) PAGO(S) QUE SE DETALLAN A CONTINUACIÓN POR CONCEPTO DE:',1,0,'L',1);
		$this->Ln(6);
		
		$y=$this->GetY();
		$this->SetFont('Times','B',9);
		$this->MultiCell($a,4,$concepto,1,'J');
		$this->Ln(5);
		//$this->SetFillColor(250);
		$this->SetFont('Arial','',9);
		$this->Cell($e=0,6,'D E S C R I P C I O N',1,0,'C',1);	
		$this->Ln();
		$this->SetFont('Times','B',8.5);
		$this->Cell(8,6,'Item',1,0,'C',1);
		$this->Cell($a=36,6,'Imputacion Presup.',1,0,'C',1);
		$this->Cell($b=14,6,'Cantidad',1,0,'C',1);
		$this->Cell($c=70,6,'Detalle',1,0,'C',1);
		$this->Cell($d=27,6,'Precio Unitario',1,0,'C',1);	
		$this->Cell($e=0,6,'Total',1,0,'C',1);	
		$this->Ln();
	}	
	
	function Footer()
	{    
		//-------------------------------------------------
		$this->SetY(-68);
		$this->SetFillColor(245);
		$alto = 7;
		$this->SetFont('Times','',11-$letra);
		$this->Cell($a=135,$alto,'Monto Total en Letras:',1,0,'C',1);
		$this->Cell(0,$alto,'Monto Bs.',1,1,'C',1);
		
		$this->SetFont('Times','B',10-$letra);
		$y=$this->GetY();
		if ($_SESSION['lineas']==0)	
			{$this->MultiCell($a,5,strtoupper(valorEnLetras($_SESSION['monto'])),1);}
		else
			{$this->MultiCell($a,5,'',1);}
		$y2=$this->GetY();
		
		$this->SetY($y);
		$this->SetX($a+17);
		if ($_SESSION['lineas']==0)	
			{$this->Cell(0,$y2-$y,formato_moneda($_SESSION['monto']),1,1,'C',0); }
		else
			{$this->Cell(0,$y2-$y,'Van... '.formato_moneda($_SESSION['monto']),1,1,'C',0); }
		//$this->Ln(8+$altura);
		
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
		$this->Cell(0,19,'',1);
		$this->SetY($y+23);
		//$instituto = instituto();
		$this->Cell(182/3,5,$instituto[2],0,0,'C');
		$this->Cell(182/3,5,$instituto[3],0,0,'C');
		$this->Cell(182/3,5,$instituto[4],0,0,'C');
		//--------------
		$this->SetFont('Times','I',8);
		$this->SetY(-13);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['empleado'],0,0,'L');
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de paginas',0,0,'R');
		
	}	
}

$id = decriptar($_GET['id']);
//-------------	

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(17,80,17);
$pdf->SetAutoPageBreak(1,73);
$pdf->SetTitle('Solicitud Orden de Pago');

// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',9);
$a=36;
$b=14;
$c=70;
$d=27;

//-----------------
$consulta = "SELECT orden.categoria, orden.partida, orden.cantidad, orden.descripcion, orden.precio_uni, orden.total FROM orden WHERE tipo_orden='M' AND orden.estatus=0 AND orden.id_contribuyente = $id  ORDER BY id;";// 
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
	//$pdf->Cell($aa,$alto,$i+1,1,0,'C',0);
	$pdf->Cell(8,$alto,$i,1,0,'C',0);
	$pdf->Cell($a,$alto,($registro->categoria.$registro->partida),1,0,'C',0);
	$pdf->Cell($b,$alto,$registro->cantidad,1,0,'C',0);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell($c,$alto,$registro->descripcion,1,0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell($d,$alto,formato_moneda($registro->precio_uni),1,0,'R',1);
	$pdf->Cell(0,$alto,formato_moneda($registro->total),1,1,'R',1);
	//-----------
	$_SESSION['monto']= $_SESSION['monto'] + $registro->total;
	$_SESSION['lineas']--;
	$i++;
	}

if ($pdf->GetY()<$y=205)
	{
	$pdf->Cell(8,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($a,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($c,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($d,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell(0,$y-$pdf->GetY(),'',1,1,'C',0);
	}

$pdf->Output();
?>