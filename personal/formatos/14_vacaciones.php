<?php
session_start();
ob_end_clean();
session_start();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once('../../lib/fpdf/fpdf.php');

//if ($_SESSION['VERIFICADO'] != "SI") { 
//    header ("Location: ../index.php?errorusuario=val"); 
//    exit(); 
//	}

if ($_GET['id'] <> '0') {
	$_SESSION['id_ct'] = decriptar($_GET['id']);
} else {
	$_SESSION['id_ct'] = $_POST['id'];
}

class PDF_WriteTag extends FPDF
{
	function Footer()
	{
		$this->SetTextColor(50);
		$this->SetFont('Times', 'I', 11);
		$this->SetY(-12);
		$this->Cell(0, 0, 'SIACEBG', 0, 0, 'L');
	}
	//----------------- PARA NEGRITAS EN TEXTO

	protected $wLine; // Maximum width of the line
	protected $hLine; // Height of the line
	protected $Text; // Text to display
	protected $border;
	protected $align; // Justification of the text
	protected $fill;
	protected $Padding;
	protected $lPadding;
	protected $tPadding;
	protected $bPadding;
	protected $rPadding;
	protected $TagStyle; // Style for each tag
	protected $Indent;
	protected $Space; // Minimum space between words
	protected $PileStyle;
	protected $Line2Print; // Line to display
	protected $NextLineBegin; // Buffer between lines 
	protected $TagName;
	protected $Delta; // Maximum width minus width
	protected $StringLength;
	protected $LineLength;
	protected $wTextLine; // Width minus paddings
	protected $nbSpace; // Number of spaces in the line
	protected $Xini; // Initial position
	protected $href; // Current URL
	protected $TagHref; // URL for a cell

	// Public Functions	

	function WriteTag($w, $h, $txt, $border = 0, $align = "J", $fill = false, $padding = 0)
	{
		$this->wLine = $w;
		$this->hLine = $h;
		$this->Text = trim($txt);
		$this->Text = preg_replace("/\n|\r|\t/", "", $this->Text);
		$this->border = $border;
		$this->align = $align;
		$this->fill = $fill;
		$this->Padding = $padding;

		$this->Xini = $this->GetX();
		$this->href = "";
		$this->PileStyle = array();
		$this->TagHref = array();
		$this->LastLine = false;
		$this->NextLineBegin = array();

		$this->SetSpace();
		$this->Padding();
		$this->LineLength();
		$this->BorderTop();

		while ($this->Text != "") {
			$this->MakeLine();
			$this->PrintLine();
		}

		$this->BorderBottom();
	}


	function SetStyle($tag, $family, $style, $size, $color, $indent = -1)
	{
		$tag = trim($tag);
		$this->TagStyle[$tag]['family'] = trim($family);
		$this->TagStyle[$tag]['style'] = trim($style);
		$this->TagStyle[$tag]['size'] = trim($size);
		$this->TagStyle[$tag]['color'] = trim($color);
		$this->TagStyle[$tag]['indent'] = $indent;
	}


	// Private Functions

	function SetSpace() // Minimal space between words
	{
		$tag = $this->Parser($this->Text);
		$this->FindStyle($tag[2], 0);
		$this->DoStyle(0);
		$this->Space = $this->GetStringWidth(" ");
	}


	function Padding()
	{
		if (preg_match("/^.+,/", $this->Padding)) {
			$tab = explode(",", $this->Padding);
			$this->lPadding = $tab[0];
			$this->tPadding = $tab[1];
			if (isset($tab[2]))
				$this->bPadding = $tab[2];
			else
				$this->bPadding = $this->tPadding;
			if (isset($tab[3]))
				$this->rPadding = $tab[3];
			else
				$this->rPadding = $this->lPadding;
		} else {
			$this->lPadding = $this->Padding;
			$this->tPadding = $this->Padding;
			$this->bPadding = $this->Padding;
			$this->rPadding = $this->Padding;
		}
		if ($this->tPadding < $this->LineWidth)
			$this->tPadding = $this->LineWidth;
	}


	function LineLength()
	{
		if ($this->wLine == 0)
			$this->wLine = $this->w - $this->Xini - $this->rMargin;

		$this->wTextLine = $this->wLine - $this->lPadding - $this->rPadding;
	}


	function BorderTop()
	{
		$border = 0;
		if ($this->border == 1)
			$border = "TLR";
		$this->Cell($this->wLine, $this->tPadding, "", $border, 0, 'C', $this->fill);
		$y = $this->GetY() + $this->tPadding;
		$this->SetXY($this->Xini, $y);
	}


	function BorderBottom()
	{
		$border = 0;
		if ($this->border == 1)
			$border = "BLR";
		$this->Cell($this->wLine, $this->bPadding, "", $border, 0, 'C', $this->fill);
	}


	function DoStyle($tag) // Applies a style
	{
		$tag = trim($tag);
		$this->SetFont(
			$this->TagStyle[$tag]['family'],
			$this->TagStyle[$tag]['style'],
			$this->TagStyle[$tag]['size']
		);

		$tab = explode(",", $this->TagStyle[$tag]['color']);
		if (count($tab) == 1)
			$this->SetTextColor($tab[0]);
		else
			$this->SetTextColor($tab[0], $tab[1], $tab[2]);
	}


	function FindStyle($tag, $ind) // Inheritance from parent elements
	{
		$tag = trim($tag);

		// Family
		if ($this->TagStyle[$tag]['family'] != "")
			$family = $this->TagStyle[$tag]['family'];
		else {
			foreach ($this->PileStyle as $val) {
				$val = trim($val);
				if ($this->TagStyle[$val]['family'] != "") {
					$family = $this->TagStyle[$val]['family'];
					break;
				}
			}
		}

		// Style
		$style = "";
		$style1 = strtoupper($this->TagStyle[$tag]['style']);
		if ($style1 != "N") {
			$bold = false;
			$italic = false;
			$underline = false;
			foreach ($this->PileStyle as $val) {
				$val = trim($val);
				$style1 = strtoupper($this->TagStyle[$val]['style']);
				if ($style1 == "N")
					break;
				else {
					if (strpos($style1, "B") !== false)
						$bold = true;
					if (strpos($style1, "I") !== false)
						$italic = true;
					if (strpos($style1, "U") !== false)
						$underline = true;
				}
			}
			if ($bold)
				$style .= "B";
			if ($italic)
				$style .= "I";
			if ($underline)
				$style .= "U";
		}

		// Size
		if ($this->TagStyle[$tag]['size'] != 0)
			$size = $this->TagStyle[$tag]['size'];
		else {
			foreach ($this->PileStyle as $val) {
				$val = trim($val);
				if ($this->TagStyle[$val]['size'] != 0) {
					$size = $this->TagStyle[$val]['size'];
					break;
				}
			}
		}

		// Color
		if ($this->TagStyle[$tag]['color'] != "")
			$color = $this->TagStyle[$tag]['color'];
		else {
			foreach ($this->PileStyle as $val) {
				$val = trim($val);
				if ($this->TagStyle[$val]['color'] != "") {
					$color = $this->TagStyle[$val]['color'];
					break;
				}
			}
		}

		// Result
		$this->TagStyle[$ind]['family'] = $family;
		$this->TagStyle[$ind]['style'] = $style;
		$this->TagStyle[$ind]['size'] = $size;
		$this->TagStyle[$ind]['color'] = $color;
		$this->TagStyle[$ind]['indent'] = $this->TagStyle[$tag]['indent'];
	}


	function Parser($text)
	{
		$tab = array();
		// Closing tag
		if (preg_match("|^(</([^>]+)>)|", $text, $regs)) {
			$tab[1] = "c";
			$tab[2] = trim($regs[2]);
		}
		// Opening tag
		else if (preg_match("|^(<([^>]+)>)|", $text, $regs)) {
			$regs[2] = preg_replace("/^a/", "a ", $regs[2]);
			$tab[1] = "o";
			$tab[2] = trim($regs[2]);

			// Presence of attributes
			if (preg_match("/(.+) (.+)='(.+)'/", $regs[2])) {
				$tab1 = preg_split("/ +/", $regs[2]);
				$tab[2] = trim($tab1[0]);
				foreach ($tab1 as $i => $couple) {
					if ($i > 0) {
						$tab2 = explode("=", $couple);
						$tab2[0] = trim($tab2[0]);
						$tab2[1] = trim($tab2[1]);
						$end = strlen($tab2[1]) - 2;
						$tab[$tab2[0]] = substr($tab2[1], 1, $end);
					}
				}
			}
		}
		// Space
		else if (preg_match("/^( )/", $text, $regs)) {
			$tab[1] = "s";
			$tab[2] = ' ';
		}
		// Text
		else if (preg_match("/^([^< ]+)/", $text, $regs)) {
			$tab[1] = "t";
			$tab[2] = trim($regs[1]);
		}

		$begin = strlen($regs[1]);
		$end = strlen($text);
		$text = substr($text, $begin, $end);
		$tab[0] = $text;

		return $tab;
	}


	function MakeLine()
	{
		$this->Text .= " ";
		$this->LineLength = array();
		$this->TagHref = array();
		$Length = 0;
		$this->nbSpace = 0;

		$i = $this->BeginLine();
		$this->TagName = array();

		if ($i == 0) {
			$Length = $this->StringLength[0];
			$this->TagName[0] = 1;
			$this->TagHref[0] = $this->href;
		}

		while ($Length < $this->wTextLine) {
			$tab = $this->Parser($this->Text);
			$this->Text = $tab[0];
			if ($this->Text == "") {
				$this->LastLine = true;
				break;
			}

			if ($tab[1] == "o") {
				array_unshift($this->PileStyle, $tab[2]);
				$this->FindStyle($this->PileStyle[0], $i + 1);

				$this->DoStyle($i + 1);
				$this->TagName[$i + 1] = 1;
				if ($this->TagStyle[$tab[2]]['indent'] != -1) {
					$Length += $this->TagStyle[$tab[2]]['indent'];
					$this->Indent = $this->TagStyle[$tab[2]]['indent'];
				}
				if ($tab[2] == "a")
					$this->href = $tab['href'];
			}

			if ($tab[1] == "c") {
				array_shift($this->PileStyle);
				if (isset($this->PileStyle[0])) {
					$this->FindStyle($this->PileStyle[0], $i + 1);
					$this->DoStyle($i + 1);
				}
				$this->TagName[$i + 1] = 1;
				if ($this->TagStyle[$tab[2]]['indent'] != -1) {
					$this->LastLine = true;
					$this->Text = trim($this->Text);
					break;
				}
				if ($tab[2] == "a")
					$this->href = "";
			}

			if ($tab[1] == "s") {
				$i++;
				$Length += $this->Space;
				$this->Line2Print[$i] = "";
				if ($this->href != "")
					$this->TagHref[$i] = $this->href;
			}

			if ($tab[1] == "t") {
				$i++;
				$this->StringLength[$i] = $this->GetStringWidth($tab[2]);
				$Length += $this->StringLength[$i];
				$this->LineLength[$i] = $Length;
				$this->Line2Print[$i] = $tab[2];
				if ($this->href != "")
					$this->TagHref[$i] = $this->href;
			}
		}

		trim($this->Text);
		if ($Length > $this->wTextLine || $this->LastLine == true)
			$this->EndLine();
	}


	function BeginLine()
	{
		$this->Line2Print = array();
		$this->StringLength = array();

		if (isset($this->PileStyle[0])) {
			$this->FindStyle($this->PileStyle[0], 0);
			$this->DoStyle(0);
		}

		if (count($this->NextLineBegin) > 0) {
			$this->Line2Print[0] = $this->NextLineBegin['text'];
			$this->StringLength[0] = $this->NextLineBegin['length'];
			$this->NextLineBegin = array();
			$i = 0;
		} else {
			preg_match("/^(( *(<([^>]+)>)* *)*)(.*)/", $this->Text, $regs);
			$regs[1] = str_replace(" ", "", $regs[1]);
			$this->Text = $regs[1] . $regs[5];
			$i = -1;
		}

		return $i;
	}


	function EndLine()
	{
		if (end($this->Line2Print) != "" && $this->LastLine == false) {
			$this->NextLineBegin['text'] = array_pop($this->Line2Print);
			$this->NextLineBegin['length'] = end($this->StringLength);
			array_pop($this->LineLength);
		}

		while (end($this->Line2Print) === "")
			array_pop($this->Line2Print);

		$this->Delta = $this->wTextLine - end($this->LineLength);

		$this->nbSpace = 0;
		for ($i = 0; $i < count($this->Line2Print); $i++) {
			if ($this->Line2Print[$i] == "")
				$this->nbSpace++;
		}
	}


	function PrintLine()
	{
		$border = 0;
		if ($this->border == 1)
			$border = "LR";
		$this->Cell($this->wLine, $this->hLine, "", $border, 0, 'C', $this->fill);
		$y = $this->GetY();
		$this->SetXY($this->Xini + $this->lPadding, $y);

		if ($this->Indent != -1) {
			if ($this->Indent != 0)
				$this->Cell($this->Indent, $this->hLine);
			$this->Indent = -1;
		}

		$space = $this->LineAlign();
		$this->DoStyle(0);
		for ($i = 0; $i < count($this->Line2Print); $i++) {
			if (isset($this->TagName[$i]))
				$this->DoStyle($i);
			if (isset($this->TagHref[$i]))
				$href = $this->TagHref[$i];
			else
				$href = '';
			if ($this->Line2Print[$i] == "")
				$this->Cell($space, $this->hLine, "         ", 0, 0, 'C', false, $href);
			else
				$this->Cell($this->StringLength[$i], $this->hLine, $this->Line2Print[$i], 0, 0, 'C', false, $href);
		}

		$this->LineBreak();
		if ($this->LastLine && $this->Text != "")
			$this->EndParagraph();
		$this->LastLine = false;
	}


	function LineAlign()
	{
		$space = $this->Space;
		if ($this->align == "J") {
			if ($this->nbSpace != 0)
				$space = $this->Space + ($this->Delta / $this->nbSpace);
			if ($this->LastLine)
				$space = $this->Space;
		}

		if ($this->align == "R")
			$this->Cell($this->Delta, $this->hLine);

		if ($this->align == "C")
			$this->Cell($this->Delta / 2, $this->hLine);

		return $space;
	}


	function LineBreak()
	{
		$x = $this->Xini;
		$y = $this->GetY() + $this->hLine;
		$this->SetXY($x, $y);
	}


	function EndParagraph()
	{
		$border = 0;
		if ($this->border == 1)
			$border = "LR";
		$this->Cell($this->wLine, $this->hLine / 2, "", $border, 0, 'C', $this->fill);
		$x = $this->Xini;
		$y = $this->GetY() + $this->hLine / 2;
		$this->SetXY($x, $y);
	}
}

// ENCABEZADO
$pdf = new PDF_WriteTag('P', 'mm', 'LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(1, 10);
$pdf->SetTitle('Solicitud');
$linea = 9;

// Stylesheet
$pdf->SetStyle("strong", "Times", "B", 12, "0,0,0");
$pdf->SetStyle("n", "Times", "", 12, "0,0,0");

////////// DATOS
$consulta = "SELECT rrhh_permisos.*, rrhh_permisos.jefe_area as jefe_area1, rac.*, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as empleado FROM rrhh_permisos, rac WHERE rrhh_permisos.cedula = rac.cedula AND id = " . $_SESSION['id_ct'] . ";";
$tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
$registro = $tabla->fetch_object();

// --------------
$fecha = voltea_fecha($registro->fecha);
$tipo = $registro->tipo;
$cedula = ($registro->ci);
$empleado = $registro->empleado;
$profesion = trim($registro->profesion);
$cargo = trim($registro->cargo);
$ubicacion = $registro->ubicacion;
$fecha_ingreso = $registro->fecha_ingreso;
$anno_ing = anno($registro->fecha_ingreso);
$mes_ing = mes($registro->fecha_ingreso);
$dia_ing = dia($registro->fecha_ingreso);
$annos = annos_exacto($anno_ing, $mes_ing, $dia_ing, anno($registro->fecha), mes($registro->fecha), dia($registro->fecha));
$annos_servicio = $registro->anos_servicio;

$sueldo = $registro->sueldo;
$descripcion = $registro->descripcion;
$periodo = $registro->periodo;
$desde = voltea_fecha($registro->desde);
$hasta = voltea_fecha($registro->hasta);
$incorporacion = voltea_fecha($registro->incorporacion);
$habiles = $registro->habiles;
$calendario = $registro->calendario;
$jefe = $registro->jefe;
$jefe_cargo = $registro->jefe_cargo;
$jefe_area = $registro->jefe_area1; //$registro->jefe_area;
$cargo_area = $registro->cargo_area;

//$code = generarRuta('16','amfm',substr($cedula, 6, 4), $_SESSION['id_ct']);

// ----------
$pdf->AddPage();
$pdf->SetFillColor(190);
$pdf->Image('../../images/logo_nuevo.jpg', 25, 12, 30);

//if ($_SERVER['HTTP_HOST']=='localhost')
//	{$pdf->Image("http://localhost/samatfram/scripts/qr_generador.php?code=".$code,180,245,25,25,"png");}
//else	{$pdf->Image("http://app.cebg.com.ve/scripts/qr_generador.php?code=".$code,180,245,25,25,"png");}

$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(0, 5, 'FORMA ADV-016', 0, 0, 'R');

$pdf->SetFont('Times', '', 9);
$pdf->SetX(12);
$pdf->Cell(0, 5, 'CONTRALORÍA DEL ESTADO BOLIVARIANO DE GUÁRICO', 0, 0, 'C');
$pdf->Ln(5);
//$pdf->SetX(51);
$pdf->Cell(0, 5, 'DIRECCIÓN DE TALENTO HUMANO', 0, 0, 'C');
$pdf->Ln(5);
//$pdf->SetX(51);
$pdf->Cell(0, 5, 'AUTORIZACIÓN DE VACACIONES', 0, 0, 'C');
$pdf->Ln();

$pdf->SetX(150);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell(0, $linea, "", 1);
$pdf->SetX(150);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell(0, $linea / 2, "1. FECHA:", 0, 0, 'L', 0);

$pdf->SetXY($x, $y + 2);
$pdf->SetFont('Times', '', 9);
$pdf->Cell($a, $linea - 1, $fecha, 0, 0, 'C');
$pdf->Ln($linea - 2);

$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell($a = 135, $linea, "", 1);
$pdf->SetX($x);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell($a, $linea / 2, "2. APELLIDOS Y NOMBRES DEL SERVIDOR (A) PÚBLICO (A) O TRABAJADOR (A)", 0, 0, 'L', 0);

$pdf->SetXY($x, $y + 2);
$pdf->SetFont('Times', '', 9);
$pdf->Cell($a, $linea - 1, $empleado, 0, 0, 'C');

$pdf->SetXY($x + $a, $y);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell($a = 0, $linea, "", 1);
$pdf->SetX($x);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell($a, $linea / 2, "3. CÉDULA DE IDENTIDAD", 0, 0, 'L', 0);

$pdf->SetXY($x, $y + 2);
$pdf->SetFont('Times', '', 9);
$pdf->Cell($a, $linea - 1, $cedula, 0, 0, 'C');
$pdf->Ln($linea - 2);

$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell($a = 0, $linea, "", 1);
$pdf->SetX($x);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell($a, $linea / 2, "4. DENOMINACIÓN DEL CARGO", 0, 0, 'L', 0);

$pdf->SetXY($x, $y + 2);
$pdf->SetFont('Times', '', 9);
$pdf->Cell($a, $linea - 1, $cargo, 0, 0, 'C');

//$pdf->SetXY($x+$a,$y);
//$x = $pdf->GetX();
//$y = $pdf->GetY();
//$pdf->Cell($a=0,$linea,"",1);
//$pdf->SetX($x);
//$pdf->SetFont('Times','B',8);
//$pdf->Cell($a,$linea/2,"5. CÓDIGO DE NÓMINA",0,0,'L',0);

//$pdf->SetXY($x,$y+2);
//$pdf->SetFont('Times','',9);
//$pdf->Cell($a,$linea-1,$cedula,0,0,'C');
$pdf->Ln($linea - 2);

$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell($a = 0, $linea, "", 1);
$pdf->SetX($x);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell($a, $linea / 2, "5. UBICACIÓN ADMINISTRATIVA", 0, 0, 'L', 0);

$pdf->SetXY($x, $y + 2);
$pdf->SetFont('Times', '', 9);
$pdf->Cell($a, $linea - 1, $ubicacion, 0, 0, 'C');

$pdf->SetFont('Times', 'B', 8);
$pdf->Ln($linea - 2);
$y = $pdf->GetY();
$x = $pdf->GetX();
$pdf->MultiCell(30, $linea, "6. PERÍODO (S)", 1, 'C');

//$pdf->SetXY($x+(25),$y);
//$pdf->MultiCell(25,$linea/2,"8. FECHA VENCIMIENTO",1,'C');

$pdf->SetXY($x + (30) * 1, $y);
$pdf->MultiCell(30, $linea / 1, "7. FECHA SALIDA", 1, 'C');

$pdf->SetXY($x + (30) * 2, $y);
$pdf->MultiCell(30, $linea / 2, "8. FECHA CULMINACIÓN", 1, 'C');

$pdf->SetXY($x + (30) * 3, $y);
$pdf->MultiCell(30, $linea / 1, "9. FECHA REGRESO", 1, 'C');

$pdf->SetXY($x + (30) * 4, $y);
$pdf->MultiCell(0, $linea / 2, "10. FIRMA DEL SERVIDOR (A) PÚBLICO (A) O TRABAJADOR (A) Y FECHA", 1, 'C');

$pdf->SetFont('Times', '', 10);
$pdf->Cell(30, $linea * 2, $periodo, 1, 0, 'C'); //$periodo
//$pdf->Cell(25,$linea*2,dia($registro->fecha_ingreso).' '.$_SESSION['meses_anno'][abs(mes($registro->fecha_ingreso))],1,0,'C');
$pdf->Cell(30, $linea * 2, $desde, 1, 0, 'C');
$pdf->Cell(30, $linea * 2, $hasta, 1, 0, 'C');
$pdf->Cell(30, $linea * 2, $incorporacion, 1, 0, 'C');
$pdf->Cell(0, $linea * 2, '', 1, 0, 'C');
$pdf->Ln();

$pdf->SetFont('Times', 'B', 8);
$pdf->Cell(0, $linea, "PARA USO DEL SUPERVISOR INMEDIATO Y/O JERÁRQUICO", 1, 0, 'C', 1);
$pdf->Ln();
$linea++;

$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell($a = 189 / 2, $linea, "", 1);
$pdf->SetX($x);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell($a, $linea / 2, "11. NOMBRES Y APELLIDOS DEL JEFE INMEDIATO", 0, 0, 'L', 0);

$pdf->SetXY($x, $y + 2);
$pdf->SetFont('Times', '', 9);
$pdf->Cell($a, $linea - 1, $jefe_area, 0, 0, 'C');

$pdf->SetXY($x + $a, $y);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell($a = 0, $linea, "", 1);
$pdf->SetX($x);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell($a, $linea / 2, "12. NOMBRES Y APELLIDOS DEL SUPERIOR JERARQUICO", 0, 0, 'L', 0);

$pdf->SetXY($x, $y + 2);
$pdf->SetFont('Times', '', 9);
$pdf->Cell($a, $linea - 1, $jefe, 0, 0, 'C');
$pdf->Ln($linea - 2);

$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell($a = 189 / 2, $linea, "", 1);
$pdf->SetX($x);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell($a, $linea / 2, "13. CARGO DEL SUPERIOR JERÁRQUICO", 0, 0, 'L', 0);

$pdf->SetXY($x, $y + 2);
$pdf->SetFont('Times', '', 9);
$pdf->Cell($a, $linea - 1, $cargo_area, 0, 0, 'C');

$pdf->SetXY($x + $a, $y);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell($a = 0, $linea, '', 1);
$pdf->SetX($x);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell($a, $linea / 2, "14. CARGO DEL SUPERIOR JERÁRQUICO", 0, 0, 'L', 0);

$pdf->SetXY($x, $y + 2);
$pdf->SetFont('Times', '', 9);
$pdf->Cell($a, $linea - 1, $jefe_cargo, 0, 0, 'C');
$pdf->Ln($linea - 2);

$pdf->Cell(189 / 2, $linea * 2.2, "", 1);
$pdf->Cell(0, $linea * 2.2, "", 1);
$pdf->Ln($linea);
$x = $pdf->GetX();

$pdf->SetXY($x, $y + $linea * 2.2 + 2);
$pdf->Cell(189 / 2, $linea, "Firma", 0, 0, 'C');
$pdf->Cell(0, $linea, "Firma", 0, 0, 'C');
$pdf->Ln($linea - 2);

$linea--;
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell(0, $linea, "DIRECCIÓN DE TALENTO HUMANO", 1, 0, 'C', 1);
$pdf->Ln();

$pdf->SetFont('Times', 'B', 7);
$y = $pdf->GetY();
$x = $pdf->GetX();
$pdf->MultiCell(37.8, $linea / 2, "15. FECHA DE INGRESO EN LA C.E.B.G.", 1, 'C');

$pdf->SetXY($x + (37.8), $y);
$pdf->MultiCell(37.8, $linea / 2, "16. TIEMPO DE SERVICIO EN OTROS ENTES PUBLICOS", 1, 'C');

$pdf->SetXY($x + (37.8) * 2, $y);
$pdf->MultiCell(37.8, $linea / 2, "17. TIEMPO DE SERVICIO EN LA C.E.B.G.", 1, 'C');

$pdf->SetXY($x + (37.8) * 3, $y);
$pdf->MultiCell(37.8, $linea, "18. QUINQUENIO", 1, 'C');

$pdf->SetXY($x + (37.8) * 4, $y);
$pdf->MultiCell(0, $linea / 2, "19. DÍAS HÁBILES DISFRUTE", 1, 'C');

$pdf->SetFont('Times', '', 10);
$pdf->Cell(37.8, $linea * 2, voltea_fecha($fecha_ingreso), 1, 0, 'C');
$pdf->Cell(37.8, $linea * 2, $annos_servicio, 1, 0, 'C');
$pdf->Cell(37.8, $linea * 2, $annos, 1, 0, 'C');
$pdf->Cell(37.8, $linea * 2, quinquenio($annos_servicio + $annos) . ' dias', 1, 0, 'C');
$pdf->Cell(0, $linea * 2, $habiles, 1, 0, 'C');
$pdf->Ln();

$pdf->SetFont('Times', 'B', 7);
$y = $pdf->GetY();
$x = $pdf->GetX();
$pdf->MultiCell(189 / 4, $linea / 2, "20. FECHA SALIDA", 1, 'L');

$pdf->SetXY($x + (189 / 4), $y);
$pdf->MultiCell(189 / 4, $linea / 2, "21. FECHA REGRESO", 1, 'L');

$pdf->SetXY($x + (189 / 4) * 2, $y);
$pdf->MultiCell(0, $linea / 2, "22. OBSERVACIONES", 1, 'L');

$pdf->SetFont('Times', '', 10);
$pdf->Cell(189 / 4, $linea * 2, $desde, 1, 0, 'C');
$pdf->Cell(189 / 4, $linea * 2, $incorporacion, 1, 0, 'C');
$pdf->Cell(0, $linea * 2, '', 1, 0, 'L');

$pdf->SetX($x + (189 / 4) * 2);
$pdf->MultiCell(0, $linea / 2, $descripcion, 0, 'J');

$pdf->Ln();

$pdf->SetFont('Times', 'B', 7);
$y = $pdf->GetY();
$x = $pdf->GetX();
$pdf->MultiCell(189 / 2, $linea / 2, "23. ELABORADO POR", 1, 'L');

$pdf->SetXY($x + (189 / 2), $y);
$pdf->MultiCell(0, $linea / 2, "24. FIRMA", 1, 'L');

$pdf->SetFont('Times', '', 8);
$pdf->Cell(189 / 2, $linea * 2, "", 1);
$pdf->Cell(0, $linea * 2, "", 1);
$pdf->Ln();

$pdf->SetFont('Times', 'B', 7);
$y = $pdf->GetY();
$x = $pdf->GetX();
$pdf->MultiCell(130, $linea / 2, "25. DIRECTOR DE TALENTO HUMANO ( E ):", 1, 'L');

$pdf->SetXY($x + (130), $y);
$pdf->MultiCell(0, $linea / 2, "26. FIRMA Y SELLO", 1, 'C');

$pdf->SetFont('Times', '', 8);
$pdf->Cell(130, $linea * 2, "", 1);
$pdf->Cell(0, $linea * 2, "", 1);
$pdf->Ln();

// FIN

$pdf->Output();
