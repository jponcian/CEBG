<?php

$_SESSION['fuente_cabecera'] = 8;
$alto_cabecera = 3.5;

global $color;

$a=20 ; //codigo 	
$b=80 ; //denominacion
$c=20 ; //cedula	
$d=80 ; //nombres

//------------------------
//$this->SetFillColor(170,166,166);
$this->Image('../../images/logo_nuevo.jpg',30,5,25);
$this->Image('../../images/cuadro_lleno.jpg',152,25,3);
$this->Image('../../images/cuadro_vacio.jpg',92,25,3);
//----------------------------
$this->SetY(20);

//---------------------------
$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell(45,$alto_cabecera,'',0,0,'C');
$this->Cell(160,$alto_cabecera,'COMPROBANTE DE '.$comprobante,0,0,'C');
$this->Cell(0,$alto_cabecera,'N° '.$this->PageNo().' de {nb}',0,0,'C');	
$this->Ln(4);

//---------------------------
$this->SetFont('Arial','',$_SESSION['fuente_cabecera']);
$this->Cell(65,$alto_cabecera,'',0,0,'C');
$this->Cell(60,$alto_cabecera,'BIENES MUEBLES',1,0,'C');
$this->Cell(60,$alto_cabecera,'MATERIALES',1,0,'C');
$this->Cell(20,$alto_cabecera,'',0,0,'C');

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell(0,$alto_cabecera,'Fecha: '.date('d/m/Y'),0,0,'C');
//$this->Cell(0,$alto_cabecera,('Fecha: 31/12/2021'),0,0,'C');

$this->Ln(8);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell(0,$alto_cabecera,'ORGANISMO',1,0,'L');
$this->Ln($alto_cabecera);

$x=$this->GetX();
$y=$this->GetY();

$this->SetFont('Arial','',$_SESSION['fuente_cabecera']);
$this->Cell($a,$alto_cabecera,'Código',0,0,'L');
$this->Cell(0,$alto_cabecera,'Denominación',0,0,'L');	
$this->Ln($alto_cabecera);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell($a,$alto_cabecera,'0',0,0,'R');
$this->Cell(0,$alto_cabecera,'MINISTERIO PUBLICO',0,0,'C');		

$this->SetY($y);
$this->SetX($x);

$this->Cell($a,$alto_cabecera*2,'',1,0,'L');
$this->Cell(0,$alto_cabecera*2,'',1,0,'L');	
$this->Ln($alto_cabecera*2);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell(0,$alto_cabecera,'UNIDAD ADMINISTRADORA',1,0,'L');
$this->Ln($alto_cabecera);

$x=$this->GetX();
$y=$this->GetY();

$this->SetFont('Arial','',$_SESSION['fuente_cabecera']);
$this->Cell($a,$alto_cabecera,'Código',0,0,'L');
$this->Cell(0,$alto_cabecera,'Denominación',0,0,'L');	
$this->Ln($alto_cabecera);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell($a,$alto_cabecera,'0',0,0,'R');
$this->Cell(0,$alto_cabecera,'CONTRALORÍA GENERAL DE LA REPUBLICA BOLIVARIANA DE VENEZUELA',0,0,'C');		

$this->SetY($y);
$this->SetX($x);

$this->Cell($a,$alto_cabecera*2,'',1,0,'L');
$this->Cell(0,$alto_cabecera*2,'',1,0,'L');	
$this->Ln($alto_cabecera*2);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell(0,$alto_cabecera,'DEPENDENCIA USUARIA',1,0,'L');
$this->Ln($alto_cabecera);

$x=$this->GetX();
$y=$this->GetY();

$this->SetFont('Arial','',$_SESSION['fuente_cabecera']);
$this->Cell($a,$alto_cabecera,'Código',0,0,'L');
$this->Cell(0,$alto_cabecera,'Denominación',0,0,'L');	
$this->Ln($alto_cabecera);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell($a,$alto_cabecera,'0',0,0,'R');

$this->Cell(0,$alto_cabecera,'CONTRALORIA DEL ESTADO BOLIVARIANO DE GUARICO - '.$_SESSION['DIVISION_L'],0,0,'C');

$this->SetY($y);
$this->SetX($x);

$this->Cell($a,$alto_cabecera*2,'',1,0,'L');
$this->Cell(0,$alto_cabecera*2,'',1,0,'L');	
$this->Ln($alto_cabecera*2);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell(0,$alto_cabecera,'RESPONSABLE DEL ALMACEN',1,0,'L');
$this->Ln($alto_cabecera);

$x=$this->GetX();
$y=$this->GetY();

$this->SetFont('Arial','',$_SESSION['fuente_cabecera']);
$this->Cell($a,$alto_cabecera,'Código',0,0,'L');
$this->Cell($b,$alto_cabecera,'Denominación',0,0,'L');
$this->Cell($c,$alto_cabecera,'C.I.',0,0,'L');
$this->Cell($d,$alto_cabecera,'Apellidos y Nombres',0,0,'L');
$this->Cell(0,$alto_cabecera,'Cargo',0,0,'L');	
$this->Ln($alto_cabecera);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell($a,$alto_cabecera,'0',0,0,'R');
$this->Cell($b,$alto_cabecera,'',0,0,'L');
$this->Cell($c,$alto_cabecera,'',0,0,'L');
$this->Cell($d,$alto_cabecera,'',0,0,'L');
$this->Cell(0,$alto_cabecera,'',0,0,'L');	

$this->SetY($y);
$this->SetX($x);

$this->Cell($a,$alto_cabecera*2,'',1,0,'L');
$this->Cell($b,$alto_cabecera*2,'',1,0,'L');
$this->Cell($c,$alto_cabecera*2,'',1,0,'L');
$this->Cell($d,$alto_cabecera*2,'',1,0,'L');
$this->Cell(0,$alto_cabecera*2,'',1,0,'L');	
$this->Ln($alto_cabecera*2);

?>