<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES', 'sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_SESSION['VERIFICADO'] != "SI") {
  header("Location: ../index.php?errorusuario=val");
  exit();
}

class CellPDF extends FPDF
{
  // Paleta y utilidades (no cambia layout)
  private $accent = [47, 111, 171]; // azul sobrio
  private $grid = [200, 205, 210];  // gris líneas
  private $headerFill = [242, 244, 247]; // gris claro para encabezados

  private function t($s)
  {
    if ($s === null) return '';
    return utf8_decode($s);
  }

  private function titleCaseEs($s)
  {
    if ($s === null) return '';
    $u = utf8_encode($s);
    $u = mb_convert_case(mb_strtolower($u, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
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
    $aprobado = ($_GET['p']);
    if ($aprobado == 0) {
      $consultx = "SELECT	orden.id, orden.id_contribuyente, orden.tipo_orden2, orden.rif, orden.fecha, orden.anno, orden.concepto, orden.numero, contribuyente.nombre, presupuesto.oficina FROM contribuyente, orden, presupuesto  WHERE orden.id_presupuesto = presupuesto.id_solicitud AND orden.estatus=0 AND orden.id_contribuyente = $id AND orden.id_contribuyente = contribuyente.id LIMIT 1;";
    } else {
      $consultx = "SELECT	orden.id, orden.id_contribuyente, orden.tipo_orden2, orden.rif, orden.fecha, orden.anno, orden.concepto, orden.numero, contribuyente.nombre, presupuesto.oficina FROM contribuyente, orden, presupuesto  WHERE orden.id_presupuesto = presupuesto.id_solicitud AND orden.id_solicitud = $id AND orden.id_contribuyente = contribuyente.id LIMIT 1;";
    }

    //echo $consultx;
    $tablx = $_SESSION['conexionsql']->query($consultx);
    $registro = $tablx->fetch_object();
    //-------------
    $rif = formato_rif($registro->rif);
    $contribuyente = $registro->nombre;
    $fecha = $registro->fecha;
    $anno = $registro->anno;
    $numero = $registro->numero;
    $tipo_orden2 = $registro->tipo_orden2;
    $concepto = $registro->concepto;
    $asignaciones = $registro->asignaciones;
    $oficina = info_area($registro->oficina);
    //--------------

    $this->SetFillColor(240);
    $this->Image('../../images/logo_nuevo.jpg', 27, 7, 40);
    $this->Image('../../images/bandera_linea.png', 17, 41, 182, 1);
    $this->SetFont('Times', '', 11);
    // Líneas claras y delgadas para bordes de celdas
    $this->SetDrawColor($this->grid[0], $this->grid[1], $this->grid[2]);
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

    if ($tipo_orden2 == 'COMPRA') {
      $this->SetTextColor($this->accent[0], $this->accent[1], $this->accent[2]);
      $this->Cell(0, 5, $this->t('ORDEN DE COMPRA'), 0, 0, 'C');
      $this->SetTextColor(0);
    }

    if ($tipo_orden2 == 'SERVICIO') {
      $this->SetTextColor($this->accent[0], $this->accent[1], $this->accent[2]);
      $this->Cell(0, 5, $this->t('ORDEN DE SERVICIO'), 0, 0, 'C');
      $this->SetTextColor(0);
    }

    if ($tipo_orden2 == 'MIXTA') {
      $this->SetTextColor($this->accent[0], $this->accent[1], $this->accent[2]);
      $this->Cell(0, 5, $this->t('ORDEN DE COMPRA Y SERVICIO'), 0, 0, 'C');
      $this->SetTextColor(0);
    }

    $this->Ln(12);

    $y = $this->GetY();
    $this->SetY(20);
    //$this->SetX(150);
    $this->SetFont('Arial', 'B', 13);
    $this->SetTextColor($this->accent[0], $this->accent[1], $this->accent[2]);
    $this->Cell(0, 5, $this->t('N°: ') . rellena_cero($numero, 5), 0, 0, 'R');
    $this->Ln(7);
    //$this->Cell(0,5,'Preliminar',0,0,'R'); $this->Ln(7);
    $this->SetFont('Arial', 'B', 11);
    $this->SetTextColor(60, 60, 60);
    $this->Cell(0, 5, $this->t('Fecha: ') . voltea_fecha($fecha), 0, 0, 'R'); //$this->Ln(10);
    $this->SetTextColor(0);
    $this->SetFillColor(255);
    //-------------
    $this->SetY($y);
    $this->Cell(150, 5, '');
    $this->SetFont('Times', '', 10);
    $this->Cell(7, 5, 'Rif:', 0, 0, 'L', 1);
    $this->SetFont('Times', 'B', 11);
    $this->Cell(0, 5, $rif, 0, 0, 'C', 1);
    $this->SetFont('Times', '', 10);
    //-------------
    $this->SetY($y);
    $this->SetFont('Times', '', 9);
    //$this->Cell(3,5,''); 
    $this->Cell(22, 5, $this->t('PROVEEDOR:'), 0, 0, 'L');
    $this->SetFont('Times', 'B', 10);
    $this->MultiCell(118, 5, $contribuyente);
    $this->SetFillColor(240);
    $this->Ln(1);
    //-------------
    $this->SetFont('Times', '', 8.5);
    //$this->Cell(3,5,''); 
    $this->Cell(34, 5, $this->t('UNIDAD SOLICITANTE:'), 0, 0, 'L');
    $this->SetFont('Times', 'B', 10);
    $this->Cell(0, 5, ($oficina[4]), 0, 0, 'L');
    $this->Ln(6);

    $this->SetFont('Times', '', 10);
    $this->SetDrawColor($this->grid[0], $this->grid[1], $this->grid[2]);
    $this->SetLineWidth(0.3);
    $this->SetFillColor($this->headerFill[0], $this->headerFill[1], $this->headerFill[2]);
    $this->Cell($a = 0, 6, $this->t('USO, DESTINO Y CARACTERÍSTICAS DE LOS BIENES Y/O SERVICIOS SOLICITADOS:'), 1, 0, 'L', 1);
    $this->Ln(6);

    $y = $this->GetY();
    $this->SetFont('Times', 'B', 9);
    $this->MultiCell($a, 4, $concepto, 1, 'J');
    $this->Ln(5);
    //$this->SetFillColor(250);
    $this->SetFont('Arial', 'B', 9);
    $this->SetFillColor($this->headerFill[0], $this->headerFill[1], $this->headerFill[2]);
    $this->Cell($e = 0, 6, $this->t('DESCRIPCIÓN'), 1, 0, 'C', 1);
    $this->Ln();
    $this->SetFont('Times', 'B', 8.5);
    $this->Cell(8, 6, $this->t('Item'), 1, 0, 'C', 1);
    $this->Cell($a = 33, 6, $this->t('Imputación Presup.'), 1, 0, 'C', 1);
    $this->Cell($b = 14, 6, $this->t('Cantidad'), 1, 0, 'C', 1);
    $this->Cell($c = 58, 6, $this->t('Detalle'), 1, 0, 'C', 1);
    $this->Cell($d = 23, 6, $this->t('Unidad de Medida'), 1, 0, 'C', 1);
    $this->Cell($d, 6, $this->t('Precio Unitario'), 1, 0, 'C', 1);
    $this->Cell($e = 0, 6, $this->t('Total'), 1, 0, 'C', 1);
    $this->Ln();
  }

  function Footer()
  {
    //-------------------------------------------------
    $this->SetY(-68);
    // Líneas claras y delgadas en todo el pie
    $this->SetDrawColor($this->grid[0], $this->grid[1], $this->grid[2]);
    $this->SetLineWidth(0.3);
    $this->SetFillColor(245);
    $alto = 7;
    $this->SetFont('Times', '', 11);
    $this->SetFillColor($this->headerFill[0], $this->headerFill[1], $this->headerFill[2]);
    $this->Cell($a = 135, $alto, $this->t('MONTO TOTAL EN LETRAS'), 1, 0, 'C', 1);
    $this->Cell(0, $alto, $this->t('Monto Bs.'), 1, 1, 'C', 1);

    $this->SetFont('Times', 'B', 10);
    $y = $this->GetY();
    if ($_SESSION['lineas'] == 0) {
      $this->MultiCell($a, 5, strtoupper(valorEnLetras($_SESSION['monto'])), 1);
    } else {
      $this->MultiCell($a, 5, '', 1);
    }
    $y2 = $this->GetY();

    $this->SetY($y);
    $this->SetX($a + 17);
    if ($_SESSION['lineas'] == 0) {
      $this->Cell(0, $y2 - $y, formato_moneda($_SESSION['monto']), 1, 1, 'C', 0);
    } else {
      $this->Cell(0, $y2 - $y, $this->t('Van... ') . formato_moneda($_SESSION['monto']), 1, 1, 'C', 0);
    }
    //$this->Ln(8+$altura);

    $this->SetFont('Times', '', 9);
    //------------
    $firma1 = firma(6);
    $firma2 = firma(7);
    $firma3 = firma(8);
    //------------
    $a = 181.8;

    $this->SetFillColor($this->headerFill[0], $this->headerFill[1], $this->headerFill[2]);
    $this->Cell($a / 6, 5, $this->t('Elaborado por:'), 1, 0, 'C', 1);
    $this->Cell($a / 6, 5, $this->t('Revisado por:'), 1, 0, 'C', 1);
    $this->Cell($a / 6, 5, $this->t('Aprobado por:'), 1, 0, 'C', 1);
    $this->Cell($a / 6, 5, $this->t('Aceptado por'), 1, 0, 'C', 1);
    $this->Cell($a / 6, 5, $this->t('Fecha:'), 1, 0, 'C', 1);
    $this->Cell(0, 5, $this->t('Recibido'), 1, 1, 'C', 1);
    //------------
    $this->SetFont('Times', '', 7.5);
    $y = $this->GetY();
    $x = $this->GetX();
    $this->MultiCell($a / 6, 4, ($this->t($this->titleCaseEs($firma1[2]))), 0, 'C');
    $this->SetXY($x + $a / 6, $y);
    //$this->SetFont('Times','',7-$letra);
    $this->MultiCell($a / 6, 4, ($this->t($this->titleCaseEs($firma2[2]))), 0, 'C');
    //$this->SetFont('Times','',8.5-$letra);
    $this->SetXY($x + $a / 6 + $a / 6, $y);
    $this->MultiCell($a / 6, 4, ($this->t($this->titleCaseEs($firma3[2]))), 0, 'C');
    $this->SetXY($x + $a / 6 + $a / 6 + $a / 6, $y);
    $this->Cell($a / 6, 8, 'Proveedor', 0, 0, 'C', 0);
    $this->Ln(10);
    $this->SetFont('Times', 'B', 8.5);
    $this->Cell($a / 6, 6, ($firma1[1]), 0, 0, 'C', 0);
    $this->Cell($a / 6, 6, ($firma2[1]), 0, 0, 'C', 0);
    $this->Cell($a / 6, 6, ($firma3[1]), 0, 0, 'C', 0);
    //$this->SetFont('Times','',8.5-$letra);
    $this->Cell($a / 6, 6, '', 0, 0, 'C', 0);
    $this->Cell($a / 6, 6, '/     /', 0, 0, 'C', 0);
    $this->Cell(0, 6, 'Unidad Solicitante', 0, 0, 'C', 0);
    $this->Ln(4);
    $this->SetFont('Times', '', 8.5);
    $this->Cell($a / 6, 6, formato_cedula($firma1[0]), 0, 0, 'C', 0);
    $this->Cell($a / 6, 6, formato_cedula($firma2[0]), 0, 0, 'C', 0);
    $this->Cell($a / 6, 6, formato_cedula($firma3[0]), 0, 0, 'C', 0);
    $this->Cell($a / 6, 6, 'Firma y Sello', 0, 0, 'C', 0);
    $this->Cell($a / 6, 6, '', 0, 0, 'C', 0);
    $this->Cell(0, 6, 'Firma', 0, 0, 'C', 0);
    $this->SetXY($x, $y);
    // Cajas con líneas claras
    $this->SetDrawColor($this->grid[0], $this->grid[1], $this->grid[2]);
    $this->SetLineWidth(0.3);
    $this->Cell($a / 6, 20, '', 1, 0, 'C', 0);
    $this->Cell($a / 6, 20, '', 1, 0, 'C', 0);
    $this->Cell($a / 6, 20, '', 1, 0, 'C', 0);
    $this->Cell($a / 6, 20, '', 1, 0, 'C', 0);
    $this->Cell($a / 6, 20, '', 1, 0, 'C', 0);
    $this->Cell(0, 20, '', 1, 0, 'C', 0);
    //--------------
    $this->SetFont('Times', 'I', 8);
    // Ubicar pie de página en el borde inferior
    $this->SetY(-12);
    $this->SetTextColor(120);
    //--------------
    $this->Cell(80, 0, $_SESSION['CEDULA_USUARIO'], 0, 0, 'L');
    $this->Cell(0, 0, 'SIACEBG ' . $this->PageNo() . ' de {paginas}', 0, 0, 'R');
  }

  function VCell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false)
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
          if ($align == 'U')
            $dy = $this->cMargin + $w_txt;
          elseif ($align == 'D')
            $dy = $h - $this->cMargin;
          else
            $dy = ($h + $w_txt) / 2;
          $txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
          if ($this->ColorFlag)
            $s .= 'q ' . $this->TextColor . ' ';
          $s .= sprintf(
            'BT 0 1 -1 0 %.2F %.2F Tm (%s) Tj ET ',
            ($this->x + .5 * $w + (.7 + $l - $lines / 2) * $this->FontSize) * $k,
            ($this->h - ($this->y + $dy)) * $k,
            $txt
          );
          if ($this->ColorFlag)
            $s .= ' Q ';
        }
      } else { // Single line
        $w_txt = $this->GetStringWidth($txt);
        $Tz = 100;
        if ($w_txt > $h - 2 * $this->cMargin) {
          $Tz = ($h - 2 * $this->cMargin) / $w_txt * 100;
          $w_txt = $h - 2 * $this->cMargin;
        }
        if ($align == 'U')
          $dy = $this->cMargin + $w_txt;
        elseif ($align == 'D')
          $dy = $h - $this->cMargin;
        else
          $dy = ($h + $w_txt) / 2;
        $txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
        if ($this->ColorFlag)
          $s .= 'q ' . $this->TextColor . ' ';
        $s .= sprintf(
          'q BT 0 1 -1 0 %.2F %.2F Tm %.2F Tz (%s) Tj ET Q ',
          ($this->x + .5 * $w + .3 * $this->FontSize) * $k,
          ($this->h - ($this->y + $dy)) * $k,
          $Tz,
          $txt
        );
        if ($this->ColorFlag)
          $s .= ' Q ';
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

$pdf = new CellPDF('P', 'mm', 'LETTER');
$pdf->AliasNbPages('{paginas}');
$pdf->SetMargins(17, 80, 17);
$pdf->SetAutoPageBreak(1, 73);
$pdf->SetTitle('Orden de Compra');

// ----------
$pdf->AddPage();
$pdf->SetFont('Times', '', 9);
$a = 33;
$b = 14;
$c = 58;
$d = 23;

//-----------------
$id = decriptar($_GET['id']);
$aprobado = ($_GET['p']);
if ($aprobado == 0) {
  $consulta = "SELECT orden.medida, orden.categoria, orden.partida, orden.descripcion, sum(orden.cantidad) as cantidad, (orden.precio_uni) as precio_uni, sum(orden.total) as total, exento FROM orden WHERE orden.id_contribuyente = $id  AND orden.estatus=0 GROUP BY orden.categoria, orden.partida, orden.descripcion ORDER BY descripcion;";
} else {
  $consulta = "SELECT orden.medida, orden.categoria, orden.partida, orden.descripcion, sum(orden.cantidad) as cantidad, (orden.precio_uni) as precio_uni, sum(orden.total) as total, exento FROM orden WHERE orden.id_solicitud = $id GROUP BY orden.categoria, orden.partida, orden.descripcion ORDER BY descripcion;";
}
//echo $consulta;

$tabla = $_SESSION['conexionsql']->query($consulta);
$_SESSION['lineas'] = $tabla->num_rows;
//-----------------
$i = 1;
$alto = 5;
$_SESSION['monto'] = 0;

while ($registro = $tabla->fetch_object()) {
  $pdf->SetFont('Arial', '', 8);
  if ($registro->exento == 1) {
    $exento = ' (e)';
  } else {
    $exento = '';
  }
  //----------
  if (substr($registro->partida, 0, 5) == '40318') {
    $cantidad = 1;
    $precio_uni = $registro->total;
  } else {
    $precio_uni = $registro->precio_uni;
    $cantidad = $registro->cantidad;
  }
  //----------
  // Forzar líneas claras en filas de detalle
  $pdf->SetDrawColor(200, 205, 210);
  $pdf->SetLineWidth(0.3);
  $pdf->SetFillColor(255);
  //$pdf->Cell($aa,$alto,$i+1,1,0,'C',0);
  $pdf->Cell(8, $alto, $i, 1, 0, 'C', 0);
  $pdf->Cell($a, $alto, ($registro->categoria . $registro->partida), 1, 0, 'C', 0);
  $pdf->Cell($b, $alto, $cantidad, 1, 0, 'C', 0);
  $pdf->SetFont('Arial', '', 7);
  $pdf->Cell($c, $alto, $registro->descripcion . $exento, 1, 0, 'L', 0);
  $pdf->SetFont('Arial', '', 8);
  $pdf->Cell($d, $alto, ($registro->medida), 1, 0, 'C', 0);
  $pdf->Cell($d, $alto, formato_moneda($precio_uni), 1, 0, 'R', 0);
  $pdf->Cell(0, $alto, formato_moneda($registro->total), 1, 1, 'R', 0);
  //-----------
  $_SESSION['monto'] = $_SESSION['monto'] + $registro->total;
  $_SESSION['lineas']--;
  $i++;
}

if ($pdf->GetY() < $y = 205) {
  $pdf->Cell(8, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
  $pdf->Cell($a, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
  $pdf->Cell($b, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
  $pdf->Cell($c, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
  $pdf->Cell($d, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
  $pdf->Cell($d, $y - $pdf->GetY(), '', 1, 0, 'C', 0);
  $pdf->Cell(0, $y - $pdf->GetY(), '', 1, 1, 'C', 0);
}

//$pdf->Cell(50,50,'This line is very long and gets compressed','LtRb',0,'C');

$pdf->Output();
