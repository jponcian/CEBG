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

$id = decriptar($_GET['id']);
$consultx = "UPDATE ordenes_pago SET estatus=10 WHERE id = $id AND estatus<10 AND estatus<>99;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

class PDF extends FPDF
{
	function Header()
	{    
		$id = decriptar($_GET['id']);
		$consultx = "SELECT ordenes_pago.fecha, ordenes_pago.total, contribuyente.nombre, contribuyente.rif, ced_representante FROM ordenes_pago , contribuyente , orden_solicitudes WHERE ordenes_pago.id = $id AND ordenes_pago.id_contribuyente = contribuyente.id AND orden_solicitudes.id_orden_pago = ordenes_pago.id LIMIT 1;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		
		if (strtoupper(substr($registro->rif,0,1))=='J')
			{	$rif = formato_rif($registro->rif);	}
		else	{	$rif = formato_ci($registro->ced_representante);	}
		//-------------
		$contribuyente = $registro->nombre;
		$fecha = $registro->fecha;
		$_SESSION['total']= $registro->total;
		//--------------
		
		$this->SetY(140);
		$this->SetFont('Arial','B',11);
		$this->Cell(152,5,formato_moneda($_SESSION['total']),0,0,'R'); 
		$this->Ln(11.5);
		$this->Cell(5,5,''); 
		$this->Cell(0,5,' '.($rif).' '.$contribuyente,0,0,'L'); 
		$this->Ln(9);
		$this->SetFont('Arial','B',10);
		$this->Cell(10,5,''); 
		$this->Multicell(0,5,strtoupper(valorEnLetras($_SESSION['total'])),0,'L'); 
		$this->SetY(175);
		$this->SetFont('Arial','B',10);
		$this->Cell(0,5,'Calabozo Estado Guarico, '.fecha_larga($fecha),0,0,'L'); 
		$this->SetFont('courier','',10);
		$this->Ln(15);
		$this->Cell(40,5,'');
		$this->Cell(0,5,'NO ENDOSABLE',0,0,'C'); 
		$this->Ln(5);
		$this->Cell(40,5,'');
		$this->Cell(0,5,'CADUCA A LOS 60 DIAS',0,0,'C'); 
	}	
}

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(17,80,17);
$pdf->SetAutoPageBreak(1,73);
$pdf->SetTitle('Cheque');
// ----------
$pdf->AddPage();

$pdf->Output();
?>