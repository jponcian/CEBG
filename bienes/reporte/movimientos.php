<?php
ob_end_clean();
session_start();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once( '../../lib/fpdf/fpdf.php' );
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
//$_SESSION['conexionsql']->mysql_query("SET NAMES 'utf8'");

//if ($_SESSION['VERIFICADO'] != "SI") { 
//    header ("Location: ../index.php?errorusuario=val"); 
//    exit(); 
//	}

class PDF extends FPDF {
  function Header() {
    $fecha = ( $_GET[ 'fecha1' ] );
    $fechaf = ( $_GET[ 'fecha2' ] );
    $fecha1 = voltea_fecha( $_GET[ 'fecha1' ] );
    $fecha2 = voltea_fecha( $_GET[ 'fecha2' ] );
    $dependencia = decriptar( $_GET[ 'division' ] );

    $this->SetFillColor( 230 );
    $this->Image( '../../images/logo_nuevo.jpg', 40, 15, 37 );
    //	$this->Image('../../images/admon.png',210,20,35);
    $this->SetFont( 'Times', '', 11 );

    // ---------------------
    //$instituto = instituto();
    $this->SetY( 20 );
    $this->SetFont( 'Times', 'I', 11.5 );
    $this->Cell( 0, 5, 'República Bolivariana de Venezuela', 0, 0, 'C' );
    $this->Ln( 5 );
    $this->Cell( 0, 5, 'Contraloria del Estado Bolivariano de Guárico', 0, 0, 'C' );
    $this->Ln( 5 );
    $this->Cell( 0, 5, 'Dirección de Bienes, Materiales, Suministros y Archivo', 0, 0, 'C' );
    $this->Ln( 5 );
    $this->Cell( 0, 5, 'Rif G-20001287-0', 0, 0, 'C' );
    $this->Ln( 8 );

    $this->SetFont( 'Times', 'B', 12 );
    $this->Cell( 0, 5, 'Bitacora de Movimientos de Bienes del ' . ( $fecha ) . ' al ' . ( $fechaf ), 0, 0, 'C' );
    if ( $dependencia > 0 ) {
      $this->Ln( 6 );
      $dependencia = division_bienes( $dependencia );
      $this->Cell( 0, 5, $dependencia[ 0 ], 0, 0, 'C' );
    }
    $this->Ln( 10 );
  }

  function Footer() {
    $this->SetFont( 'Times', 'I', 8 );
    $this->SetY( -13 );
    $this->SetTextColor( 120 );
    //--------------
    $this->Cell( 100, 10, 'Impreso: ' . $_SESSION[ 'CEDULA_USUARIO' ] . ' ' . date( 'd/m/Y h:m' ), 0, 0, 'L' );
    $this->Cell( 0, 10, 'SIACEBG ' . $this->PageNo() . ' de {nb}', 0, 0, 'R' );
  }

  function VCell( $w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false ) {
    //Output a cell
    $k = $this->k;
    if ( $this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak() ) {
      //Automatic page break
      $x = $this->x;
      $ws = $this->ws;
      if ( $ws > 0 ) {
        $this->ws = 0;
        $this->_out( '0 Tw' );
      }
      $this->AddPage( $this->CurOrientation, $this->CurPageSize );
      $this->x = $x;
      if ( $ws > 0 ) {
        $this->ws = $ws;
        $this->_out( sprintf( '%.3F Tw', $ws * $k ) );
      }
    }
    if ( $w == 0 )
      $w = $this->w - $this->rMargin - $this->x;
    $s = '';
    // begin change Cell function 
    if ( $fill || $border > 0 ) {
      if ( $fill )
        $op = ( $border > 0 ) ? 'B' : 'f';
      else
        $op = 'S';
      if ( $border > 1 ) {
        $s = sprintf( 'q %.2F w %.2F %.2F %.2F %.2F re %s Q ', $border,
          $this->x * $k, ( $this->h - $this->y ) * $k, $w * $k, -$h * $k, $op );
      } else
        $s = sprintf( '%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ( $this->h - $this->y ) * $k, $w * $k, -$h * $k, $op );
    }
    if ( is_string( $border ) ) {
      $x = $this->x;
      $y = $this->y;
      if ( is_int( strpos( $border, 'L' ) ) )
        $s .= sprintf( '%.2F %.2F m %.2F %.2F l S ', $x * $k, ( $this->h - $y ) * $k, $x * $k, ( $this->h - ( $y + $h ) ) * $k );
      else if ( is_int( strpos( $border, 'l' ) ) )
        $s .= sprintf( 'q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ( $this->h - $y ) * $k, $x * $k, ( $this->h - ( $y + $h ) ) * $k );

      if ( is_int( strpos( $border, 'T' ) ) )
        $s .= sprintf( '%.2F %.2F m %.2F %.2F l S ', $x * $k, ( $this->h - $y ) * $k, ( $x + $w ) * $k, ( $this->h - $y ) * $k );
      else if ( is_int( strpos( $border, 't' ) ) )
        $s .= sprintf( 'q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ( $this->h - $y ) * $k, ( $x + $w ) * $k, ( $this->h - $y ) * $k );

      if ( is_int( strpos( $border, 'R' ) ) )
        $s .= sprintf( '%.2F %.2F m %.2F %.2F l S ', ( $x + $w ) * $k, ( $this->h - $y ) * $k, ( $x + $w ) * $k, ( $this->h - ( $y + $h ) ) * $k );
      else if ( is_int( strpos( $border, 'r' ) ) )
        $s .= sprintf( 'q 2 w %.2F %.2F m %.2F %.2F l S Q ', ( $x + $w ) * $k, ( $this->h - $y ) * $k, ( $x + $w ) * $k, ( $this->h - ( $y + $h ) ) * $k );

      if ( is_int( strpos( $border, 'B' ) ) )
        $s .= sprintf( '%.2F %.2F m %.2F %.2F l S ', $x * $k, ( $this->h - ( $y + $h ) ) * $k, ( $x + $w ) * $k, ( $this->h - ( $y + $h ) ) * $k );
      else if ( is_int( strpos( $border, 'b' ) ) )
        $s .= sprintf( 'q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ( $this->h - ( $y + $h ) ) * $k, ( $x + $w ) * $k, ( $this->h - ( $y + $h ) ) * $k );
    }
    if ( trim( $txt ) != '' ) {
      $cr = substr_count( $txt, "\n" );
      if ( $cr > 0 ) { // Multi line
        $txts = explode( "\n", $txt );
        $lines = count( $txts );
        for ( $l = 0; $l < $lines; $l++ ) {
          $txt = $txts[ $l ];
          $w_txt = $this->GetStringWidth( $txt );
          if ( $align == 'U' )
            $dy = $this->cMargin + $w_txt;
          elseif ( $align == 'D' )
            $dy = $h - $this->cMargin;
          else
            $dy = ( $h + $w_txt ) / 2;
          $txt = str_replace( ')', '\\)', str_replace( '(', '\\(', str_replace( '\\', '\\\\', $txt ) ) );
          if ( $this->ColorFlag )
            $s .= 'q ' . $this->TextColor . ' ';
          $s .= sprintf( 'BT 0 1 -1 0 %.2F %.2F Tm (%s) Tj ET ',
            ( $this->x + .5 * $w + ( .7 + $l - $lines / 2 ) * $this->FontSize ) * $k,
            ( $this->h - ( $this->y + $dy ) ) * $k, $txt );
          if ( $this->ColorFlag )
            $s .= ' Q ';
        }
      } else { // Single line
        $w_txt = $this->GetStringWidth( $txt );
        $Tz = 100;
        if ( $w_txt > $h - 2 * $this->cMargin ) {
          $Tz = ( $h - 2 * $this->cMargin ) / $w_txt * 100;
          $w_txt = $h - 2 * $this->cMargin;
        }
        if ( $align == 'U' )
          $dy = $this->cMargin + $w_txt;
        elseif ( $align == 'D' )
          $dy = $h - $this->cMargin;
        else
          $dy = ( $h + $w_txt ) / 2;
        $txt = str_replace( ')', '\\)', str_replace( '(', '\\(', str_replace( '\\', '\\\\', $txt ) ) );
        if ( $this->ColorFlag )
          $s .= 'q ' . $this->TextColor . ' ';
        $s .= sprintf( 'q BT 0 1 -1 0 %.2F %.2F Tm %.2F Tz (%s) Tj ET Q ',
          ( $this->x + .5 * $w + .3 * $this->FontSize ) * $k,
          ( $this->h - ( $this->y + $dy ) ) * $k, $Tz, $txt );
        if ( $this->ColorFlag )
          $s .= ' Q ';
      }
    }
    // end change Cell function 
    if ( $s )
      $this->_out( $s );
    $this->lasth = $h;
    if ( $ln > 0 ) {
      //Go to next line
      $this->y += $h;
      if ( $ln == 1 )
        $this->x = $this->lMargin;
    } else
      $this->x += $w;
  }

  function Cell( $w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '' ) {
    //Output a cell
    $k = $this->k;
    if ( $this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak() ) {
      //Automatic page break
      $x = $this->x;
      $ws = $this->ws;
      if ( $ws > 0 ) {
        $this->ws = 0;
        $this->_out( '0 Tw' );
      }
      $this->AddPage( $this->CurOrientation, $this->CurPageSize );
      $this->x = $x;
      if ( $ws > 0 ) {
        $this->ws = $ws;
        $this->_out( sprintf( '%.3F Tw', $ws * $k ) );
      }
    }
    if ( $w == 0 )
      $w = $this->w - $this->rMargin - $this->x;
    $s = '';
    // begin change Cell function
    if ( $fill || $border > 0 ) {
      if ( $fill )
        $op = ( $border > 0 ) ? 'B' : 'f';
      else
        $op = 'S';
      if ( $border > 1 ) {
        $s = sprintf( 'q %.2F w %.2F %.2F %.2F %.2F re %s Q ', $border,
          $this->x * $k, ( $this->h - $this->y ) * $k, $w * $k, -$h * $k, $op );
      } else
        $s = sprintf( '%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ( $this->h - $this->y ) * $k, $w * $k, -$h * $k, $op );
    }
    if ( is_string( $border ) ) {
      $x = $this->x;
      $y = $this->y;
      if ( is_int( strpos( $border, 'L' ) ) )
        $s .= sprintf( '%.2F %.2F m %.2F %.2F l S ', $x * $k, ( $this->h - $y ) * $k, $x * $k, ( $this->h - ( $y + $h ) ) * $k );
      else if ( is_int( strpos( $border, 'l' ) ) )
        $s .= sprintf( 'q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ( $this->h - $y ) * $k, $x * $k, ( $this->h - ( $y + $h ) ) * $k );

      if ( is_int( strpos( $border, 'T' ) ) )
        $s .= sprintf( '%.2F %.2F m %.2F %.2F l S ', $x * $k, ( $this->h - $y ) * $k, ( $x + $w ) * $k, ( $this->h - $y ) * $k );
      else if ( is_int( strpos( $border, 't' ) ) )
        $s .= sprintf( 'q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ( $this->h - $y ) * $k, ( $x + $w ) * $k, ( $this->h - $y ) * $k );

      if ( is_int( strpos( $border, 'R' ) ) )
        $s .= sprintf( '%.2F %.2F m %.2F %.2F l S ', ( $x + $w ) * $k, ( $this->h - $y ) * $k, ( $x + $w ) * $k, ( $this->h - ( $y + $h ) ) * $k );
      else if ( is_int( strpos( $border, 'r' ) ) )
        $s .= sprintf( 'q 2 w %.2F %.2F m %.2F %.2F l S Q ', ( $x + $w ) * $k, ( $this->h - $y ) * $k, ( $x + $w ) * $k, ( $this->h - ( $y + $h ) ) * $k );

      if ( is_int( strpos( $border, 'B' ) ) )
        $s .= sprintf( '%.2F %.2F m %.2F %.2F l S ', $x * $k, ( $this->h - ( $y + $h ) ) * $k, ( $x + $w ) * $k, ( $this->h - ( $y + $h ) ) * $k );
      else if ( is_int( strpos( $border, 'b' ) ) )
        $s .= sprintf( 'q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ( $this->h - ( $y + $h ) ) * $k, ( $x + $w ) * $k, ( $this->h - ( $y + $h ) ) * $k );
    }
    if ( trim( $txt ) != '' ) {
      $cr = substr_count( $txt, "\n" );
      if ( $cr > 0 ) { // Multi line
        $txts = explode( "\n", $txt );
        $lines = count( $txts );
        for ( $l = 0; $l < $lines; $l++ ) {
          $txt = $txts[ $l ];
          $w_txt = $this->GetStringWidth( $txt );
          if ( $align == 'R' )
            $dx = $w - $w_txt - $this->cMargin;
          elseif ( $align == 'C' )
            $dx = ( $w - $w_txt ) / 2;
          else
            $dx = $this->cMargin;

          $txt = str_replace( ')', '\\)', str_replace( '(', '\\(', str_replace( '\\', '\\\\', $txt ) ) );
          if ( $this->ColorFlag )
            $s .= 'q ' . $this->TextColor . ' ';
          $s .= sprintf( 'BT %.2F %.2F Td (%s) Tj ET ',
            ( $this->x + $dx ) * $k,
            ( $this->h - ( $this->y + .5 * $h + ( .7 + $l - $lines / 2 ) * $this->FontSize ) ) * $k,
            $txt );
          if ( $this->underline )
            $s .= ' ' . $this->_dounderline( $this->x + $dx, $this->y + .5 * $h + .3 * $this->FontSize, $txt );
          if ( $this->ColorFlag )
            $s .= ' Q ';
          if ( $link )
            $this->Link( $this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $w_txt, $this->FontSize, $link );
        }
      } else { // Single line
        $w_txt = $this->GetStringWidth( $txt );
        $Tz = 100;
        if ( $w_txt > $w - 2 * $this->cMargin ) { // Need compression
          $Tz = ( $w - 2 * $this->cMargin ) / $w_txt * 100;
          $w_txt = $w - 2 * $this->cMargin;
        }
        if ( $align == 'R' )
          $dx = $w - $w_txt - $this->cMargin;
        elseif ( $align == 'C' )
          $dx = ( $w - $w_txt ) / 2;
        else
          $dx = $this->cMargin;
        $txt = str_replace( ')', '\\)', str_replace( '(', '\\(', str_replace( '\\', '\\\\', $txt ) ) );
        if ( $this->ColorFlag )
          $s .= 'q ' . $this->TextColor . ' ';
        $s .= sprintf( 'q BT %.2F %.2F Td %.2F Tz (%s) Tj ET Q ',
          ( $this->x + $dx ) * $k,
          ( $this->h - ( $this->y + .5 * $h + .3 * $this->FontSize ) ) * $k,
          $Tz, $txt );
        if ( $this->underline )
          $s .= ' ' . $this->_dounderline( $this->x + $dx, $this->y + .5 * $h + .3 * $this->FontSize, $txt );
        if ( $this->ColorFlag )
          $s .= ' Q ';
        if ( $link )
          $this->Link( $this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $w_txt, $this->FontSize, $link );
      }
    }
    // end change Cell function
    if ( $s )
      $this->_out( $s );
    $this->lasth = $h;
    if ( $ln > 0 ) {
      //Go to next line
      $this->y += $h;
      if ( $ln == 1 )
        $this->x = $this->lMargin;
    } else
      $this->x += $w;
  }
}

$fecha = ( $_GET[ 'fecha1' ] );
$fechaf = ( $_GET[ 'fecha2' ] );
$fecha1 = voltea_fecha( $_GET[ 'fecha1' ] );
$fecha2 = voltea_fecha( $_GET[ 'fecha2' ] );
$dependencia = decriptar( $_GET[ 'division' ] );
$bien = decriptar( $_GET[ 'bien' ] );

// ENCABEZADO
$pdf = new PDF( 'L', 'mm', 'OFICIO' );
$pdf->AliasNbPages();
$pdf->SetMargins( 17, 12, 17 );
$pdf->SetAutoPageBreak( 1, 15 );
$pdf->SetTitle( 'Reasignaciones Realizadas' );
$pdf->SetFillColor( 230 );

$pdf->AddPage();
$pdf->SetFont( 'Times', 'B', 11 );

// ----------
$pdf->Cell( 17, 7, 'Incorporación', 1, 0, 'C', 1 );
$pdf->Cell( 15, 7, 'Código', 1, 0, 'C', 1 );
$pdf->Cell( 100, 7, 'Descripcion', 1, 0, 'C', 1 );
$pdf->Cell( 17, 7, 'Concepto', 1, 0, 'C', 1 );
$pdf->Cell( 17, 7, 'Movimiento', 1, 0, 'C', 1 );
$pdf->Cell( 65, 7, 'Origen', 1, 0, 'C', 1 );
$pdf->Cell( 0, 7, 'Destino', 1, 0, 'C', 1 );
$pdf->Ln( 7 );
// ----------
if ( $dependencia > 0 ) {
  $dependencia = " AND (bn_reasignaciones_detalle.id_destino = $dependencia OR 	bn_reasignaciones_detalle.id_origen = $dependencia) ";
}

if ( $bien > 0 ) {
  $bien = " AND bn_reasignaciones_detalle.id_bien = $bien ";
} else {
  $bien = '';
}

$consulta1 = "SELECT bn_reasignaciones_detalle.motivo, bn_bienes.*, bn_categorias.codigo, bn_dependencias.division AS origen, bn_dependencias2.division as destino, bn_reasignaciones.fecha as fecha_mov FROM bn_reasignaciones, bn_categorias, bn_reasignaciones_detalle, bn_bienes, bn_dependencias , bn_dependencias as bn_dependencias2 WHERE bn_reasignaciones_detalle.id_destino = bn_dependencias2.id AND 	bn_reasignaciones_detalle.id_origen = bn_dependencias.id AND  bn_reasignaciones_detalle.id_reasignacion=bn_reasignaciones.id AND bn_categorias.id_categoria=bn_bienes.id_categoria AND bn_bienes.id_bien = bn_reasignaciones_detalle.id_bien AND bn_reasignaciones.fecha >= '$fecha1' AND bn_reasignaciones.fecha <= '$fecha2' $dependencia $bien";
//echo $consulta1;
$tabla1 = $_SESSION[ 'conexionsql' ]->query( $consulta1 );
//-----------------
$pdf->SetFont( 'Times', '', 8 );
$i = 0;

while ( $registro1 = $tabla1->fetch_object() ) {
  $i++;
  $total += $registro1->valor;
  // ----------
  if ( $pdf->GetY() > 181 ) {
    $pdf->AddPage();
  }
  //----- PARA ARRANCAR CON LA LINEA
  $y1 = $pdf->GetY();
  $x = $pdf->GetX();
  $pdf->SetX( 32 + 17 );
  //-----------------------------------------MULTICELL
  $pdf->MultiCell( 100, 5, ( ( $registro1->descripcion_bien ) ), 1, 'J' );
  $y2 = $pdf->GetY();
  //- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
  $pdf->SetY( $y1 );
  $pdf->SetX( $x );
  $alto2 = $y2 - $y1;
  //-------------------
  $pdf->Cell( 17, $alto2, voltea_fecha( $registro1->fecha_adquisicion ) . $pdf->GetY(), 1, 0, 'C', 0 );
  $pdf->SetFont( 'Times', '', 10 );
  $pdf->Cell( 15, $alto2, ( $registro1->numero_bien ), 1, 0, 'C', 0 );
  $pdf->SetFont( 'Times', '', 8 );
  $pdf->Cell( 100, $alto2, '' );
  $pdf->Cell( 17, $alto2, ( $registro1->motivo ), 1, 0, 'C', 0 );
  $pdf->Cell( 17, $alto2, voltea_fecha( $registro1->fecha_mov ), 1, 0, 'C', 0 );
  $pdf->Cell( 65, $alto2, ( $registro1->origen ), 1, 0, 'L', 0 );
  $pdf->Cell( 0, $alto2, ( $registro1->destino ), 1, 0, 'L', 0 );
  // ----------
  $pdf->Ln( $alto2 );
}

if ( $i == 0 ) {
  $pdf->SetFont( 'Times', 'B', 12 );
  $pdf->Cell( 0, 10, 'NO HUBO', 1, 0, 'C', 0 );
  // ----------
  $pdf->Ln();
}

$pdf->SetFont( 'Times', 'B', 12 );
$pdf->Cell( 132, 7, '' );
$pdf->Cell( 0, 7, "Cant. Movimientos Realizados => $i", 1, 0, 'C', 1 );
//$pdf->Cell(20,7,$i,1,0,'C',1);
//$pdf->Cell(0,7,'Total Bs => '.formato_moneda($total),1,1,'R',1);

//$firma1 = firma(14);
//
//$pdf->SetFont('Times','B',10);
//$pdf->SetY(-45);
//$pdf->Cell(70,6,'ELABORADO POR:',1,1,'L',1);
//$pdf->Cell(70,6,$firma1[1],0,1,'L',0);
//$pdf->Cell(70,6,$firma1[2],0,1,'L',0);

$pdf->Output();
?>