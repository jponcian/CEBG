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
	function Header()
	{
		$this->SetFillColor(2, 117, 216);
		//	$this->Image('../../images/personal.png',190,16,60);
		$this->Image('../../images/logo_nuevo.jpg', 35, 14, 25);

		//$instituto = instituto();
		$this->SetFont('Times', 'I', 11);
		$this->SetX(91);
		$this->Cell(98, 5, 'República Bolivariana de Venezuela', 0, 0, 'C');
		$this->Ln(5);
		$this->SetX(91);
		$this->Cell(98, 5, 'Contraloria del Estado Bolivariano de Guárico', 0, 0, 'C');
		$this->Ln(5);
		$this->SetX(91);
		$this->Cell(98, 5, 'Dirección de Talento Humano', 0, 0, 'C');
		$this->Ln(15);
		// ---------------------

		$this->SetFont('Times', 'B', 11);
		$this->Cell(0, 5, 'RELACIÓN DE CARGA FAMILIAR', 0, 0, 'C');
		$this->Ln(7);

		$this->SetTextColor(255);
		$this->SetFont('Times', 'B', 10.5);
		$this->Cell($aa = 9, 7, 'Item', 1, 0, 'C', 1);
		$this->Cell($a = 20, 7, 'Cedula', 1, 0, 'C', 1);
		$this->Cell($b = 87, 7, 'Empleado', 1, 0, 'C', 1);
		$this->Cell($c = 20, 7, 'Cedula', 1, 0, 'C', 1);
		$this->Cell($d = 87, 7, 'Hijo(a)', 1, 0, 'C', 1);
		$this->Cell($e = 0, 7, 'Fecha Nac', 1, 1, 'C', 1);
	}

	function Footer()
	{
		$this->SetFont('Times', 'I', 8);
		$this->SetY(-18);
		$this->SetTextColor(120);
		//$this->Cell(0,5,'Resoluciï¿½n '.($_GET['id']));
		//--------------
		$s = $this->PageNo();
		while ($s > 5) {
			$s = $s - 5;
		}
		$this->Cell(0, 0, 'SIACEBG' . ' ' . $this->PageNo() . ' de {nb}', 0, 0, 'R');
	}
}

// ENCABEZADO
$pdf = new PDF('L', 'mm', 'LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17, 15, 17);
$pdf->SetAutoPageBreak(1, 23);
$pdf->SetTitle('Hijos');

// ----------
$pdf->AddPage();

$aa = 9;
$a = 20;
$b = 87;
$c = 20;
$d = 87;
$e = 0;

$pdf->SetFont('Times', '', 9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i = 0;
$nomina = '';
$ubicacion = '';
//-----------------
$tabla = $_SESSION['conexionsql']->query($_SESSION['consultaH']);
//-----------------

$edad_min = isset($_GET['edad_min']) ? intval($_GET['edad_min']) : null;
$edad_max = isset($_GET['edad_max']) ? intval($_GET['edad_max']) : null;
function calcular_edad_pdf($fecha_nac)
{
	$fecha = new DateTime($fecha_nac);
	$hoy = new DateTime();
	$edad = $hoy->diff($fecha)->y;
	return $edad;
}
$i = 0;
$monto = 0;
while ($registro = $tabla->fetch_object()) {
	$edad = calcular_edad_pdf($registro->fecha_nac);
	if ((isset($_GET['edad_min']) && $_GET['edad_min'] !== '' && $edad < $edad_min) || (isset($_GET['edad_max']) && $_GET['edad_max'] !== '' && $edad > $edad_max)) {
		continue;
	}
	//----------
	if ($i % 2 == 0) {
		$pdf->SetFillColor(255);
	} else {
		$pdf->SetFillColor(250);
	}
	//----------
	$pdf->SetFont('Times', '', 9);
	$pdf->Cell($aa, 5.5, $i + 1, 1, 0, 'C', 1);
	$pdf->Cell($a, 5.5, formato_cedula($registro->cedula), 1, 0, 'C', 1);
	$pdf->SetFont('Times', '', 8);
	$pdf->Cell($b, 5.5, substr($registro->nombre, 0, 50), 1, 0, 'L', 1);
	$pdf->Cell($c, 5.5, formato_cedula(abs($registro->cih)), 1, 0, 'C', 1);
	$pdf->SetFont('Times', '', 9);
	$pdf->Cell($d, 5.5, ($registro->nombres), 1, 0, 'L', 1);
	$pdf->Cell($e, 5.5, voltea_fecha($registro->fecha_nac), 1, 0, 'C', 1);

	$pdf->Ln(5.5);
	$monto = $monto + $registro->sueldo;
	//-----------
	$i++;
}

$pdf->SetFont('Times', 'B', 12);
$pdf->SetFillColor(230);
//$pdf->Cell($aa+$a+$b+$c+$d+$d1,6,'TOTAL =>',1,0,'R',1);
//$pdf->Cell(0,6,'TOTAL => '.formato_moneda($monto),1,0,'R',1);

$pdf->Output();
