<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once( '../../lib/fpdf/fpdf.php' );
$_SESSION[ 'conexionsql' ]->query( "SET NAMES 'latin1'" );

class PDF_WriteTag extends FPDF {
  function Header() {
    //		$this->Image('../../images/logo_nuevo.jpg',22,17,35);

    //		$this->SetY(20);
    //		$this->SetFont('Times','I',12);
    //		//$this->Cell(15,5,'');
    //		$this->Cell(0,5,'REPÚBLICA BOLIVARIANA DE VENEZUELA',0,0,'C'); 
  }

  function Footer() {
    //--------------
    $this->SetFont( 'Times', 'I', 8 );
    $this->SetY( -13 );
    $this->SetTextColor( 120 );
    //--------------
    //		$usuario = persona($_SESSION['usuario']);
    $this->Cell( 0, 0, 'SIACEBG' . ' ' . $this->PageNo() . ' de paginas', 0, 0, 'R' );

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

  function WriteTag( $w, $h, $txt, $border = 0, $align = "J", $fill = false, $padding = 0 ) {
    $this->wLine = $w;
    $this->hLine = $h;
    $this->Text = trim( $txt );
    $this->Text = preg_replace( "/\n|\r|\t/", "", $this->Text );
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

    while ( $this->Text != "" ) {
      $this->MakeLine();
      $this->PrintLine();
    }

    $this->BorderBottom();
  }


  function SetStyle( $tag, $family, $style, $size, $color, $indent = -1 ) {
    $tag = trim( $tag );
    $this->TagStyle[ $tag ][ 'family' ] = trim( $family );
    $this->TagStyle[ $tag ][ 'style' ] = trim( $style );
    $this->TagStyle[ $tag ][ 'size' ] = trim( $size );
    $this->TagStyle[ $tag ][ 'color' ] = trim( $color );
    $this->TagStyle[ $tag ][ 'indent' ] = $indent;
  }


  // Private Functions

  function SetSpace() // Minimal space between words
  {
    $tag = $this->Parser( $this->Text );
    $this->FindStyle( $tag[ 2 ], 0 );
    $this->DoStyle( 0 );
    $this->Space = $this->GetStringWidth( " " );
  }


  function Padding() {
    if ( preg_match( "/^.+,/", $this->Padding ) ) {
      $tab = explode( ",", $this->Padding );
      $this->lPadding = $tab[ 0 ];
      $this->tPadding = $tab[ 1 ];
      if ( isset( $tab[ 2 ] ) )
        $this->bPadding = $tab[ 2 ];
      else
        $this->bPadding = $this->tPadding;
      if ( isset( $tab[ 3 ] ) )
        $this->rPadding = $tab[ 3 ];
      else
        $this->rPadding = $this->lPadding;
    } else {
      $this->lPadding = $this->Padding;
      $this->tPadding = $this->Padding;
      $this->bPadding = $this->Padding;
      $this->rPadding = $this->Padding;
    }
    if ( $this->tPadding < $this->LineWidth )
      $this->tPadding = $this->LineWidth;
  }


  function LineLength() {
    if ( $this->wLine == 0 )
      $this->wLine = $this->w - $this->Xini - $this->rMargin;

    $this->wTextLine = $this->wLine - $this->lPadding - $this->rPadding;
  }


  function BorderTop() {
    $border = 0;
    if ( $this->border == 1 )
      $border = "TLR";
    $this->Cell( $this->wLine, $this->tPadding, "", $border, 0, 'C', $this->fill );
    $y = $this->GetY() + $this->tPadding;
    $this->SetXY( $this->Xini, $y );
  }


  function BorderBottom() {
    $border = 0;
    if ( $this->border == 1 )
      $border = "BLR";
    $this->Cell( $this->wLine, $this->bPadding, "", $border, 0, 'C', $this->fill );
  }


  function DoStyle( $tag ) // Applies a style
  {
    $tag = trim( $tag );
    $this->SetFont( $this->TagStyle[ $tag ][ 'family' ],
      $this->TagStyle[ $tag ][ 'style' ],
      $this->TagStyle[ $tag ][ 'size' ] );

    $tab = explode( ",", $this->TagStyle[ $tag ][ 'color' ] );
    if ( count( $tab ) == 1 )
      $this->SetTextColor( $tab[ 0 ] );
    else
      $this->SetTextColor( $tab[ 0 ], $tab[ 1 ], $tab[ 2 ] );
  }


  function FindStyle( $tag, $ind ) // Inheritance from parent elements
  {
    $tag = trim( $tag );

    // Family
    if ( $this->TagStyle[ $tag ][ 'family' ] != "" )
      $family = $this->TagStyle[ $tag ][ 'family' ];
    else {
      foreach ( $this->PileStyle as $val ) {
        $val = trim( $val );
        if ( $this->TagStyle[ $val ][ 'family' ] != "" ) {
          $family = $this->TagStyle[ $val ][ 'family' ];
          break;
        }
      }
    }

    // Style
    $style = "";
    $style1 = strtoupper( $this->TagStyle[ $tag ][ 'style' ] );
    if ( $style1 != "N" ) {
      $bold = false;
      $italic = false;
      $underline = false;
      foreach ( $this->PileStyle as $val ) {
        $val = trim( $val );
        $style1 = strtoupper( $this->TagStyle[ $val ][ 'style' ] );
        if ( $style1 == "N" )
          break;
        else {
          if ( strpos( $style1, "B" ) !== false )
            $bold = true;
          if ( strpos( $style1, "I" ) !== false )
            $italic = true;
          if ( strpos( $style1, "U" ) !== false )
            $underline = true;
        }
      }
      if ( $bold )
        $style .= "B";
      if ( $italic )
        $style .= "I";
      if ( $underline )
        $style .= "U";
    }

    // Size
    if ( $this->TagStyle[ $tag ][ 'size' ] != 0 )
      $size = $this->TagStyle[ $tag ][ 'size' ];
    else {
      foreach ( $this->PileStyle as $val ) {
        $val = trim( $val );
        if ( $this->TagStyle[ $val ][ 'size' ] != 0 ) {
          $size = $this->TagStyle[ $val ][ 'size' ];
          break;
        }
      }
    }

    // Color
    if ( $this->TagStyle[ $tag ][ 'color' ] != "" )
      $color = $this->TagStyle[ $tag ][ 'color' ];
    else {
      foreach ( $this->PileStyle as $val ) {
        $val = trim( $val );
        if ( $this->TagStyle[ $val ][ 'color' ] != "" ) {
          $color = $this->TagStyle[ $val ][ 'color' ];
          break;
        }
      }
    }

    // Result
    $this->TagStyle[ $ind ][ 'family' ] = $family;
    $this->TagStyle[ $ind ][ 'style' ] = $style;
    $this->TagStyle[ $ind ][ 'size' ] = $size;
    $this->TagStyle[ $ind ][ 'color' ] = $color;
    $this->TagStyle[ $ind ][ 'indent' ] = $this->TagStyle[ $tag ][ 'indent' ];
  }


  function Parser( $text ) {
    $tab = array();
    // Closing tag
    if ( preg_match( "|^(</([^>]+)>)|", $text, $regs ) ) {
      $tab[ 1 ] = "c";
      $tab[ 2 ] = trim( $regs[ 2 ] );
    }
    // Opening tag
    else if ( preg_match( "|^(<([^>]+)>)|", $text, $regs ) ) {
      $regs[ 2 ] = preg_replace( "/^a/", "a ", $regs[ 2 ] );
      $tab[ 1 ] = "o";
      $tab[ 2 ] = trim( $regs[ 2 ] );

      // Presence of attributes
      if ( preg_match( "/(.+) (.+)='(.+)'/", $regs[ 2 ] ) ) {
        $tab1 = preg_split( "/ +/", $regs[ 2 ] );
        $tab[ 2 ] = trim( $tab1[ 0 ] );
        foreach ( $tab1 as $i => $couple ) {
          if ( $i > 0 ) {
            $tab2 = explode( "=", $couple );
            $tab2[ 0 ] = trim( $tab2[ 0 ] );
            $tab2[ 1 ] = trim( $tab2[ 1 ] );
            $end = strlen( $tab2[ 1 ] ) - 2;
            $tab[ $tab2[ 0 ] ] = substr( $tab2[ 1 ], 1, $end );
          }
        }
      }
    }
    // Space
    else if ( preg_match( "/^( )/", $text, $regs ) ) {
      $tab[ 1 ] = "s";
      $tab[ 2 ] = ' ';
    }
    // Text
    else if ( preg_match( "/^([^< ]+)/", $text, $regs ) ) {
      $tab[ 1 ] = "t";
      $tab[ 2 ] = trim( $regs[ 1 ] );
    }

    $begin = strlen( $regs[ 1 ] );
    $end = strlen( $text );
    $text = substr( $text, $begin, $end );
    $tab[ 0 ] = $text;

    return $tab;
  }


  function MakeLine() {
    $this->Text .= " ";
    $this->LineLength = array();
    $this->TagHref = array();
    $Length = 0;
    $this->nbSpace = 0;

    $i = $this->BeginLine();
    $this->TagName = array();

    if ( $i == 0 ) {
      $Length = $this->StringLength[ 0 ];
      $this->TagName[ 0 ] = 1;
      $this->TagHref[ 0 ] = $this->href;
    }

    while ( $Length < $this->wTextLine ) {
      $tab = $this->Parser( $this->Text );
      $this->Text = $tab[ 0 ];
      if ( $this->Text == "" ) {
        $this->LastLine = true;
        break;
      }

      if ( $tab[ 1 ] == "o" ) {
        array_unshift( $this->PileStyle, $tab[ 2 ] );
        $this->FindStyle( $this->PileStyle[ 0 ], $i + 1 );

        $this->DoStyle( $i + 1 );
        $this->TagName[ $i + 1 ] = 1;
        if ( $this->TagStyle[ $tab[ 2 ] ][ 'indent' ] != -1 ) {
          $Length += $this->TagStyle[ $tab[ 2 ] ][ 'indent' ];
          $this->Indent = $this->TagStyle[ $tab[ 2 ] ][ 'indent' ];
        }
        if ( $tab[ 2 ] == "a" )
          $this->href = $tab[ 'href' ];
      }

      if ( $tab[ 1 ] == "c" ) {
        array_shift( $this->PileStyle );
        if ( isset( $this->PileStyle[ 0 ] ) ) {
          $this->FindStyle( $this->PileStyle[ 0 ], $i + 1 );
          $this->DoStyle( $i + 1 );
        }
        $this->TagName[ $i + 1 ] = 1;
        if ( $this->TagStyle[ $tab[ 2 ] ][ 'indent' ] != -1 ) {
          $this->LastLine = true;
          $this->Text = trim( $this->Text );
          break;
        }
        if ( $tab[ 2 ] == "a" )
          $this->href = "";
      }

      if ( $tab[ 1 ] == "s" ) {
        $i++;
        $Length += $this->Space;
        $this->Line2Print[ $i ] = "";
        if ( $this->href != "" )
          $this->TagHref[ $i ] = $this->href;
      }

      if ( $tab[ 1 ] == "t" ) {
        $i++;
        $this->StringLength[ $i ] = $this->GetStringWidth( $tab[ 2 ] );
        $Length += $this->StringLength[ $i ];
        $this->LineLength[ $i ] = $Length;
        $this->Line2Print[ $i ] = $tab[ 2 ];
        if ( $this->href != "" )
          $this->TagHref[ $i ] = $this->href;
      }

    }

    trim( $this->Text );
    if ( $Length > $this->wTextLine || $this->LastLine == true )
      $this->EndLine();
  }


  function BeginLine() {
    $this->Line2Print = array();
    $this->StringLength = array();

    if ( isset( $this->PileStyle[ 0 ] ) ) {
      $this->FindStyle( $this->PileStyle[ 0 ], 0 );
      $this->DoStyle( 0 );
    }

    if ( count( $this->NextLineBegin ) > 0 ) {
      $this->Line2Print[ 0 ] = $this->NextLineBegin[ 'text' ];
      $this->StringLength[ 0 ] = $this->NextLineBegin[ 'length' ];
      $this->NextLineBegin = array();
      $i = 0;
    } else {
      preg_match( "/^(( *(<([^>]+)>)* *)*)(.*)/", $this->Text, $regs );
      $regs[ 1 ] = str_replace( " ", "", $regs[ 1 ] );
      $this->Text = $regs[ 1 ] . $regs[ 5 ];
      $i = -1;
    }

    return $i;
  }


  function EndLine() {
    if ( end( $this->Line2Print ) != "" && $this->LastLine == false ) {
      $this->NextLineBegin[ 'text' ] = array_pop( $this->Line2Print );
      $this->NextLineBegin[ 'length' ] = end( $this->StringLength );
      array_pop( $this->LineLength );
    }

    while ( end( $this->Line2Print ) === "" )
      array_pop( $this->Line2Print );

    $this->Delta = $this->wTextLine - end( $this->LineLength );

    $this->nbSpace = 0;
    for ( $i = 0; $i < count( $this->Line2Print ); $i++ ) {
      if ( $this->Line2Print[ $i ] == "" )
        $this->nbSpace++;
    }
  }


  function PrintLine() {
    $border = 0;
    if ( $this->border == 1 )
      $border = "LR";
    $this->Cell( $this->wLine, $this->hLine, "", $border, 0, 'C', $this->fill );
    $y = $this->GetY();
    $this->SetXY( $this->Xini + $this->lPadding, $y );

    if ( $this->Indent != -1 ) {
      if ( $this->Indent != 0 )
        $this->Cell( $this->Indent, $this->hLine );
      $this->Indent = -1;
    }

    $space = $this->LineAlign();
    $this->DoStyle( 0 );
    for ( $i = 0; $i < count( $this->Line2Print ); $i++ ) {
      if ( isset( $this->TagName[ $i ] ) )
        $this->DoStyle( $i );
      if ( isset( $this->TagHref[ $i ] ) )
        $href = $this->TagHref[ $i ];
      else
        $href = '';
      if ( $this->Line2Print[ $i ] == "" )
        $this->Cell( $space, $this->hLine, "         ", 0, 0, 'C', false, $href );
      else
        $this->Cell( $this->StringLength[ $i ], $this->hLine, $this->Line2Print[ $i ], 0, 0, 'C', false, $href );
    }

    $this->LineBreak();
    if ( $this->LastLine && $this->Text != "" )
      $this->EndParagraph();
    $this->LastLine = false;
  }


  function LineAlign() {
    $space = $this->Space;
    if ( $this->align == "J" ) {
      if ( $this->nbSpace != 0 )
        $space = $this->Space + ( $this->Delta / $this->nbSpace );
      if ( $this->LastLine )
        $space = $this->Space;
    }

    if ( $this->align == "R" )
      $this->Cell( $this->Delta, $this->hLine );

    if ( $this->align == "C" )
      $this->Cell( $this->Delta / 2, $this->hLine );

    return $space;
  }


  function LineBreak() {
    $x = $this->Xini;
    $y = $this->GetY() + $this->hLine;
    $this->SetXY( $x, $y );
  }


  function EndParagraph() {
    $border = 0;
    if ( $this->border == 1 )
      $border = "LR";
    $this->Cell( $this->wLine, $this->hLine / 2, "", $border, 0, 'C', $this->fill );
    $x = $this->Xini;
    $y = $this->GetY() + $this->hLine / 2;
    $this->SetXY( $x, $y );
  }


  function TextWithDirection( $x, $y, $txt, $direction = 'R' ) {
    if ( $direction == 'R' )
      $s = sprintf( 'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 1, 0, 0, 1, $x * $this->k, ( $this->h - $y ) * $this->k, $this->_escape( $txt ) );
    elseif ( $direction == 'L' )
      $s = sprintf( 'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', -1, 0, 0, -1, $x * $this->k, ( $this->h - $y ) * $this->k, $this->_escape( $txt ) );
    elseif ( $direction == 'U' )
      $s = sprintf( 'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, 1, -1, 0, $x * $this->k, ( $this->h - $y ) * $this->k, $this->_escape( $txt ) );
    elseif ( $direction == 'D' )
      $s = sprintf( 'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, -1, 1, 0, $x * $this->k, ( $this->h - $y ) * $this->k, $this->_escape( $txt ) );
    else
      $s = sprintf( 'BT %.2F %.2F Td (%s) Tj ET', $x * $this->k, ( $this->h - $y ) * $this->k, $this->_escape( $txt ) );
    if ( $this->ColorFlag )
      $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
    $this->_out( $s );
  }

  function TextWithRotation( $x, $y, $txt, $txt_angle, $font_angle = 0 ) {
    $font_angle += 90 + $txt_angle;
    $txt_angle *= M_PI / 180;
    $font_angle *= M_PI / 180;

    $txt_dx = cos( $txt_angle );
    $txt_dy = sin( $txt_angle );
    $font_dx = cos( $font_angle );
    $font_dy = sin( $font_angle );

    $s = sprintf( 'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', $txt_dx, $txt_dy, $font_dx, $font_dy, $x * $this->k, ( $this->h - $y ) * $this->k, $this->_escape( $txt ) );
    if ( $this->ColorFlag )
      $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
    $this->_out( $s );
  }
}

//--------------
$pdf = new PDF_WriteTag( 'P', 'mm', 'LETTER' );
$pdf->AliasNbPages( 'paginas' );
$pdf->SetMargins( 20, 15, 20 );
$pdf->SetFont( 'Times', '', 11 );
$pdf->SetTitle('Traslados');
$pdf->AddPage();
$pdf->SetFillColor( 240 );
$linea = 7;

// Stylesheet
$pdf->SetStyle( "strong", "Times", "B", 12, "0,0,0" );
$pdf->SetStyle( "n", "Times", "", 12, "0,0,0" );

// Title
//$pdf->SetFont('Times','B',13);
//$txt="DECRETO Nº AMM- OPP- ".rellena_cero($numero,3)."/".$anno;
//$pdf->SetTitle($txt);
//$pdf->Cell(0,0,$txt);
//$pdf->Ln(7);

$id = decriptar( $_GET[ 'id' ] );
/////// DISMINUCION
$consultx = "SELECT * FROM traslados WHERE id_traspaso = $id LIMIT 1;";
//echo $consultx;
$tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
if ( $tablx->num_rows > 0 ) {
	$registro = $tablx->fetch_object();
	$resolucion = $registro->concepto;
	$fecha = $registro->fecha;
	$pdf->SetFont( 'Times', 'B', 12 );
	$txt = $resolucion." de fecha ".voltea_fecha($fecha);
	$pdf->Cell( 0, 6, $txt, 0, 0, 'L', 0 );
	$pdf->Ln();
}

/////// DISMINUCION
$consultx = "SELECT * FROM traslados, a_partidas WHERE id_traspaso = $id AND monto1>0 AND a_partidas.codigo = traslados.partida1 ORDER BY categoria1, partida1, monto1;";
//echo $consultx;
$tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
if ( $tablx->num_rows > 0 ) {
  $pdf->SetFont( 'Times', 'B', 12 );
  $txt = "PARTIDAS A DISMINUIR";
  $pdf->Cell( 0, 6, $txt, 0, 0, 'L', 0 );
  $pdf->Ln();
}
//-------------
$i = 0;
$total = 0;
while ( $registro = $tablx->fetch_object() ) {
	if ($pdf->GetY()>249)	{
		$pdf->AddPage();
	}
  $i++;
  //------------
  $categoria = trim( $registro->categoria1 );
  $partida = trim( $registro->partida1 );
  $descripcion = trim( $registro->descripcion );
  $monto = formato_moneda( $registro->monto1 );
  //------------
  if ( $cat_control <> $categoria ) {
	 
	if ($i > 1 ) {
		$pdf->SetFont( 'Times', 'B', 11 );
		$pdf->Cell( 149, 7, 'ACT. ' . substr( $cat_control, 8, 2 ) . ' TOTAL ' . $par_control . ' XXXXXXX ', 1, 0, 'R', 1 );
		$pdf->Cell( 0, 7, formato_moneda( $subtotalp ), 1, 0, 'R', 1 );
		$pdf->Ln();
		$pdf->Cell( 149, 7, 'TOTAL A DISMINUIR ACT. ' . substr( $cat_control, 8, 2 ), 1, 0, 'R', 1 );
		$pdf->Cell( 0, 7, formato_moneda( $subtotala ), 1, 0, 'R', 1 );
		$pdf->Ln();
		$subtotalp = 0;
		$subtotala = 0;
		}

    $pdf->Ln();
	 //-----------
    $pdf->SetFont( 'Times', 'B', 10 );
	$x = $pdf->GetX();
	  
	$pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 10, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 90, 32, 'DENOMINACIÓN', 1, 0, 'C', 1 );
    $pdf->Cell( 0, 32, 'MONTO EN BS.', 1, 0, 'L', 1 );

    $y = $pdf->GetY();

    $pdf->TextWithDirection( $x + 5, $y + 30, 'SECTOR', 'U' );
    $pdf->TextWithDirection( $x + 12, $y + 30, 'PROGRAMA', 'U' );
    $pdf->TextWithDirection( $x + 19, $y + 30, 'SUB-PROGRAMA', 'U' );
    $pdf->TextWithDirection( $x + 26, $y + 30, 'ACTIVIDAD', 'U' );
    $pdf->TextWithDirection( $x + 34, $y + 30, 'PARTIDA', 'U' );
    $pdf->TextWithDirection( $x + 42.5, $y + 30, 'GENÉRICA', 'U' );
    $pdf->TextWithDirection( $x + 49.5, $y + 30, 'ESPECÍFICA', 'U' );
    $pdf->TextWithDirection( $x + 56.5, $y + 30, 'SUB-ESPECÍFICA', 'U' );

    $pdf->Ln( 32 );
    $cat_control = $categoria;
    $par_control = substr( $partida, 0, 3 );
    //--------------
  }
  //------------
  if ( $par_control <> substr( $partida, 0, 3 ) and $i > 1 ) {
    //-----------
    $pdf->SetFont( 'Times', 'B', 11 );

    $pdf->Cell( 149, 7, 'ACT. ' . substr( $categoria, 8, 2 ) . ' TOTAL ' . $par_control . ' XXXXXXX ', 1, 0, 'R', 1 );
    $pdf->Cell( 0, 7, formato_moneda( $subtotalp ), 1, 0, 'R', 1 );

    $pdf->Ln();
    $pdf->Ln();
    $par_control = substr( $partida, 0, 3 );
    //--------------
    $subtotalp = 0;
  }
  //----------------------------
  $subtotalp = $subtotalp + $registro->monto1;
  $subtotala = $subtotala + $registro->monto1;
  $total = $total + $registro->monto1;
  //----------------------------
  $pdf->SetFont( 'Times', '', 10 );
  //----- PARA ARRANCAR CON LA LINEA
  $y1 = $pdf->GetY();
  $x = $pdf->GetX();
  $pdf->SetX( $x + 59 );
  //-----------------------------------------MULTICELL
  $pdf->MultiCell( 90, 6, $descripcion, 1, 'J' );
  $y2 = $pdf->GetY();
  //- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
  $pdf->SetY( $y1 );
  $pdf->SetX( $x );
  $alto2 = $y2 - $y1;
  //----------------------------
  $pdf->Cell( 7, $alto2, substr( $categoria, 1, 1 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $categoria, 3, 1 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $categoria, 5, 1 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $categoria, 8, 2 ), 1, 0, 'C', 0 );
  $pdf->Cell( 10, $alto2, substr( $partida, 0, 3 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $partida, 3, 2 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $partida, 5, 2 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $partida, 7, 2 ), 1, 0, 'L', 0 );
  $pdf->Cell( 0, $alto2, $monto, 1, 0, 'R', 0 );
  $pdf->Ln();
}
//-----------
$pdf->SetFont( 'Times', 'B', 11 );

$pdf->Cell( 149, 7, 'ACT. ' . substr( $cat_control, 8, 2 ) . ' TOTAL ' . $par_control . ' XXXXXXX ', 1, 0, 'R', 1 );
$pdf->Cell( 0, 7, formato_moneda( $subtotalp ), 1, 0, 'R', 1 );
$pdf->Ln();

$pdf->Cell( 149, 7, 'TOTAL A DISMINUIR ACT. ' . substr( $cat_control, 8, 2 ), 1, 0, 'R', 1 );
$pdf->Cell( 0, 7, formato_moneda( $subtotala ), 1, 0, 'R', 1 );
$pdf->Ln();
$pdf->Ln();

$pdf->Cell( 149, 7, 'TOTAL A DISMINUIR TODAS LAS ACTIVIDADES ', 1, 0, 'R', 1 );
$pdf->Cell( 0, 7, formato_moneda( $total ), 1, 0, 'R', 1 );
$pdf->Ln();
$pdf->Ln();

//-------------- fin de la disminucion
$i = 0;
$total = 0;
$subtotalp = 0;
$subtotala = 0;
$par_control = "";
$cat_control = "";

/////// AUMENTO
$consultx = "SELECT * FROM traslados, a_partidas WHERE id_traspaso = $id AND monto2>0 AND a_partidas.codigo = traslados.partida2 ORDER BY categoria2, partida2, monto2;";
//echo $consultx;
$tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
if ( $tablx->num_rows > 0 ) {
  $pdf->SetFont( 'Times', 'B', 12 );
  $txt = "PARTIDAS A INCREMENTAR";
  $pdf->Cell( 0, 6, $txt, 0, 0, 'L', 0 );
  $pdf->Ln();
}
//-------------
$i = 0;
$total = 0;
while ( $registro = $tablx->fetch_object() ) {
	if ($pdf->GetY()>249)	{
		$pdf->AddPage();
	}
  $i++;
  //------------
  $categoria = trim( $registro->categoria2 );
  $partida = trim( $registro->partida2 );
  $descripcion = trim( $registro->descripcion );
  $monto = formato_moneda( $registro->monto2 );
  //------------
  if ( $cat_control <> $categoria ) {
	 
	if ($i > 1 ) {
		$pdf->SetFont( 'Times', 'B', 11 );
		$pdf->Cell( 149, 7, 'ACT. ' . substr( $cat_control, 8, 2 ) . ' TOTAL ' . $par_control . ' XXXXXXX ', 1, 0, 'R', 1 );
		$pdf->Cell( 0, 7, formato_moneda( $subtotalp ), 1, 0, 'R', 1 );
		$pdf->Ln();
		$pdf->Cell( 149, 7, 'TOTAL A INCREMENTAR ACT. ' . substr( $cat_control, 8, 2 ), 1, 0, 'R', 1 );
		$pdf->Cell( 0, 7, formato_moneda( $subtotala ), 1, 0, 'R', 1 );
		$pdf->Ln();
		$subtotalp = 0;
		$subtotala = 0;
		}

    $pdf->Ln();
	 //-----------
    $pdf->SetFont( 'Times', 'B', 10 );
	$x = $pdf->GetX();
	  
	$pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 10, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 7, 32, '', 1, 0, 'L', 1 );
    $pdf->Cell( 90, 32, 'DENOMINACIÓN', 1, 0, 'C', 1 );
    $pdf->Cell( 0, 32, 'MONTO EN BS.', 1, 0, 'L', 1 );

    $y = $pdf->GetY();

    $pdf->TextWithDirection( $x + 5, $y + 30, 'SECTOR', 'U' );
    $pdf->TextWithDirection( $x + 12, $y + 30, 'PROGRAMA', 'U' );
    $pdf->TextWithDirection( $x + 19, $y + 30, 'SUB-PROGRAMA', 'U' );
    $pdf->TextWithDirection( $x + 26, $y + 30, 'ACTIVIDAD', 'U' );
    $pdf->TextWithDirection( $x + 34, $y + 30, 'PARTIDA', 'U' );
    $pdf->TextWithDirection( $x + 42.5, $y + 30, 'GENÉRICA', 'U' );
    $pdf->TextWithDirection( $x + 49.5, $y + 30, 'ESPECÍFICA', 'U' );
    $pdf->TextWithDirection( $x + 56.5, $y + 30, 'SUB-ESPECÍFICA', 'U' );

    $pdf->Ln( 32 );
    $cat_control = $categoria;
    $par_control = substr( $partida, 0, 3 );
    //--------------
  }
  //------------
  if ( $par_control <> substr( $partida, 0, 3 ) and $i > 1 ) {
    //-----------
    $pdf->SetFont( 'Times', 'B', 11 );

    $pdf->Cell( 149, 7, 'ACT. ' . substr( $categoria, 8, 2 ) . ' TOTAL ' . $par_control . ' XXXXXXX ', 1, 0, 'R', 1 );
    $pdf->Cell( 0, 7, formato_moneda( $subtotalp ), 1, 0, 'R', 1 );

    $pdf->Ln();
    $pdf->Ln();
    $par_control = substr( $partida, 0, 3 );
    //--------------
    $subtotalp = 0;
  }
  //----------------------------
  $subtotalp = $subtotalp + $registro->monto2;
  $subtotala = $subtotala + $registro->monto2;
  $total = $total + $registro->monto2;
  //----------------------------
  $pdf->SetFont( 'Times', '', 10 );
  //----- PARA ARRANCAR CON LA LINEA
  $y1 = $pdf->GetY();
  $x = $pdf->GetX();
  $pdf->SetX( $x + 59 );
  //-----------------------------------------MULTICELL
  $pdf->MultiCell( 90, 6, $descripcion, 1, 'J' );
  $y2 = $pdf->GetY();
  //- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
  $pdf->SetY( $y1 );
  $pdf->SetX( $x );
  $alto2 = $y2 - $y1;
  //----------------------------
  $pdf->Cell( 7, $alto2, substr( $categoria, 1, 1 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $categoria, 3, 1 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $categoria, 5, 1 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $categoria, 8, 2 ), 1, 0, 'C', 0 );
  $pdf->Cell( 10, $alto2, substr( $partida, 0, 3 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $partida, 3, 2 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $partida, 5, 2 ), 1, 0, 'C', 0 );
  $pdf->Cell( 7, $alto2, substr( $partida, 7, 2 ), 1, 0, 'L', 0 );
  $pdf->Cell( 0, $alto2, $monto, 1, 0, 'R', 0 );
  $pdf->Ln();
}
//-----------
$pdf->SetFont( 'Times', 'B', 11 );

$pdf->Cell( 149, 7, 'ACT. ' . substr( $cat_control, 8, 2 ) . ' TOTAL ' . $par_control . ' XXXXXXX ', 1, 0, 'R', 1 );
$pdf->Cell( 0, 7, formato_moneda( $subtotalp ), 1, 0, 'R', 1 );
$pdf->Ln();

$pdf->Cell( 149, 7, 'TOTAL A INCREMENTAR ACT. ' . substr( $cat_control, 8, 2 ), 1, 0, 'R', 1 );
$pdf->Cell( 0, 7, formato_moneda( $subtotala ), 1, 0, 'R', 1 );
$pdf->Ln();
$pdf->Ln();

$pdf->Cell( 149, 7, 'TOTAL A INCREMENTAR TODAS LAS ACTIVIDADES ', 1, 0, 'R', 1 );
$pdf->Cell( 0, 7, formato_moneda( $total ), 1, 0, 'R', 1 );

//-------------- fin de la disminucion

$pdf->Output();
?>
