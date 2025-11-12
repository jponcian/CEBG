<?php
session_start();
include "../../conexion.php";
include('../../funciones/auxiliar_php.php');
require('../../lib/fpdf/fpdf.php');
//mysql_query("SET NAMES 'latin1'");

$_SESSION['origen'] = decriptar($_GET['origen']);
$_SESSION['destino'] = decriptar($_GET['destino']);
$_SESSION['estatus'] = ($_GET['estatus']);
$_SESSION['id'] = decriptar($_GET['id']);

if ($_SESSION['VERIFICADO'] != "SI") {
  header("Location: index.php?errorusuario=val");
  exit();
}

class PDF extends FPDF
{
  function Header()
  {
    $_SESSION['fuente_cabecera'] = 8;
    $alto_cabecera = 3.5;

    global $color;
    $c = 30; //cedula	
    $d = 90; //nombres
    //------------------------
    $this->Image('../../images/logo_nuevo.jpg', 30, 15, 25);
    //----------------------------
    $this->SetY(15);

    $txt1 = 'RELACION DE MOVIMIENTO DE BIENES MUEBLES (BM-2)';

    $this->SetFont('Arial', 'B', $_SESSION['fuente_cabecera'] + 1);
    $this->Cell(0, $alto_cabecera, 'Fecha: ' . $_SESSION['fecha'], 0, 0, 'R');
    $this->Ln($alto_cabecera);

    $this->SetFont('Arial', 'B', $_SESSION['fuente_cabecera'] + 2);
    $alto_cabecera++;
    $this->Cell(0, $alto_cabecera, 'REPUBLICA BOLIVARIANA DE VENEZUELA', 0, 0, 'C');
    $this->Ln($alto_cabecera);
    $this->Cell(0, $alto_cabecera, 'CONTRALORIA DEL ESTADO BOLIVARIANO DE GUARICO', 0, 0, 'C');
    $this->Ln($alto_cabecera);
    $this->Cell(0, $alto_cabecera, utf8_decode('DIRECCIÓN DE BIENES, MATERIALES, SUMINISTROS Y ARCHIVO'), 0, 0, 'C');
    $this->Ln($alto_cabecera);
    $this->Cell(0, $alto_cabecera, $txt1, 0, 0, 'C');
    $this->Ln($alto_cabecera);
    $alto_cabecera--;
    $this->Ln($alto_cabecera);
    $alto_cabecera += $alto_cabecera;

    $this->SetFont('Arial', 'B', $_SESSION['fuente_cabecera']);
    $this->Cell(35, $alto_cabecera, 'DEPENDENCIA:', 1, 0, 'L');
    $this->Cell(0, $alto_cabecera, '' . $_SESSION['DIVISION_L'], 1, 0, 'L');
    $this->Ln($alto_cabecera);

    $this->Cell(35, $alto_cabecera, 'PERIODO:', 1, 0, 'L');
    $this->Cell(0, $alto_cabecera, '' . mayuscula($_SESSION['meses_anno'][intval(mes($_SESSION['fecha']))]) . ' - ' . anno(voltea_fecha($_SESSION['fecha'])), 1, 0, 'L');
    $this->Ln($alto_cabecera * 1.6);

    $this->SetFont('Arial', 'B', $_SESSION['fuente_cabecera']);

    global $a, $b, $c, $d, $e, $f, $g, $h;

    $a = 20; //cantidad 	
    $b = 13; //codigo
    $c = 20; //bien	
    $d = 95; //descripcion
    $e = 23; //conservacion
    $f = 27; //concepto
    //	$g=20 ; //valor
    $h = 0; //total

    $this->SetFont('Arial', 'B', $_SESSION['fuente_cabecera'] - 2);

    $x = $this->GetX();
    $y1 = $this->GetY();

    $this->cell($b * 4, 4, utf8_decode('CLASIFICACIÓN (CÓDIGO)'), 1, 0, 'C');

    $this->SetY($y1 + 4);
    $this->SetX($x);

    $this->cell($b, 8, 'GRUPO', 1, 0, 'C');

    $x = $this->GetX();
    $y = $this->GetY();
    $this->multicell($b, 4, 'SUB GRUPO', 1, 'C');
    $this->SetY($y);
    $this->SetX($x + $b);

    $this->cell($b, 8, 'SECCION', 1, 0, 'C');

    $x = $this->GetX();
    $y = $this->GetY();
    $this->multicell($b, 4, 'SUB SECCION', 1, 'C');
    $this->SetY($y1);
    $this->SetX($x + $b);

    //	$this->SetY($y1);

    $x = $this->GetX();
    $y = $this->GetY();
    $this->multicell($a, 6, 'CONCEPTO DEL MOVIMIENTO', 1, 'C');
    $this->SetY($y);
    $this->SetX($x + $a);

    $x = $this->GetX();
    $y = $this->GetY();
    $this->multicell($c, 6, utf8_decode('NÚMERO DE IDENTIFICACIÓN'), 1, 'C');
    $this->SetY($y);
    $this->SetX($x + $c);

    $this->cell(strtoupper($d), 12, 'NOMBRE Y DESCRIPCION DE LOS BIENES', 1, 0, 'C');

    $this->cell($f, 12, 'INCORPORACIONES BS', 1, 0, 'C');

    $this->cell($h, 12, 'DESINCORPORACIONES BS', 1, 0, 'C');

    $this->Ln(12);
  }

  function Footer()
  {
    //	include "../formatos/pie.php";
    //Posici�n a 1,5 cm del final
    $this->SetY(-14);
    //Arial it�lica 8
    $this->SetFont('Times', 'I', 9);
    //Color del texto en gris
    $this->SetTextColor(120);
    //N�mero de p�gina
    $this->Cell(100, 10, 'Impreso: ' . $_SESSION['CEDULA_USUARIO'] . ' ' . date('d/m/Y h:m'), 0, 0, 'L');
    $this->Cell(0, 10, 'SIACEBG ' . $this->PageNo() . ' de {nb}', 0, 0, 'R');
  }
}

// INICIO
$pdf = new PDF('L', 'mm', 'LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17, 30, 17);
$pdf->SetTitle('Comprobante de Reasignacion');
//-------------------

$_SESSION['i'] = 0;
$_SESSION['monto'] = 0;

//---------- DESTINO
if ($_SESSION['estatus'] == 0) {
  $consulta_div = "SELECT bn_reasignaciones_detalle.usuario, bn_reasignaciones_detalle.fecha, bn_dependencias.division,  bn_dependencias.id_direccion FROM bn_reasignaciones_detalle, bn_dependencias WHERE bn_reasignaciones_detalle.id_origen = " . $_SESSION['origen'] . " AND bn_reasignaciones_detalle.id_destino = " . $_SESSION['destino'] . " AND bn_reasignaciones_detalle.estatus = 0 AND bn_reasignaciones_detalle.id_destino = bn_dependencias.id GROUP BY division;";
} else {
  $consulta_div = "SELECT bn_reasignaciones.usuario, bn_reasignaciones.fecha, bn_dependencias.division,  bn_dependencias.id,  bn_dependencias.id_direccion FROM bn_reasignaciones_detalle, bn_dependencias, bn_reasignaciones WHERE bn_dependencias.id =  bn_reasignaciones.division_destino AND bn_reasignaciones_detalle.id_reasignacion=bn_reasignaciones.id AND  bn_reasignaciones.id = " . $_SESSION['id'] . " GROUP BY bn_reasignaciones.id;";
}
//		echo $consulta_div;
$tabla_div = $_SESSION['conexionsql']->query($consulta_div);
while ($registro_div = $tabla_div->fetch_object()) {
  $_SESSION['DIVISION_L'] = $registro_div->division;
  $_SESSION['fecha'] = voltea_fecha($registro_div->fecha);
  $_SESSION['monto'] = 0;
  $_SESSION['i'] = 0;
  //------------
  $pdf->AddPage();
  $linea = 1;
  $alto = 4;
  $i = 0;
  //----------
  if ($_SESSION['estatus'] == 0) {
    $consulta_x = "SELECT bn_bienes.*, bn_categorias.codigo, bn_reasignaciones_detalle.motivo FROM bn_categorias, bn_reasignaciones_detalle, bn_bienes WHERE bn_categorias.id_categoria=bn_bienes.id_categoria AND bn_bienes.id_bien = bn_reasignaciones_detalle.id_bien 	AND bn_reasignaciones_detalle.id_origen = " . $_SESSION['origen'] . " AND bn_reasignaciones_detalle.id_destino = " . $_SESSION['destino'] . " AND bn_reasignaciones_detalle.estatus = 0;";
  } else {
    $consulta_x = "SELECT bn_bienes.*, bn_categorias.codigo, bn_reasignaciones_detalle.motivo FROM bn_reasignaciones, bn_categorias, bn_reasignaciones_detalle, bn_bienes WHERE bn_reasignaciones_detalle.id_reasignacion=bn_reasignaciones.id AND bn_categorias.id_categoria=bn_bienes.id_categoria AND bn_bienes.id_bien = bn_reasignaciones_detalle.id_bien AND bn_reasignaciones.id = " . $_SESSION['id'] . ";";
  }
  $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
  //echo '<br> Cuerpo => '.$consulta_x;

  while ($registro_x = $tabla_x->fetch_object()) {
    $i++;
    //++++++++++++++++++++++++++
    if ($y1 > 165 or $y2 > 165) {
      $pdf->SetFont('Arial', 'B', $_SESSION['fuente_cabecera']);
      //				$pdf->Cell($b*4,4,'CANT. BIENES '.$_SESSION['i'],1,0,'C');
      //				$pdf->Cell($b*4+$a+$c,4,'',1,0,'L');
      $pdf->Cell(($b * 4 + $a + $c + $d), 4, 'VAN', 1, 0, 'C');
      $pdf->Cell(0, 4, 'SUBTOTAL ' . formato_moneda($_SESSION['monto']), 1, 0, 'R');
      //----------------------------------
      $pdf->AddPage();
      $y1 = $pdf->GetY();
      $pdf->SetFont('Arial', 'B', $_SESSION['fuente_cabecera']);
      //				$pdf->Cell($b*4,4,'CANT. BIENES '.$_SESSION['i'],1,0,'C');
      //				$pdf->Cell($a+$c,4,'',1,0,'L');
      $pdf->Cell($b * 4 + $a + $c + $d, 4, 'VIENEN', 1, 0, 'C');
      $pdf->Cell(0, 4, formato_moneda($_SESSION['monto']), 1, 0, 'R');
      $pdf->Ln(4);
    }
    //-------------------
    $pdf->SetFont('Times', '', 9);

    //----- PARA ARRANCAR CON LA LINEA
    $y1 = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->SetX($x + $a + $b + $b + $b + $b + $c);
    //-----------------------------------------MULTICELL
    $pdf->MultiCell($d, $alto, ucfirst(strtolower($registro_x->descripcion_bien)), $linea, 'J');
    $y2 = $pdf->GetY();
    //- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
    $pdf->SetY($y1);
    $pdf->SetX($x);
    $alto2 = $y2 - $y1;
    //---------------------------------------------------
    //	$pdf->Cell($a,($alto2),'01',$linea,0,'C');
    $pdf->Cell($b, ($alto2), $registro_x->grupo, $linea, 0, 'C');
    $pdf->Cell($b, ($alto2), $registro_x->subgrupo, $linea, 0, 'C');
    $pdf->Cell($b, ($alto2), $registro_x->seccion, $linea, 0, 'C');
    $pdf->Cell($b, ($alto2), $registro_x->subseccion, $linea, 0, 'C');
    $pdf->Cell($a, ($alto2), '02', $linea, 0, 'C');
    $pdf->Cell($c, ($alto2), $registro_x->numero_bien, $linea, 0, 'C');
    $pdf->SetX($x + $a + $b + $b + $b + $b + $c + $d);
    $pdf->Cell($f, ($alto2), formato_moneda($registro_x->valor), $linea, 0, 'R');
    //	$pdf->Cell($e,($alto2), voltea_fecha($registro_x->fecha_adquisicion),$linea,0,'C'); 
    $pdf->Cell($h, ($alto2), formato_moneda(0), $linea, 0, 'R');
    //--------------------
    $_SESSION['monto'] = $_SESSION['monto'] + ($registro_x->valor);

    //---------------------
    $pdf->Ln($alto2);
    $_SESSION['i']++;
  }

  while ($pdf->GetY() <= 170) {
    //----------- LINEA EN BLANCO
    $pdf->Cell($b, 4, '', 1, 0, 'L');
    $pdf->Cell($b, 4, '', 1, 0, 'L');
    $pdf->Cell($b, 4, '', 1, 0, 'L');
    $pdf->Cell($b, 4, '', 1, 0, 'L');
    $pdf->Cell($a, 4, '', 1, 0, 'L');
    $pdf->Cell($c, 4, '', 1, 0, 'C');
    $pdf->Cell($d, 4, '', 1, 0, 'C');
    $pdf->Cell($f, 4, '', 1, 0, 'R');
    $pdf->Cell($h, 4, '', 1, 0, 'R');
    $pdf->Ln(4);
    //----------------------
    $i++;
  }

  // TOTAL GENERAL
  $alto++;
  $alto++;
  $pdf->SetY(-41.8);
  $pdf->SetFont('Arial', 'B', $_SESSION['fuente_cabecera']);
  $pdf->Cell($a + $b + $b + $b + $b + $c + $d, $alto, 'TOTAL INCORPORACIONES', 1, 0, 'R');
  $pdf->Cell($f, $alto, formato_moneda($_SESSION['monto']), 1, 0, 'R');
  $pdf->Cell($h, $alto, formato_moneda(0), 1, 0, 'R');
  //----------------------------------
  //----------
}

//---------- ORIGEN
$_SESSION['i'] = 0;
$_SESSION['monto'] = 0;

//---------- DESTINO
if ($_SESSION['estatus'] == 0) {
  $consulta_div = "SELECT bn_reasignaciones_detalle.usuario, bn_reasignaciones_detalle.fecha, bn_dependencias.division,  bn_dependencias.id_direccion FROM bn_reasignaciones_detalle, bn_dependencias WHERE bn_reasignaciones_detalle.id_origen = " . $_SESSION['origen'] . " AND bn_reasignaciones_detalle.id_destino = " . $_SESSION['destino'] . " AND bn_reasignaciones_detalle.estatus = 0 AND bn_reasignaciones_detalle.id_origen = bn_dependencias.id GROUP BY division;";
} else {
  $consulta_div = "SELECT bn_reasignaciones.usuario, bn_reasignaciones.fecha, bn_dependencias.division,  bn_dependencias.id,  bn_dependencias.id_direccion FROM bn_reasignaciones_detalle, bn_dependencias, bn_reasignaciones WHERE bn_dependencias.id =  bn_reasignaciones.division_actual AND bn_reasignaciones_detalle.id_reasignacion=bn_reasignaciones.id AND  bn_reasignaciones.id = " . $_SESSION['id'] . " GROUP BY bn_reasignaciones.id;";
}
//		echo $consulta_div;
$tabla_div = $_SESSION['conexionsql']->query($consulta_div);
while ($registro_div = $tabla_div->fetch_object()) {
  $_SESSION['DIVISION_L'] = $registro_div->division;
  $_SESSION['fecha'] = voltea_fecha($registro_div->fecha);
  $_SESSION['monto'] = 0;
  $_SESSION['i'] = 0;
  //------------
  $pdf->AddPage();
  $linea = 1;
  $alto = 4;
  $i = 0;
  //----------
  if ($_SESSION['estatus'] == 0) {
    $consulta_x = "SELECT bn_bienes.*, bn_categorias.codigo, bn_reasignaciones_detalle.motivo FROM bn_categorias, bn_reasignaciones_detalle, bn_bienes WHERE bn_categorias.id_categoria=bn_bienes.id_categoria AND bn_bienes.id_bien = bn_reasignaciones_detalle.id_bien 	AND bn_reasignaciones_detalle.id_origen = " . $_SESSION['origen'] . " AND bn_reasignaciones_detalle.id_destino = " . $_SESSION['destino'] . " AND bn_reasignaciones_detalle.estatus = 0;";
  } else {
    $consulta_x = "SELECT bn_bienes.*, bn_categorias.codigo, bn_reasignaciones_detalle.motivo FROM bn_reasignaciones, bn_categorias, bn_reasignaciones_detalle, bn_bienes WHERE bn_reasignaciones_detalle.id_reasignacion=bn_reasignaciones.id AND bn_categorias.id_categoria=bn_bienes.id_categoria AND bn_bienes.id_bien = bn_reasignaciones_detalle.id_bien AND bn_reasignaciones.id = " . $_SESSION['id'] . ";";
  }
  $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
  //echo '<br> Cuerpo => '.$consulta_x;

  while ($registro_x = $tabla_x->fetch_object()) {
    $i++;
    //++++++++++++++++++++++++++
    if ($y1 > 165 or $y2 > 165) {
      $pdf->SetFont('Arial', 'B', $_SESSION['fuente_cabecera']);
      //				$pdf->Cell($b*4,4,'CANT. BIENES '.$_SESSION['i'],1,0,'C');
      //				$pdf->Cell($b*4+$a+$c,4,'',1,0,'L');
      $pdf->Cell(($b * 4 + $a + $c + $d), 4, 'VAN', 1, 0, 'C');
      $pdf->Cell(0, 4, 'SUBTOTAL ' . formato_moneda($_SESSION['monto']), 1, 0, 'R');
      //----------------------------------
      $pdf->AddPage();
      $y1 = $pdf->GetY();
      $pdf->SetFont('Arial', 'B', $_SESSION['fuente_cabecera']);
      //				$pdf->Cell($b*4,4,'CANT. BIENES '.$_SESSION['i'],1,0,'C');
      //				$pdf->Cell($a+$c,4,'',1,0,'L');
      $pdf->Cell($b * 4 + $a + $c + $d, 4, 'VIENEN', 1, 0, 'C');
      $pdf->Cell(0, 4, formato_moneda($_SESSION['monto']), 1, 0, 'R');
      $pdf->Ln(4);
    }
    //-------------------
    $pdf->SetFont('Times', '', 9);

    //----- PARA ARRANCAR CON LA LINEA
    $y1 = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->SetX($x + $a + $b + $b + $b + $b + $c);
    //-----------------------------------------MULTICELL
    $pdf->MultiCell($d, $alto, ucfirst(strtolower($registro_x->descripcion_bien)), $linea, 'J');
    $y2 = $pdf->GetY();
    //- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
    $pdf->SetY($y1);
    $pdf->SetX($x);
    $alto2 = $y2 - $y1;
    //---------------------------------------------------
    //	$pdf->Cell($a,($alto2),'01',$linea,0,'C');
    $pdf->Cell($b, ($alto2), $registro_x->grupo, $linea, 0, 'C');
    $pdf->Cell($b, ($alto2), $registro_x->subgrupo, $linea, 0, 'C');
    $pdf->Cell($b, ($alto2), $registro_x->seccion, $linea, 0, 'C');
    $pdf->Cell($b, ($alto2), $registro_x->subseccion, $linea, 0, 'C');
    $pdf->Cell($a, ($alto2), $registro_x->motivo, $linea, 0, 'C');
    $pdf->Cell($c, ($alto2), $registro_x->numero_bien, $linea, 0, 'C');
    $pdf->SetX($x + $a + $b + $b + $b + $b + $c + $d);
    $pdf->Cell($f, ($alto2), '0,00', $linea, 0, 'R');
    //	$pdf->Cell($e,($alto2), voltea_fecha($registro_x->fecha_adquisicion),$linea,0,'C'); 
    $pdf->Cell($h, ($alto2), formato_moneda($registro_x->valor), $linea, 0, 'R');
    //--------------------
    $_SESSION['monto'] = $_SESSION['monto'] + ($registro_x->valor);

    //---------------------
    $pdf->Ln($alto2);
    $_SESSION['i']++;
  }

  while ($pdf->GetY() <= 170) {
    //----------- LINEA EN BLANCO
    $pdf->Cell($b, 4, '', 1, 0, 'L');
    $pdf->Cell($b, 4, '', 1, 0, 'L');
    $pdf->Cell($b, 4, '', 1, 0, 'L');
    $pdf->Cell($b, 4, '', 1, 0, 'L');
    $pdf->Cell($a, 4, '', 1, 0, 'L');
    $pdf->Cell($c, 4, '', 1, 0, 'C');
    $pdf->Cell($d, 4, '', 1, 0, 'C');
    $pdf->Cell($f, 4, '', 1, 0, 'R');
    $pdf->Cell($h, 4, '', 1, 0, 'R');
    $pdf->Ln(4);
    //----------------------
    $i++;
  }

  // TOTAL GENERAL
  $alto++;
  $alto++;
  $pdf->SetY(-41.8);
  $pdf->SetFont('Arial', 'B', $_SESSION['fuente_cabecera']);
  $pdf->Cell($a + $b + $b + $b + $b + $c + $d, $alto, 'TOTAL DESINCORPORACIONES', 1, 0, 'R');
  $pdf->Cell($f, $alto, formato_moneda(0), 1, 0, 'R');
  $pdf->Cell($h, $alto, formato_moneda($_SESSION['monto']), 1, 0, 'R');
  //----------------------------------
  //----------
}

$pdf->Output();
