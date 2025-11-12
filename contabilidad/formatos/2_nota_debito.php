<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
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
		$consultx = "SELECT * FROM estado_cuenta WHERE id = $id;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		$anno = anno($registro->fecha);
		$descripcion = $registro->concepto;
		$rif = $registro->rif_orden;
		$contribuyente = $registro->nombre_orden;
		//-------------
		$tipo_solicitud = $registro->tipo_orden;
		$fecha = $registro->fecha;
		$numero = 0;
		//-------
		$_SESSION['orden'] = 0;
		$_SESSION['descripcion'] = $registro->concepto;
		$_SESSION['descuentos'] = 0;
		$_SESSION['iva'] = 0;
		$_SESSION['islr'] = 0;
		$_SESSION['total']= 0;
		$_SESSION['estatus'] = $registro->estatus;
		$_SESSION['empleado'] = $registro->usuario;
		//--------------
	
		$this->SetFillColor(240);
		$this->Image('../../images/logo_nuevo.jpg',27,7,40);
		$this->Image('../../images/bandera_linea.png',17,41,182,1);
		$this->Image('../../images/linea.png',17,51.5,182,1);
		$this->SetFont('Times','',11);
		// ---------------------

		//$this->SetY(12);
		////$instituto = instituto();
		$this->SetFont('Times','I',11.5);
		$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Rif G-20001287-0 - Ejercicio Fiscal '.$anno,0,0,'C'); 
		$this->Ln(8);
		
		$consultx = "SELECT * FROM estado_cuenta WHERE estado_cuenta.id = $id ;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		if ($registro->haber>0)
			{ 	$titulo = 'NOTA DE DEBITO';	}
		else
			{ 	$titulo = 'NOTA DE CREDITO';	}

		$this->SetFont('Times','B',14);
		$this->Cell(0,5,$titulo,0,0,'C'); 
		$this->Ln(8);
		
		$y=$this->GetY();
		$this->SetY(20);
		//$this->SetX(150);
		$this->SetFont('Arial','B',13);
		$this->SetTextColor(0,0,255);
//		$this->Cell(0,5,'Nro: '.rellena_cero($numero,5),0,0,'R'); 
		$this->Ln(7);
		$this->SetFont('Arial','B',11);
		$this->SetTextColor(255,0,0);
		//$this->Cell(0,5,'Fecha: '.voltea_fecha($fecha),0,0,'R'); //
		$this->SetTextColor(0);
		$this->SetY($y+2);
		
		$this->SetFont('Times','',10);
		$this->Cell(3,5,''); 
		$this->Cell(28,5,'BENEFICIARIO:',0,0,'L'); 
		$this->SetFont('Times','B',10);
		$this->MultiCell(112,4,$contribuyente,0); 
		$this->SetY($y+2);
		$this->Cell(28+118,5,''); 
		$this->SetFont('Times','',10);
		$this->Cell(7,5,'Rif:',0,0,'L');
		$this->SetFont('Times','B',10);
		$this->Cell(0,5,formato_rif($rif),0,0,'C'); 
		$this->Ln(11);
		
		$consultx = "SELECT * FROM estado_cuenta INNER JOIN	a_cuentas ON estado_cuenta.id_banco = a_cuentas.id WHERE estado_cuenta.id = $id ;"; 
//		echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro = $tablx->fetch_object())
			{
			//-------
			$transferencia = $registro->referencia;
			$fecha_pago = voltea_fecha($registro->fecha);
			$asignaciones = $registro->debe+$registro->haber;
			$monto= $registro->debe+$registro->haber;
			$_SESSION['total']  = $registro->debe+$registro->haber;
			$banco = $registro->banco;
			$cuenta = $registro->cuenta;
			$tipo = 'TRANSFERENCIA:';

			$this->SetFont('Times','',10);
			$this->Cell(23,6,'Transferencia:',1,0,'C',1); 
			$this->SetFont('Times','B',10);
			$this->Cell(25,6,$transferencia,1,0,'L'); 
			$this->SetFont('Times','',10);
			$this->Cell(13,6,'Fecha:',1,0,'C',1); 
			$this->SetFont('Times','B',10);
			$this->Cell(18,6,$fecha_pago,1,0,'C',0); 
			$this->SetFont('Times','',10);
			$this->Cell(16,6,'BANCO:',1,0,'L',1); 
			$this->SetFont('Times','B',10);
			$this->Cell(41,6,$banco.' '.substr($cuenta,16,4),1,0,'L'); 
            $this->SetFont('Times','',10);
			$this->Cell(16,6,'MONTO:',1,0,'L',1); 
			$this->SetFont('Times','B',10);
			$this->Cell(0,6,formato_moneda($monto),1,0,'R'); 
			$this->Ln();
			}
		$this->Ln(5);
//		$this->SetFont('Times','',10);
//		$this->Cell(0,6,'IMPUTACION PRESUPUESTARIA',1,1,'C',1); 
//		$this->SetFont('Times','B',9.5);
//		$this->Cell(8,6,'Item',1,0,'C',1);
//		$this->Cell($a=20,6,'Compromiso',1,0,'C',1);
//		$this->Cell($a,6,'Causado',1,0,'C',1);
//		//$this->Cell($a+1,6,'Partida',1,0,'C',1);
//		$this->Cell($b=9,6,'Sec',1,0,'C',1);
//		$this->Cell($b,6,'Prg',1,0,'C',1);	
//		$this->Cell($b,6,'SPrg',1,0,'C',1);	
//		$this->Cell($b,6,'Pry',1,0,'C',1);	
//		$this->Cell($b,6,'Act',1,0,'C',1);	
//		$this->Cell($b,6,'Par',1,0,'C',1);	
//		$this->Cell($b,6,'Gen',1,0,'C',1);	
//		$this->Cell($b,6,'Esp',1,0,'C',1);	
//		$this->Cell($b,6,'SEsp',1,0,'C',1);	
//		$this->Cell($b,6,'Aux',1,0,'C',1);	
//		$this->Cell($c=0,6,'Total',1,0,'C',1);	
//		$this->Ln();
	}	
	
	function Footer()
	{    
		//-------------------------------------------------
		$this->SetY(-75);
		$this->SetFillColor(245);
		$alto = 7;
		$this->Cell($a=130,6,'DETALLES',1,0,'L',1);
		$y=$this->GetY();
		$this->Ln(6);
		$this->SetFont('Times','B',9);
		$this->MultiCell($a,4,$_SESSION['descripcion'],1);
		$y2=$this->GetY();
		
		$this->SetY($y);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Van... Bs->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['total']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Iva->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda(0),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Islr->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda(0),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Otro->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda(0),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Neto Bs->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['total']),1,0,'R');
		$this->Ln(6);
		$y3=$this->GetY();
		
		if ($y2>$y3)
			{
			$this->SetY($y2);
			}
		
		$this->Cell(182/3,20,'',1,0,'L');
		$this->Cell(182/3,20,'',1,0,'C');
//		$this->Cell(0,20,'',1,0,'C');

		if ($y2>$y3)
			{
			$this->SetY($y2);
			}

		$this->Ln(2);
		$this->SetFont('Times','',9);
		$this->Ln(13);
		$this->Cell(182/3,4,'Director de Admon y Presupuesto',0,0,'C');
		$this->Cell(182/3,4,'Analista de Finanzas',0,0,'C');
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
$pdf->SetTitle('Comprobante de Pago');

// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',9);
$a=20;
$b=9;
$c=0;

//-----------------

//if ($pdf->GetY()<$y=205)
//	{
//	$y = $y - 0.5;
//	$pdf->Cell(8,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($a,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($a,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
//	$pdf->Cell(0,$y-$pdf->GetY(),'',1,1,'C',0);
//	}

if ($_SESSION['estatus']==99)	
	{
	$pdf->SetY(140);
	$pdf->SetTextColor(255,0,0);
	$pdf->SetFont('helvetica','',35);
	$pdf->Cell(0,5,'COMPROBANTE ANULADO',0,0,'C'); 
	$pdf->SetFont('Times','',10);
	$pdf->SetTextColor(0);
	}

$pdf->Output();
?>