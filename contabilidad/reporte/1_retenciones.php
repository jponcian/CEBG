<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
//$_SESSION['conexionsql']->mysql_query("SET NAMES 'utf8'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Header()
	{
	$fecha = ($_GET['fecha1']);
	$fechaf = ($_GET['fecha2']);
	$fecha1 = voltea_fecha($_GET['fecha1']);
	$fecha2 = voltea_fecha($_GET['fecha2']);
	$tipo = ($_GET['tipo']);
	
	$consulta = "SELECT * FROM a_retenciones WHERE id = $tipo;";
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$registro = $tabla->fetch_object();
	$rif_beneficiario = $registro->rif_beneficiario;
	$beneficiario = $registro->beneficiario;
	$titulo = $registro->decripcion;

	$this->SetFillColor(230);
	$this->Image('../../images/logo_nuevo.jpg',20,10,35);
	$this->Image('../../images/escudo.jpg',164,10,26);
	//$this->Image('../../images/logo_web.png',100,80,100);
	$this->SetFont('Times','',11);

	// ---------------------
	//$instituto = instituto();
	$this->SetY(20);
	$this->SetFont('Times','I',11.5);
	$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Rif G-20001287-0',0,0,'C'); 
	$this->Ln(8);

	//$this->SetFont('Times','',12);
	//$this->Cell(0,5,'Relación de Planillas en Sistema por concepto de:',0,0,'C'); $this->Ln(6);
	$this->SetFont('Times','B',12);
	$this->Cell(0,5,'Retenciones por: '.$titulo,0,0,'C'); $this->Ln(6);
	$this->Cell(0,5,'del '.($fecha).' al '.($fechaf),0,0,'C'); 
	$this->Ln(10);

	$y=$this->GetY();
	$this->SetFont('Times','',10);
	$this->Cell(150,5,'',0,0,'L');
	$this->Cell(7,5,'Rif:',0,0,'L');
	$this->SetFont('Times','B',10);
	$this->Cell(0,5,formato_rif($rif_beneficiario),0,0,'C'); 

	$this->SetY($y);
	$this->SetFont('Times','',10);
	$this->Cell(3,5,''); 
	$this->Cell(28,5,'BENEFICIARIO:',0,0,'L'); 
	$this->SetFont('Times','B',10);
	$this->MultiCell(113,5,$beneficiario,0); 
	$this->Ln(5);
	//-----------
	if ($this->PageNo()>1)
		{
		$this->SetFont('Times','B',10);
		$this->Cell(30,7,'Pagos por el Banco => '.$_SESSION['banco'].' desde la Cuenta => '.$_SESSION['cta'],0,0,'L',0);
		$this->Ln(8);

		$this->SetFont('Times','B',10);
		$this->Cell($_SESSION['aa']=10,7,'Item',1,0,'C',1);
		$this->Cell($_SESSION['a']=17,7,'Orden P',1,0,'C',1);
		$this->Cell($_SESSION['b']=20,7,'Rif',1,0,'C',1);
		$this->Cell($_SESSION['c']=80,7,'Contribuyente',1,0,'C',1);
		$this->Cell($_SESSION['e']=10,7,'Tipo',1,0,'C',1);
		//$this->Cell($_SESSION['f']=12,7,'N#',1,0,'C',1);
		$this->Cell($_SESSION['g']=18,7,'Fecha P',1,0,'C',1);
		$this->Cell($_SESSION['d']=0,7,'Retenido',1,1,'C',1);
		}	
	}
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-13);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
	}	
}

$fecha = ($_GET['fecha1']);
$fechaf = ($_GET['fecha2']);
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
$tipo = ($_GET['tipo']);

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,12,17);
$pdf->SetAutoPageBreak(1,23);
$pdf->SetTitle('Retenciones Realizadas');
$pdf->SetFillColor(230);

$pdf->AddPage();
$pdf->SetFont('Times','B',12);	
$pdf->Cell(0,6,'RESUMEN DE PAGOS POR CUENTAS',0,0,'C',0);
$pdf->Ln(10);
$pdf->SetFont('Times','B',11);	

// ----------
$pdf->Cell(40,7,'Banco',1,0,'C',1);
$pdf->Cell($b=40,7,'Cuenta',1,0,'C',1);
$pdf->Cell(35,7,'Total Orden Pago',1,0,'C',1);
$pdf->Cell(35,7,'Total Pagado',1,0,'C',1);
$pdf->Cell(0,7,'Retencion',1,0,'C',1);
$pdf->Ln(7);
// ----------

$consulta1 = "SELECT
	sum(ordenes_pago.asignaciones) AS total,
	sum(ordenes_pago_descuentos.descuento) AS impuesto,
	sum( ordenes_pago_pagos.monto ) AS pagado,
	ordenes_pago_pagos.banco,
	ordenes_pago_pagos.cuenta 
FROM
	ordenes_pago_descuentos,
	a_retenciones,
	ordenes_pago,
	ordenes_pago_pagos,
	contribuyente 
WHERE
	ordenes_pago.estatus >= 10 
	AND ordenes_pago.estatus <> 99 
	AND ordenes_pago.id = ordenes_pago_pagos.id_orden 
	AND ordenes_pago_descuentos.id_descuento = a_retenciones.id 
	AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id 
	AND ordenes_pago.id_contribuyente = contribuyente.id 
	AND ordenes_pago.fecha >= '$fecha1' 
	AND ordenes_pago.fecha <= '$fecha2'
	AND a_retenciones.id = $tipo 
GROUP BY
	ordenes_pago_pagos.banco,
	ordenes_pago_pagos.cuenta";
//echo $consulta1;
$tabla1 = $_SESSION['conexionsql']->query($consulta1);
//-----------------
$pdf->SetFont('Times','',10);	
$pdf->SetFillColor(255);

while ($registro1 = $tabla1->fetch_object())
	{
	$impuesto += $registro1->impuesto;
	// ----------
	$pdf->Cell(40,7,($registro1->banco),1,0,'L',0);
	$pdf->Cell($b,7,$registro1->cuenta,1,0,'C',0);
	$pdf->Cell(35,7,formato_moneda($registro1->total),1,0,'R',0);
	$pdf->Cell(35,7,formato_moneda($registro1->pagado),1,0,'R',0);
	$pdf->Cell(0,7,formato_moneda($registro1->impuesto),1,0,'R',0);
	// ----------
	$pdf->Ln(7);
	}

$pdf->SetFont('Times','B',12);
//$pdf->SetFillColor(230);
$pdf->Cell($e,7,'Total Retenciones Bs => '.formato_moneda($impuesto),1,1,'R',1);

//--------- SEGUNDA PARTE
$consulta1 = "SELECT
	ordenes_pago.asignaciones AS total,
	ordenes_pago_descuentos.descuento AS impuesto,
	sum( ordenes_pago_pagos.monto ) AS pagado,
	ordenes_pago_pagos.banco,
	ordenes_pago_pagos.cuenta 
FROM
	ordenes_pago_descuentos,
	a_retenciones,
	ordenes_pago,
	ordenes_pago_pagos,
	contribuyente 
WHERE
	ordenes_pago.estatus >= 10 
	AND ordenes_pago.estatus <> 99 
	AND ordenes_pago.id = ordenes_pago_pagos.id_orden 
	AND ordenes_pago_descuentos.id_descuento = a_retenciones.id 
	AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id 
	AND ordenes_pago.id_contribuyente = contribuyente.id 
	AND ordenes_pago.fecha >= '$fecha1' 
	AND ordenes_pago.fecha <= '$fecha2'
	AND a_retenciones.id = $tipo 
GROUP BY
	ordenes_pago_pagos.banco,
	ordenes_pago_pagos.cuenta;";
//echo $consulta1;
$tabla1 = $_SESSION['conexionsql']->query($consulta1);
//-----------------
$i1=0; $monto1=0;
while ($registro1 = $tabla1->fetch_object())
	{
$_SESSION['banco'] = $registro1->banco;
$_SESSION['cta'] = $registro1->cuenta;
$cta = $registro1->cuenta;
// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',9);	
$pdf->SetFillColor(255);
//-----------------
$consulta = "SELECT
	ordenes_pago_pagos.tipo_pago,
	ordenes_pago_pagos.chequera,
	ordenes_pago_pagos.num_pago,
	ordenes_pago_pagos.fecha_pago,
	a_retenciones.decripcion,
	a_retenciones.corta,
	ordenes_pago_descuentos.descuento,
	ordenes_pago_descuentos.porcentaje,
	ordenes_pago.iva,
	a_retenciones.id,
	ordenes_pago.total,
	ordenes_pago.asignaciones,
	ordenes_pago.numero,
	contribuyente.nombre,
	contribuyente.rif 
FROM
	ordenes_pago_descuentos,
	a_retenciones,
	ordenes_pago,
	ordenes_pago_pagos,
	contribuyente 
WHERE
	ordenes_pago_pagos.cuenta = '$cta' 
	AND ordenes_pago.id = ordenes_pago_pagos.id_orden 
	AND ordenes_pago.estatus >= 10 
	AND ordenes_pago.estatus <> 99 
	AND ordenes_pago_descuentos.id_descuento = a_retenciones.id 
	AND ordenes_pago_descuentos.id_orden_pago = ordenes_pago.id 
	AND ordenes_pago.id_contribuyente = contribuyente.id 
	AND ordenes_pago.fecha >= '$fecha1' 
	AND ordenes_pago.fecha <= '$fecha2' 
	AND a_retenciones.id = $tipo 
GROUP BY
	ordenes_pago.id
ORDER BY
	ordenes_pago.fecha ASC,
	ordenes_pago.numero ASC;";
//echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
	{
	//----------
	$pdf->Cell($_SESSION['aa'],6,$i+1,1,0,'C',0);
	$pdf->Cell($_SESSION['a'],6,rellena_cero($registro->numero,6),1,0,'C',0);
	$pdf->Cell($_SESSION['b'],6,$registro->rif,1,0,'C',0);
	$pdf->Cell($_SESSION['c'],6,$registro->nombre,1,0,'L',0);
	$pdf->Cell($_SESSION['e'],6,$_SESSION['tipo_pago'][($registro->tipo_pago)],1,0,'C',1);
	//$pdf->Cell($_SESSION['f'],6,($registro->num_pago),1,0,'C',1);
	$pdf->Cell($_SESSION['g'],6,voltea_fecha($registro->fecha_pago),1,0,'C',1);
	$pdf->Cell($_SESSION['d'],6,formato_moneda($registro->descuento),1,0,'R',1);
	$pdf->Ln(6);
	$monto = $monto + $registro->descuento;
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',12);
//$pdf->SetFillColor(230);
$pdf->Cell($e,7,'Total Bs => '.formato_moneda($monto),1,1,'R',1);
	}
$pdf->Output();
?>