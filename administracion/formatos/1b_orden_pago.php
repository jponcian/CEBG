<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once('../../lib/fpdf/fpdf.php');
require '../../vendor/autoload.php';

use Luecano\NumeroALetras\NumeroALetras;

$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

//-------------	
$id = decriptar($_GET['id']);
$consultax = "CALL actualizar_orden_pago_individual($id);"; //echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);

class PDF extends FPDF
{
	function Header()
	{
		$this->SetY(10);
		$id = decriptar($_GET['id']);

		$consultx = "SELECT ordenes_pago.usuario, ordenes_pago.id, ordenes_pago.id_contribuyente, ordenes_pago.tipo_solicitud, ordenes_pago.descripcion, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.total, ordenes_pago.estatus, ordenes_pago.num_comprobante, ordenes_pago.fecha_comprobante, contribuyente.nombre, contribuyente.rif, orden_solicitudes.anno FROM ordenes_pago , contribuyente , orden_solicitudes WHERE ordenes_pago.id = $id AND ordenes_pago.id_contribuyente = contribuyente.id AND orden_solicitudes.id_orden_pago = ordenes_pago.id LIMIT 1;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		//$tipo_solicitud = $registro->tipo_solicitud;
		$fecha = $registro->fecha;
		$anno = $registro->anno;
		$numero = $registro->numero;
		$descripcion = $registro->descripcion;
		$rif = $registro->rif;
		$contribuyente = $registro->nombre;
		//$asignaciones = $registro->asignaciones;
		//$_SESSION['retenciones'] = $registro->descuentos;
		$_SESSION['total'] = $registro->total;
		$_SESSION['estatus'] = $registro->estatus;
		$_SESSION['empleado'] = $registro->usuario;
		//--------------

		$this->SetFillColor(240);
		if (anno($fecha) < 2024) {
			$this->Image('../../images/logo_2023.jpg', 27, 7, 32);
		} else {
			$this->Image('../../images/logo_nuevo.jpg', 27, 7, 40);
		}

		$this->Image('../../images/bandera_linea.png', 17, 41, 182, 1);
		$this->SetFont('Times', '', 11);
		$this->SetDrawColor(200, 205, 210);
		$this->SetLineWidth(0.3);

		$municipio = 'Francisco de Miranda';
		// ---------------------
		//$this->SetY(12);
		////$instituto = instituto();
		$this->SetFont('Times', 'I', 11.5);
		$this->Cell(0, 5, utf8_decode('República Bolivariana de Venezuela'), 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 5, utf8_decode('Contraloría del Estado Bolivariano de Guárico'), 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 5, utf8_decode('RIF G-20001287-0 - Ejercicio Fiscal ') . $anno, 0, 0, 'C');
		$this->Ln(7);

		$this->SetFont('Times', 'B', 14);
		$this->Cell(0, 5, 'ORDEN DE PAGO FINANCIERA', 0, 0, 'C');
		$this->Ln(5);
		//$this->SetFont('Times','',10);
		//$this->Cell(0,5,'NOMINA',0,0,'C'); 
		$this->Ln(6);

		$y = $this->GetY();
		$this->SetY(20);
		//$this->SetX(150);
		$this->SetFont('Arial', 'B', 13);
		$this->SetTextColor(47, 111, 171);
		$this->Cell(0, 5, utf8_decode('N°: ') . rellena_cero($numero, 5), 0, 0, 'R');
		$this->Ln(7);
		$this->SetFont('Arial', 'B', 11);
		$this->SetTextColor(60, 60, 60);
		$this->Cell(0, 5, utf8_decode('Fecha: ') . voltea_fecha($fecha), 0, 0, 'R'); //$this->Ln(10);
		$this->SetTextColor(0);
		$this->SetY($y);

		$this->SetFont('Times', '', 10);
		$this->Cell(3, 5, '');
		$this->Cell(28, 5, 'BENEFICIARIO:', 0, 0, 'L');
		$this->SetFont('Times', 'B', 10);
		$this->MultiCell(112, 4, $contribuyente, 0);
		$this->SetY($y + 2);
		$this->Cell(28 + 118, 5, '');
		$this->SetFont('Times', '', 11);
		$this->Cell(7, 5, 'Rif:', 0, 0, 'L');
		$this->SetFont('Times', 'B', 11);
		$this->Cell(0, 5, formato_rif($rif), 0, 0, 'C');
		$this->SetFont('Times', '', 10);
		$this->Ln(6);
		$this->Cell(3, 5, '');
		$this->Cell(44, 5, 'AUTORIZADO A COBRAR:', 0, 0, 'L');
		$this->SetFont('Times', 'B', 10);
		$this->MultiCell(112, 4, $contribuyente, 0);
		$this->Ln(1);

		$this->SetFont('Times', '', 10);
		$this->SetFillColor(242, 244, 247);
		$this->Cell($a = 130, 6, utf8_decode('DESCRIPCIÓN O CONCEPTO DE LA ORDEN DE PAGO'), 1, 0, 'L', 1);
		$this->Cell(0, 6, utf8_decode('RETENCIONES'), 1, 0, 'C', 1);
		$this->Ln(6);

		$y = $this->GetY();
		$this->SetFont('Times', 'B', 9);
		$this->MultiCell($a, 4, $descripcion, 1, 'J');
		$y2 = $this->GetY();

		$this->SetY($y);

		//--------------
		$_SESSION['retenciones'] = 0;
		$consultx = "SELECT a_retenciones.corta, descuento, ordenes_pago_descuentos.porcentaje FROM ordenes_pago_descuentos , a_retenciones WHERE a_retenciones.id = ordenes_pago_descuentos.id_descuento AND id_orden_pago = $id;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro = $tablx->fetch_object()) {
			$this->Cell($a, 5, '');
			$this->Cell(24, 5, $registro->corta . ' ' . $registro->porcentaje . '%', 1, 0, 'R');
			$this->Cell(0, 5, formato_moneda($registro->descuento), 1, 0, 'R');
			$this->Ln(5);
			$_SESSION['retenciones'] = $_SESSION['retenciones'] + $registro->descuento;
		}

		$y3 = $this->GetY();

		if ($y2 > $y3) {
			$this->SetY($y2);
		}
		$this->Ln(2);
		//$this->SetFillColor(250);
		$this->SetFont('Times', 'B', 9.5);
		$this->SetFillColor(242, 244, 247);
		$this->Cell(8, 6, 'Item', 1, 0, 'C', 1);
		$this->Cell($a = 20, 6, utf8_decode('Compromiso'), 1, 0, 'C', 1);
		$this->Cell($a - 2, 6, utf8_decode('Categoría'), 1, 0, 'C', 1);
		$this->Cell($a + 2, 6, 'Partida', 1, 0, 'C', 1);
		$this->Cell($b = 90 - 8, 6, utf8_decode('Detalle'), 1, 0, 'C', 1);
		$this->Cell($c = 0, 6, 'Total', 1, 0, 'C', 1);
		$this->Ln();
	}

	function Footer()
	{
		$formatter = new NumeroALetras();
		//-------------------------------------------------

		$this->SetY(-73);
		$this->SetFillColor(245);
		$alto = 7;
		$this->Cell($a = 120, 6, 'MONTO A PAGAR EN LETRAS', 1, 0, 'L', 1);
		$y = $this->GetY();
		$this->Ln(6);
		$this->SetFont('Times', 'B', 9);
		if ($_SESSION['lineas'] == 0) {
			$this->MultiCell($a, 4, strtoupper($formatter->toMoney($_SESSION['monto'] - $_SESSION['retenciones'], 2, 'BOLIVARES', 'CENTIMOS')), 1);
		} else {
			$this->MultiCell($a, 12, '', 1);
		}
		$y2 = $this->GetY();

		$this->SetY($y);
		$this->Cell($a, 6, '');
		if ($this->PageNo() < '{paginas}') {
			$this->Cell(30, 6, 'Van... Bs->', 1, 0, 'R', 1);
		} else {
			$this->Cell(30, 6, 'Total... Bs->', 1, 0, 'R', 1);
		}
		$this->Cell(0, 6, formato_moneda($_SESSION['monto']), 1, 0, 'R');
		$this->Ln(6);
		$this->Cell($a, 6, '');
		$this->Cell(30, 6, 'Retenci�n Bs->', 1, 0, 'R', 1);
		$this->Cell(0, 6, formato_moneda($_SESSION['retenciones']), 1, 0, 'R');
		$this->Ln(6);
		$this->Cell($a, 6, '');
		$this->Cell(30, 6, 'Neto a Pagar Bs->', 1, 0, 'R', 1);
		$this->Cell(0, 6, formato_moneda($_SESSION['monto'] - $_SESSION['retenciones']), 1, 0, 'R');
		$this->Ln(6);
		$y3 = $this->GetY();

		if ($y2 > $y3) {
			$this->SetY($y2);
		}
		$this->Ln(2);

		//------------
		$a = 181.8;
		$this->Cell(0, 5, 'FIRMAS AUTORIZADAS', 1, 1, 'C', 1);

		$this->Cell($a / 5, 5, 'Elaborado por:', 1, 0, 'C', 1);
		$this->Cell($a / 5, 5, 'Revisado por:', 1, 0, 'C', 1);
		$this->Cell($a / 5, 5, 'Revisado por:', 1, 0, 'C', 1);
		$this->Cell($a / 5, 5, 'Autorizado por', 1, 0, 'C', 1);
		$this->Cell($a / 5, 5, 'Aprobado por:', 1, 1, 'C', 1);
		//------------
		$this->SetFont('Times', '', 8.5);
		$y = $this->GetY();
		$x = $this->GetX();
		$this->MultiCell($a / 5, 4, 'Analista del Area de Gestion de Pagos', 0, 'C');
		$this->SetXY($x + $a / 5, $y);
		$this->MultiCell($a / 5, 4, 'Analista de Presupuesto III', 0, 'C');
		$this->SetXY($x + ($a / 5) * 2, $y);
		$this->MultiCell($a / 5, 4, 'Jefe del Area de Finanzas', 0, 'C');
		$this->SetXY($x + ($a / 5) * 3, $y);
		$this->SetFont('Times', '', 8);
		$this->MultiCell($a / 5, 4, 'Director de Administracion y Presupuesto', 0, 'C');
		$this->SetXY($x + ($a / 5) * 4, $y);
		$this->MultiCell($a / 5, 4, 'Contralor (P) del Estado Bolivariano de Guarico', 0, 'C');

		$this->Ln(8);
		$this->SetFont('Times', 'B', 8.5);
		$this->Cell($a / 5, 6, 'Diana Enir� Barreto', 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, 'Leonardo David Flores', 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, 'Claudia Lisbeth Valerio', 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, 'Franklin Palacios', 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, 'Julio Cesar Paez', 0, 0, 'C', 0);
		$this->Ln(4);

		$this->SetFont('Times', '', 8.5);
		$this->Cell($a / 5, 6, '25.942.276', 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, '14.395.231', 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, '11.856.502', 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, '15.712.015', 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, '16.179.059', 0, 0, 'C', 0);
		$this->SetXY($x, $y);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		//--------------
		$this->SetFont('Times', 'I', 8);
		$this->SetY(-12);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80, 0, $_SESSION['CEDULA_USUARIO'], 0, 0, 'L');
		$this->Cell(0, 0, 'SIACEBG ' . $this->PageNo() . ' de {paginas}', 0, 0, 'R');
		if ($_SESSION['estatus'] == 99) {
			$this->SetY(140);
			$this->SetTextColor(255, 0, 0);
			$this->SetFont('helvetica', '', 35);
			$this->Cell(0, 5, 'ORDEN ANULADA', 0, 0, 'C');
			$this->SetFont('Times', '', 10);
			$this->SetTextColor(0);
		}
	}
}

$id = decriptar($_GET['id']);
//-------------	

// ENCABEZADO
$pdf = new PDF('P', 'mm', 'LETTER');
$pdf->AliasNbPages('{paginas}');
$pdf->SetMargins(17, 80, 17);
$pdf->SetAutoPageBreak(1, 73);
$pdf->SetTitle('Orden de Pago Financiera');

// ----------
$pdf->AddPage();
$pdf->SetFont('Times', '', 9);
$a = 20;
$b = 90 - 8;
$c = 0;

//-----------------
$consulta = "SELECT orden.categoria, orden.partida, sum(orden.total) as total, orden_solicitudes.numero, orden_solicitudes.tipo_orden FROM orden , orden_solicitudes , ordenes_pago WHERE ordenes_pago.id = '$id' AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden.id_solicitud = orden_solicitudes.id GROUP BY numero, categoria, partida;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$_SESSION['lineas'] = $tabla->num_rows;
//-----------------
$i = 1;
$_SESSION['monto'] = 0;
$alto = 5;
while ($registro = $tabla->fetch_object()) {
	// Asegurar trazo claro en el detalle
	$pdf->SetDrawColor(200, 205, 210);
	$pdf->SetLineWidth(0.3);
	if ($registro->tipo_orden == 'CC' or $registro->tipo_orden == 'CD' or $registro->tipo_orden == 'CP') {
		$letra = 'c';
	}
	//if ($registro->tipo_orden=='CD') {$letra='c';}
	//if ($registro->tipo_orden=='CP') {$letra='c';}
	if ($registro->tipo_orden == 'M') {
		$letra = 'm';
	}
	if ($registro->tipo_orden == 'F') {
		$letra = 'f';
	}
	//----------
	//$pdf->Cell($aa,$alto,$i+1,1,0,'C',0);
	$pdf->Cell(8, $alto, $i, 1, 0, 'C', 0);
	$pdf->Cell($a, $alto, rellena_cero($registro->numero, 6) . $letra, 1, 0, 'C', 0);
	$pdf->Cell($a - 2, $alto, $registro->categoria, 1, 0, 'C', 0);
	$pdf->Cell($a + 2, $alto, $registro->partida, 1, 0, 'C', 0);
	$pdf->Cell($b, $alto, 'SIN IMPUTACION PRESUPUESTARIA', 1, 0, 'L', 0);
	$pdf->SetFillColor(255);
	$pdf->Cell($c, $alto, formato_moneda($registro->total), 1, 1, 'R', 1);
	//-----------
	$_SESSION['monto'] = $_SESSION['monto'] + $registro->total;
	$_SESSION['lineas']--;
	$i++;
}

if ($pdf->GetY() < $y = 205) {
	$pdf->Cell(8, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
	$pdf->Cell($a, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
	$pdf->Cell($a - 2, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
	$pdf->Cell($a + 2, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
	$pdf->Cell($b, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
	$pdf->Cell(0, $y - $pdf->GetY(), '', 1, 1, 'C', 0);
}

$pdf->Output();
