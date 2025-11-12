<?php
//------------ CUANDO ES POR REASIGNACIÓN
if ($_SESSION['tipo'] == 21 or $_SESSION['tipo'] == 31 or $_SESSION['tipo'] == 121 or $_SESSION['tipo'] == 131)
{
global $a, $b, $c, $d, $e, $f, $g, $h;

$a=15 ; //cantidad 	
$b=13 ; //codigo
$c=20 ; //bien	
$d=100 ; //descripcion
$e=23 ; //conservacion
$f=17 ; //concepto
$g=20 ; //valor
$h=0 ; //total

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']-0.5);

$x=$this->GetX();
$y1=$this->GetY();

$this->cell($a+$b*4,4,'CLASIFICACION (CÓDIGO)',1,0,'C');

$this->SetY($y1+4);
$this->SetX($x);

$this->cell($a,12,'Cantidad',1,0,'C');

$this->cell($b,12,'Grupo',1,0,'C');
	
$x=$this->GetX();
$y=$this->GetY();
$this->multicell($b,6,'Sub Grupo',1,'C');
$this->SetY($y);
$this->SetX($x+$b);

$this->cell($b,12,'Seccion',1,0,'C');

$x=$this->GetX();
$y=$this->GetY();
$this->multicell($b,6,'Sub Seccion',1,'C');
$this->SetY($y1);
$this->SetX($x+$b);

$x=$this->GetX();
$y=$this->GetY();
$this->multicell($c,6,'Número de Identificación',1,'C');
$this->SetY($y);
$this->SetX($x+$c);

$this->cell(strtoupper($d),12,'DESCRIPCION',1,0,'C');

$this->cell($f,12,'Concepto',1,0,'C');

$x=$this->GetX();
$y=$this->GetY();
$this->multicell($e,6,'Fecha de Incorporación',1,'C');
$this->SetY($y);
$this->SetX($x+$e);

$this->cell($h,12,'Valor Total',1,0,'C');

$this->Ln(12);

//----------- VIENEN
//if ($this->PageNo()>1)
//	{
//	$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
//	$this->Cell($a,4,$_SESSION['i'],1,0,'C');
//	$this->Cell($b,4,'',1,0,'L');
//	$this->Cell($c,4,'',1,0,'L');
//	$this->Cell(strtoupper($d),4,'VIENEN',1,0,'C');
//	$this->Cell($e,4,'',1,0,'L');	
//	$this->Cell($f,4,'',1,0,'L');	
//	$this->Cell($g,4,'',1,0,'L');	
//	$this->Cell($h,4,formato_moneda($_SESSION['monto']),1,0,'R');	
//	$this->Ln(4);
//	}
}
else
{
global $a, $b, $c, $d, $e, $f;

$a=15 ; //cantidad 	
$b=13 ; //codigo
$c=20 ; //bien	
$d=110 ; //descripcion
$e=23 ; //conservacion
$f=0 ; //valor

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']-0.5);

$x=$this->GetX();
$y1=$this->GetY();

$this->cell($a+$b*4,4,'CLASIFICACION (CÓDIGO)',1,0,'C');

$this->SetY($y1+4);
$this->SetX($x);

$this->cell($a,8,'Cantidad',1,0,'C');

$this->cell($b,8,'Grupo',1,0,'C');
	
$x=$this->GetX();
$y=$this->GetY();
$this->multicell($b,4,'Sub Grupo',1,'C');
$this->SetY($y);
$this->SetX($x+$b);

$this->cell($b,8,'Seccion',1,0,'C');

$x=$this->GetX();
$y=$this->GetY();
$this->multicell($b,4,'Sub Seccion',1,'C');
$this->SetY($y1);
$this->SetX($x+$b);

$x=$this->GetX();
$y=$this->GetY();
$this->multicell($c,6,'Número de Identificación',1,'C');
$this->SetY($y);
$this->SetX($x+$c);

$this->cell(strtoupper($d),12,'DESCRIPCION',1,0,'C');

$x=$this->GetX();
$y=$this->GetY();
$this->multicell($e,6,'Fecha de Incorporación',1,'C');
$this->SetY($y);
$this->SetX($x+$e);

$x=$this->GetX();
$y=$this->GetY();
$this->multicell($f,12,'Valor Unitario',1,'C');
}
?>