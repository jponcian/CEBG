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
	// Paleta sutil y utilidades sin alterar layout
	private $accent = [47, 111, 171]; // azul sobrio
	private $grid = [200, 205, 210];  // gris líneas
	private $headerFill = [242, 244, 247]; // gris claro para encabezados

	private function t($s)
	{
		// FPDF espera ISO-8859-1; aseguramos acentos correctos
		if ($s === null) return '';
		return utf8_decode($s);
	}

	private function shadowRect($x, $y, $w, $h, $ox = 1.0, $oy = 1.0)
	{
		// Sombra ligera, no desplaza contenido
		$this->SetFillColor(230, 232, 235);
		$this->Rect($x + $ox, $y + $oy, $w, $h, 'F');
	}

	private function titleCaseEs($s)
	{
		if ($s === null) return '';
		// Convertir a UTF-8 (DB latin1) y luego a Título
		$u = utf8_encode($s);
		$u = mb_convert_case(mb_strtolower($u, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
		// Palabras menores en minúscula salvo si son la primera
		$min = ['de', 'del', 'la', 'las', 'los', 'y', 'e', 'o', 'u', 'a', 'en', 'para', 'por', 'con', 'sin', 'al', 'el'];
		$parts = preg_split('/\s+/', $u);
		foreach ($parts as $i => $w) {
			$wl = mb_strtolower($w, 'UTF-8');
			if ($i > 0 && in_array($wl, $min, true)) {
				$parts[$i] = $wl;
			}
		}
		return implode(' ', $parts);
	}
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
		$_SESSION['fecha'] = $fecha;
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
		// Líneas claras y delgadas para bordes de celdas
		$this->SetDrawColor(200, 205, 210);
		$this->SetLineWidth(0.3);

		$municipio = 'Francisco de Miranda';
		// ---------------------
		//$this->SetY(12);
		////$instituto = instituto();
		$this->SetFont('Times', 'B', 9.5);
		$this->Cell(0, 5, $this->t('República Bolivariana de Venezuela'), 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 5, $this->t('Contraloría del Estado Bolivariano de Guárico'), 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 5, $this->t('Dirección de Administración y Presupuesto'), 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 5, $this->t('RIF G-20001287-0 - Ejercicio Fiscal ') . $anno, 0, 0, 'C');
		$this->Ln(7);

		$this->SetFont('Times', 'B', 14);
		$this->SetTextColor($this->accent[0], $this->accent[1], $this->accent[2]);
		$this->Cell(0, 5, $this->t('ORDEN DE PAGO'), 0, 0, 'C');
		$this->SetTextColor(0);
		$this->Ln(5);
		//$this->SetFont('Times','',10);
		//$this->Cell(0,5,'NOMINA',0,0,'C'); 
		$this->Ln(6);

		$y = $this->GetY();
		$this->SetY(20);
		//$this->SetX(150);
		$this->SetFont('Arial', 'B', 13);
		$this->SetTextColor($this->accent[0], $this->accent[1], $this->accent[2]);
		$this->Cell(0, 5, $this->t('N°: ') . rellena_cero($numero, 5), 0, 0, 'R');
		$this->Ln(7);
		$this->SetFont('Arial', 'B', 11);
		$this->SetTextColor(60, 60, 60);
		$this->Cell(0, 5, $this->t('Fecha: ') . voltea_fecha($fecha), 0, 0, 'R'); //$this->Ln(10);
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
		$this->SetDrawColor($this->grid[0], $this->grid[1], $this->grid[2]);
		$this->SetLineWidth(0.3);
		$this->SetFillColor($this->headerFill[0], $this->headerFill[1], $this->headerFill[2]);
		$this->Cell($a = 130, 6, $this->t('DESCRIPCIÓN O CONCEPTO DE LA ORDEN DE PAGO'), 1, 0, 'L', 1);
		$this->Cell(0, 6, $this->t('RETENCIONES'), 1, 0, 'C', 1);
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
		$this->SetFillColor($this->headerFill[0], $this->headerFill[1], $this->headerFill[2]);
		$this->Cell(8, 6, $this->t('Item'), 1, 0, 'C', 1);
		$this->Cell($a = 20, 6, $this->t('Compromiso'), 1, 0, 'C', 1);
		$this->Cell($a - 2, 6, $this->t('Categoría'), 1, 0, 'C', 1);
		$this->Cell($a + 2, 6, $this->t('Partida'), 1, 0, 'C', 1);
		$this->Cell($b = 90 - 8, 6, $this->t('Detalle'), 1, 0, 'C', 1);
		$this->Cell($c = 0, 6, $this->t('Total'), 1, 0, 'C', 1);
		$this->Ln();
	}

	function Footer()
	{
		$formatter = new NumeroALetras();
		//-------------------------------------------------
		$this->SetY(-73);
		// Asegurar líneas claras y delgadas en toda la sección inferior
		$this->SetDrawColor(200, 205, 210);
		$this->SetLineWidth(0.3);
		$this->SetFillColor(245);
		$alto = 7;
		$this->SetFillColor($this->headerFill[0], $this->headerFill[1], $this->headerFill[2]);
		$this->Cell($a = 120, 6, $this->t('MONTO A PAGAR EN LETRAS'), 1, 0, 'L', 1);
		$y = $this->GetY();
		$this->Ln(6);
		$this->SetFont('Times', 'B', 9);
		if ($_SESSION['lineas'] == 0) {
			$this->MultiCell($a, 4, $this->t(strtoupper($formatter->toMoney($_SESSION['monto'] - $_SESSION['retenciones'], 2, 'BOLIVARES', 'CÉNTIMOS'))), 1);
		} else {
			$this->MultiCell($a, 12, '', 1);
		}
		$y2 = $this->GetY();

		$this->SetY($y);
		$this->Cell($a, 6, '');
		if ($this->PageNo() < '{paginas}') {
			$this->Cell(30, 6, $this->t('Van... Bs->'), 1, 0, 'R', 1);
		} else {
			$this->Cell(30, 6, $this->t('Total... Bs->'), 1, 0, 'R', 1);
		}
		$this->Cell(0, 6, formato_moneda($_SESSION['monto']), 1, 0, 'R');
		$this->Ln(6);
		$this->Cell($a, 6, '');
		$this->Cell(30, 6, $this->t('Retención Bs->'), 1, 0, 'R', 1);
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
		$this->Cell(0, 5, $this->t('FIRMAS AUTORIZADAS'), 1, 1, 'C', 1);

		$this->Cell($a / 5, 5, 'Elaborado por:', 1, 0, 'C', 1);
		$this->Cell($a / 5, 5, 'Revisado por:', 1, 0, 'C', 1);
		$this->Cell($a / 5, 5, 'Revisado por:', 1, 0, 'C', 1);
		$this->Cell($a / 5, 5, 'Autorizado por', 1, 0, 'C', 1);
		$this->Cell($a / 5, 5, 'Aprobado por:', 1, 1, 'C', 1);

		//----firmas
		$firma1 = firma_op(1, $_SESSION['fecha']);
		$firma2 = firma_op(2, $_SESSION['fecha']);
		$firma3 = firma_op(3, $_SESSION['fecha']);
		$firma4 = firma_op(4, $_SESSION['fecha']);
		$firma5 = firma_op(5, $_SESSION['fecha']);

		//------------
		$this->SetFont('Times', '', 7.5);
		$y1 = $this->GetY();
		$x1 = $this->GetX();
		$y = $this->GetY();
		$x = $this->GetX();
		$this->MultiCell($a / 5, 4, ($this->t($this->titleCaseEs($firma1[2]))), 0, 'C');
		$this->SetXY($x + $a / 5, $y);
		$this->MultiCell($a / 5, 4, ($this->t($this->titleCaseEs($firma2[2]))), 0, 'C');
		$this->SetXY($x + ($a / 5) * 2, $y);
		$this->MultiCell($a / 5, 4, ($this->t($this->titleCaseEs($firma3[2]))), 0, 'C');
		$this->SetXY($x + ($a / 5) * 3, $y);
		$this->SetFont('Times', '', 8);
		$this->MultiCell($a / 5, 4, ($this->t($this->titleCaseEs($firma4[2]))), 0, 'C');
		$this->SetXY($x + ($a / 5) * 4, $y);
		$this->MultiCell($a / 5, 4, ($this->t($this->titleCaseEs($firma5[2]))), 0, 'C');
		$this->Ln(6);

		$this->SetFont('Times', '', 8.5);
		$y = $this->GetY();
		$x = $this->GetX();
		$this->MultiCell($a / 5, 4, $firma1[1], 0, 'C');
		$this->SetXY($x + $a / 5, $y);
		$this->MultiCell($a / 5, 4, $firma2[1], 0, 'C');
		$this->SetXY($x + ($a / 5) * 2, $y);
		$this->MultiCell($a / 5, 4, $firma3[1], 0, 'C');
		$this->SetXY($x + ($a / 5) * 3, $y);
		$this->SetFont('Times', '', 8);
		$this->MultiCell($a / 5, 4, $firma4[1], 0, 'C');
		$this->SetXY($x + ($a / 5) * 4, $y);
		$this->MultiCell($a / 5, 4, $firma5[1], 0, 'C');

		$this->SetXY($x1, $y1 + 20);
		$this->SetFont('Times', '', 8.5);
		$this->Cell($a / 5, 6, formato_cedula($firma1[0]), 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, formato_cedula($firma2[0]), 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, formato_cedula($firma3[0]), 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, formato_cedula($firma4[0]), 0, 0, 'C', 0);
		$this->Cell($a / 5, 6, formato_cedula($firma5[0]), 0, 0, 'C', 0);

		// Sin sombra detrás de las cajas de firmas para no tapar texto
		$this->SetXY($x1, $y1);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		$this->Cell($a / 5, 25, '', 1, 0, 'C', 0);
		//--------------
		$this->SetFont('Times', 'I', 8);
		// Ubicar pie de página en el borde inferior
		$this->SetY(-12);
		$this->SetTextColor(120);
		// Líneas claras y delgadas en totales y firmas
		$this->SetDrawColor(200, 205, 210);
		$this->SetLineWidth(0.3);
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
$pdf->SetTitle('Orden de Pago');

// ----------
$pdf->AddPage();
$pdf->SetFont('Times', '', 9);
$a = 20;
$b = 90 - 8;
$c = 0;

//-----------------
$consulta = "SELECT orden.categoria, orden.partida, sum(orden.total) as total, orden_solicitudes.numero, orden_solicitudes.tipo_orden, left(a_partidas.descripcion,80) as descripcion FROM orden , orden_solicitudes , ordenes_pago , a_partidas WHERE ordenes_pago.id = '$id' AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden.id_solicitud = orden_solicitudes.id AND orden.partida = a_partidas.codigo GROUP BY numero, categoria, partida;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$_SESSION['lineas'] = $tabla->num_rows;
//-----------------
$i = 1;
$_SESSION['monto'] = 0;
$letra = ''; // Initialize $letra
$alto = 5;
while ($registro = $tabla->fetch_object()) {
	// Asegurar líneas claras en todas las celdas del detalle
	$pdf->SetDrawColor(200, 205, 210);
	$pdf->SetLineWidth(0.3);
	if ($registro->tipo_orden == 'CC' or $registro->tipo_orden == 'CD' or $registro->tipo_orden == 'CP') {
		$letra = 'c';
	}
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
	$pdf->Cell($b, $alto, ucfirst(strtolower($registro->descripcion)), 1, 0, 'L', 0);
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
