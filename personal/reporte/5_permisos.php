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

class CellPDF extends FPDF
{
	function Header()
	{
		$tipo = decriptar($_GET['tipo']);
		if ($tipo == 0) {
			$tipo = "(TODOS)";
		} elseif ($tipo == 1) {
			$tipo = "PERMISOS GENERADOS";
		} elseif ($tipo == 2) {
			$tipo = "REPOSOS GENERADOS";
		} elseif ($tipo == 3) {
			$tipo = "VACACIONES GENERADAS";
		}

		$desde = voltea_fecha($_GET['desde']);
		$hasta = voltea_fecha($_GET['hasta']);
		$txt1 = "FECHA " . date('d/m/Y');
		$txt2 = "DESDE EL " . $desde . " AL " . $hasta;

		$this->SetFillColor(2, 117, 216);
		$this->Image('../../images/logo_nuevo.jpg', 30, 10, 35);
		$x = $this->GetX();
		$y = $this->GetY();
		$this->SetXY(200, 25);
		$this->SetFont('Times', 'B', 11.5);
		$this->Cell(0, 7, utf8_decode($txt1), 0, 0, 'R', 0);
		$this->SetXY($x, $y);

		$this->SetFont('Times', 'I', 11.5);
		$this->Cell(0, 5, utf8_decode('República Bolivariana de Venezuela'), 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 5, utf8_decode('Contraloría del Estado Bolivariano de Guárico'), 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 5, utf8_decode('Dirección de Talento Humano'), 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 5, 'Rif G-20001287-0', 0, 0, 'C');
		$this->Ln(8);

		$this->SetFont('Times', 'B', 11);
		$this->Cell(0, 5, utf8_decode("RELACIÓN DE $tipo"), 0, 0, 'C');
		$this->Ln(7);
		$this->Cell(0, 5, utf8_decode($txt2), 0, 0, 'C');
		$this->Ln(7);

		$this->SetTextColor(255);
		$this->SetFont('Times', 'B', 10.5);
		$this->Cell($aa = 9, 7, 'Item', 1, 0, 'C', 1);
		$this->Cell($d = 51, 7, utf8_decode('Dirección'), 1, 0, 'C', 1);
		$this->Cell($a = 18, 7, utf8_decode('Cédula'), 1, 0, 'C', 1);
		$this->Cell($b = 50, 7, 'Empleado', 1, 0, 'C', 1);
		$this->Cell($c = 51, 7, 'Cargo', 1, 0, 'C', 1);
		$this->Cell($e = 22, 7, 'Salida', 1, 0, 'C', 1);
		$this->Cell($e, 7, utf8_decode('Culminación'), 1, 0, 'C', 1);
		$this->Cell($e2 = 20, 7, utf8_decode('Incorporación'), 1, 0, 'C', 1);
		$this->Cell(0, 7, utf8_decode('Días'), 1, 1, 'C', 1);
	}

	function Footer()
	{
		$this->SetFont('Times', 'I', 8);
		$this->SetY(-18);
		$this->SetTextColor(120);
		//$this->Cell(0,5,'Resolución '.($_GET['id']));
		$this->Cell(0, 0, utf8_decode('SIACEBG' . ' ' . $this->PageNo() . ' de {nb}'), 0, 0, 'R');
	}

	function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
	{
		//Output a cell
		$k = $this->k;
		if ($this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()) {
			//Automatic page break
			$x = $this->x;
			$ws = $this->ws;
			if ($ws > 0) {
				$this->ws = 0;
				$this->_out('0 Tw');
			}
			$this->AddPage($this->CurOrientation, $this->CurPageSize);
			$this->x = $x;
			if ($ws > 0) {
				$this->ws = $ws;
				$this->_out(sprintf('%.3F Tw', $ws * $k));
			}
		}
		if ($w == 0)
			$w = $this->w - $this->rMargin - $this->x;
		$s = '';
		// begin change Cell function
		if ($fill || $border > 0) {
			if ($fill)
				$op = ($border > 0) ? 'B' : 'f';
			else
				$op = 'S';
			if ($border > 1) {
				$s = sprintf(
					'q %.2F w %.2F %.2F %.2F %.2F re %s Q ',
					$border,
					$this->x * $k,
					($this->h - $this->y) * $k,
					$w * $k,
					-$h * $k,
					$op
				);
			} else
				$s = sprintf('%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
		}
		if (is_string($border)) {
			$x = $this->x;
			$y = $this->y;
			if (is_int(strpos($border, 'L')))
				$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);
			else if (is_int(strpos($border, 'l')))
				$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);

			if (is_int(strpos($border, 'T')))
				$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);
			else if (is_int(strpos($border, 't')))
				$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);

			if (is_int(strpos($border, 'R')))
				$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
			else if (is_int(strpos($border, 'r')))
				$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);

			if (is_int(strpos($border, 'B')))
				$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
			else if (is_int(strpos($border, 'b')))
				$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
		}
		if (trim($txt) != '') {
			$cr = substr_count($txt, "\n");
			if ($cr > 0) { // Multi line
				$txts = explode("\n", $txt);
				$lines = count($txts);
				for ($l = 0; $l < $lines; $l++) {
					$txt = $txts[$l];
					$w_txt = $this->GetStringWidth($txt);
					if ($align == 'R')
						$dx = $w - $w_txt - $this->cMargin;
					elseif ($align == 'C')
						$dx = ($w - $w_txt) / 2;
					else
						$dx = $this->cMargin;

					$txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
					if ($this->ColorFlag)
						$s .= 'q ' . $this->TextColor . ' ';
					$s .= sprintf(
						'BT %.2F %.2F Td (%s) Tj ET ',
						($this->x + $dx) * $k,
						($this->h - ($this->y + .5 * $h + (.7 + $l - $lines / 2) * $this->FontSize)) * $k,
						$txt
					);
					if ($this->underline)
						$s .= ' ' . $this->_dounderline($this->x + $dx, $this->y + .5 * $h + .3 * $this->FontSize, $txt);
					if ($this->ColorFlag)
						$s .= ' Q ';
					if ($link)
						$this->Link($this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $w_txt, $this->FontSize, $link);
				}
			} else { // Single line
				$w_txt = $this->GetStringWidth($txt);
				$Tz = 100;
				if ($w_txt > $w - 2 * $this->cMargin) { // Need compression
					$Tz = ($w - 2 * $this->cMargin) / $w_txt * 100;
					$w_txt = $w - 2 * $this->cMargin;
				}
				if ($align == 'R')
					$dx = $w - $w_txt - $this->cMargin;
				elseif ($align == 'C')
					$dx = ($w - $w_txt) / 2;
				else
					$dx = $this->cMargin;
				$txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
				if ($this->ColorFlag)
					$s .= 'q ' . $this->TextColor . ' ';
				$s .= sprintf(
					'q BT %.2F %.2F Td %.2F Tz (%s) Tj ET Q ',
					($this->x + $dx) * $k,
					($this->h - ($this->y + .5 * $h + .3 * $this->FontSize)) * $k,
					$Tz,
					$txt
				);
				if ($this->underline)
					$s .= ' ' . $this->_dounderline($this->x + $dx, $this->y + .5 * $h + .3 * $this->FontSize, $txt);
				if ($this->ColorFlag)
					$s .= ' Q ';
				if ($link)
					$this->Link($this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $w_txt, $this->FontSize, $link);
			}
		}
		// end change Cell function
		if ($s)
			$this->_out($s);
		$this->lasth = $h;
		if ($ln > 0) {
			//Go to next line
			$this->y += $h;
			if ($ln == 1)
				$this->x = $this->lMargin;
		} else
			$this->x += $w;
	}
}

// ENCABEZADO
$pdf = new CellPDF('L', 'mm', 'LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(12, 15, 12);
$pdf->SetAutoPageBreak(1, 20);
$pdf->SetTitle('Relacion de Asistencia Diaria');

$desde = voltea_fecha($_GET['desde']);
$hasta = voltea_fecha($_GET['hasta']);
$tipo = decriptar($_GET['tipo']);
$cedula = ($_GET['cedula']);
$direccion = decriptar($_GET['direccion']);

if ($direccion == 0) {
} else {
	if ($cedula == 0) {
		$filtro = ' AND rac.id_div=' . $direccion;
	} else {
		$filtro = ' AND rac.cedula=' . $cedula;
	}
}

//-------------	
if ($tipo == 1) {
	$tipo = "PERMISO";
}
if ($tipo == 2) {
	$tipo = "REPOSO";
}
if ($tipo == 3) {
	$tipo = "VACACIONES";
}

//-------------	
$consult = "SELECT 	CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) AS nombre, rrhh_permisos_detalle.desde, rrhh_permisos_detalle.hasta, rrhh_permisos_detalle.incorporacion, rac.cedula, rac.cargo, a_direcciones.direccion, rrhh_permisos.tipo, rrhh_permisos_detalle.habiles FROM rrhh_permisos, a_direcciones, rac, rrhh_permisos_detalle WHERE rrhh_permisos_detalle.desde >= '$desde' AND rrhh_permisos_detalle.desde <= '$hasta' AND rrhh_permisos.id = rrhh_permisos_detalle.id_permiso AND rac.id_div = a_direcciones.id AND rrhh_permisos.cedula = rac.cedula AND rrhh_permisos.tipo = '$tipo' $filtro ORDER BY rrhh_permisos_detalle.desde";

// ----------
$pdf->AddPage();

$aa = 9;
$a = 18;
$b = 50;
$c = 51;
$d = 51;
$e = 22;
$e2 = 20;

$pdf->SetFont('Times', '', 9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i = 0;
//$nomina = '';
//$direccion = '';
//-----------------

$tabla = $_SESSION['conexionsql']->query($consult);
//-----------------
$i = 0;
$monto = 0;
while ($registro = $tabla->fetch_object()) {
	//----------
	if ($i % 2 == 0) {
		$pdf->SetFillColor(255);
	} else {
		$pdf->SetFillColor(250);
	}
	//----------
	$pdf->SetFont('Times', '', 9);
	$pdf->Cell($aa, 5.5, $i + 1, 1, 0, 'C', 1);
	$pdf->Cell($d, 5.5, utf8_decode($registro->direccion), 1, 0, 'L', 1);
	$pdf->Cell($a, 5.5, $registro->cedula, 1, 0, 'C', 1);
	$pdf->SetFont('Times', '', 8);
	$pdf->Cell($b, 5.5, utf8_decode($registro->nombre), 1, 0, 'L', 1);
	$pdf->Cell($c, 5.5, utf8_decode($registro->cargo), 1, 0, 'L', 1);
	$pdf->SetFont('Times', '', 9);
	$pdf->Cell($e, 5.5, voltea_fecha($registro->desde), 1, 0, 'C', 1);
	$pdf->Cell($e, 5.5, voltea_fecha($registro->hasta), 1, 0, 'C', 1);
	$pdf->Cell($e2, 5.5, voltea_fecha($registro->incorporacion), 1, 0, 'C', 1);
	$pdf->Cell(0, 5.5, ($registro->habiles), 1, 0, 'C', 1);

	$pdf->Ln(5.5);
	$monto = $monto + $registro->sueldo;
	//-----------
	$i++;
}

$pdf->SetFont('Times', 'B', 12);
$pdf->SetFillColor(230);
//$pdf->Cell($aa+$a+$b+$c+$d+$d1,6,'TOTAL =>',1,0,'R',1);
//$pdf->Cell(0,6,'TOTAL => '.formato_moneda($monto),1,0,'R',1);
//-----------

$pdf->Output();
