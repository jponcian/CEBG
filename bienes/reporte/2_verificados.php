<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'utf8'");

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../index.php?errorusuario=val");
	exit();
}

class PDF extends FPDF
{
	function Footer()
	{
		$this->SetFont('Times', 'I', 8);
		$this->SetY(-15);
		$this->SetTextColor(120);
		//$this->Cell(0,5,'Resoluci�n '.($_GET['id']));
		//--------------
		$s = $this->PageNo();
		$this->Cell(0, 0, 'SIACEBG' . ' ' . $this->PageNo() . ' de {nb}', 0, 0, 'R');
		$this->SetY(-15);
		$this->Cell(0, 0, $_SESSION['CEDULA_USUARIO'] . ' ' . date('c'), 0, 0, 'L'); //d/m/Y h:i:s a 
	}

	// Dibuja texto recortado con '...' si no cabe en el ancho indicado (1 línea)
	function CellFitText($w, $h, $txt, $border = 0, $ln = 0, $align = 'L', $fill = false, $link = '')
	{
		$txt = trim((string)$txt);
		$ellipsis = '...'; // ASCII, compatible con FPDF (ISO-8859-1)
		$maxWidth = $w > 0 ? max(0, $w - 2 * $this->cMargin) : 0;
		if ($maxWidth <= 0) {
			return $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
		}
		$fullWidth = $this->GetStringWidth($txt);
		if ($fullWidth <= $maxWidth) {
			return $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
		}
		$ellipsisWidth = $this->GetStringWidth($ellipsis);
		if ($ellipsisWidth >= $maxWidth) {
			// Ni siquiera cabe '...': imprime elipsis sola
			return $this->Cell($w, $h, $ellipsis, $border, $ln, $align, $fill, $link);
		}
		// Búsqueda binaria para encontrar el mayor prefijo que quepa con '...'
		$lo = 0;
		$hi = strlen($txt);
		$best = 0;
		while ($lo <= $hi) {
			$mid = intdiv($lo + $hi, 2);
			$substr = substr($txt, 0, $mid);
			$wSub = $this->GetStringWidth($substr) + $ellipsisWidth;
			if ($wSub <= $maxWidth) {
				$best = $mid;
				$lo = $mid + 1;
			} else {
				$hi = $mid - 1;
			}
		}
		$trimmed = ($best > 0 ? substr($txt, 0, $best) : '') . $ellipsis;
		return $this->Cell($w, $h, $trimmed, $border, $ln, $align, $fill, $link);
	}

	// Ajuste horizontal: escala o ajusta espaciado para que el texto quepa exactamente en el ancho
	function CellFit($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $scale = false, $force = true)
	{
		// Ancho del texto actual
		$str_width = $this->GetStringWidth($txt);

		// Si no se indica ancho, usar el disponible hasta el margen derecho
		if ($w == 0)
			$w = $this->w - $this->rMargin - $this->x;

		// Ratio para ajustar al ancho interno de la celda (restando márgenes internos)
		$ratio = ($w - 2 * $this->cMargin) / ($str_width ?: 1);
		$fit = ($ratio < 1 || ($ratio > 1 && $force));
		if ($fit) {
			if ($scale) {
				// Escalado horizontal (Tz)
				$horiz_scale = $ratio * 100.0;
				$this->_out(sprintf('BT %.2F Tz ET', $horiz_scale));
			} else {
				// Espaciado entre caracteres (Tc)
				$char_space = ($w - 2 * $this->cMargin - $str_width) / max(strlen($txt) - 1, 1) * $this->k;
				$this->_out(sprintf('BT %.2F Tc ET', $char_space));
			}
			// Forzar alineación por defecto para llenar la celda
			$align = '';
		}

		// Pasar a Cell estándar
		$this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);

		// Resetear el ajuste aplicado
		if ($fit)
			$this->_out('BT ' . ($scale ? '100 Tz' : '0 Tc') . ' ET');
	}

	// Escalado horizontal solo si es necesario (no fuerza a ampliar)
	function CellFitScale($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
	{
		$this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, true, false);
	}
}

// ENCABEZADO
$pdf = new PDF('L', 'mm', 'LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(12, 15, 12);
$pdf->SetAutoPageBreak(1, 17);
$pdf->SetTitle('Relacion de Bienes');

// ----------
$pdf->AddPage();
$pdf->SetFillColor(2, 117, 216);
$pdf->Image('../../images/logo_nuevo.jpg', 20, 10, 33);
////$pdf->Image('../../images/escudo.jpg',165,12,26);
//$pdf->Image('../../images/logo_web.png',100,80,100);
$pdf->SetFont('Times', '', 11);

// ---------------------
//$pdf->SetY(12);
//$instituto = instituto();
$pdf->SetFont('Times', 'I', 11.5);
$pdf->Cell(0, 5, utf8_decode('República Bolivariana de Venezuela'), 0, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(0, 5, utf8_decode('Contraloría del Estado Bolivariano de Guárico'), 0, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(0, 5, utf8_decode('Dirección de Bienes, Materiales, Suministros y Archivo'), 0, 0, 'C');
$pdf->Ln(10);

$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(0, 5, utf8_decode('RELACIÓN DE BIENES'), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode($_SESSION['titulo']), 0, 0, 'C');
$pdf->Ln(7);

$pdf->SetTextColor(255);
$pdf->SetFont('Times', 'B', 10.5);
$pdf->Cell($aa = 9, 7, 'Item', 1, 0, 'C', 1);
$pdf->Cell($a = 50, 7, 'Dependencia', 1, 0, 'L', 1);
$pdf->Cell($b = 14, 7, 'Bien', 1, 0, 'C', 1);
$pdf->Cell($c = 160, 7, utf8_decode('Descripción'), 1, 0, 'L', 1);
$pdf->Cell($d = 0, 7, 'Estatus', 1, 1, 'C', 1);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i = 0;
//-----------------
$_SESSION['estatus'] = array('Por Verificar', 'Verificado');
$tabla = $_SESSION['conexionsql']->query($_SESSION['consulta']);
//-----------------
$i = 0;
$monto = 0;
while ($registro = $tabla->fetch_object()) {
	$pdf->SetFont('Times', '', 8.5);
	if ($i % 2 == 0) {
		$pdf->SetFillColor(255);
	} else {
		$pdf->SetFillColor(235);
	}
	//----------
	$pdf->Cell($aa, 5.5, $i + 1, 1, 0, 'C', 1);
	$pdf->SetFont('Times', '', 7);
	$pdf->CellFitScale($a, 5.5, utf8_decode($registro->division), 1, 0, 'L', 1);
	$pdf->SetFont('Times', '', 8.5);
	$numeroBien = str_pad((string)$registro->numero_bien, 6, '0', STR_PAD_LEFT);
	$pdf->Cell($b, 5.5, $numeroBien, 1, 0, 'C', 1);
	$pdf->SetFont('Times', '', 7);
	$pdf->CellFitScale($c, 5.5, utf8_decode($registro->descripcion_bien), 1, 0, 'L', 1);
	$pdf->SetFont('Times', '', 8.5);
	$pdf->Cell($d, 5.5, utf8_decode($_SESSION['estatus'][($registro->revisado)]), 1, 0, 'C', 1);

	$pdf->Ln(5.5);
	//-----------
	$i++;
}

//$pdf->SetFont('Times','B',12);
//$pdf->SetFillColor(230);
//$pdf->Cell(0,6,'TOTAL => '.formato_moneda($monto),1,0,'R',1);
//
$pdf->Output();
