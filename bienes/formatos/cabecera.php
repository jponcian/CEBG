<?php

$_SESSION['fuente_cabecera'] = 8;
$alto_cabecera = 3.5;

global $color;

$c=30 ; //cedula	
$d=90 ; //nombres

//------------------------
//$this->SetFillColor(170,166,166);
$this->Image('../../images/logo_nuevo.jpg',30,15,25);
//$this->Image('../../images/cuadro_lleno.jpg',92,25,3);
//$this->Image('../../images/cuadro_vacio.jpg',152,25,3);
//----------------------------
$this->SetY(20);

//----- POR SI SON REASIGNACIONES
if ($_SESSION['tipo']==21 or $_SESSION['tipo']==31 or $_SESSION['tipo']==121 or $_SESSION['tipo']==131)
	{
	$txt1 = 'RELACION DE MOVIMIENTO DE BIENES MUEBLES (BM-2)';
	}
else
	{
	$txt1 = 'INVENTARIO DE BIENES MUEBLES (BM-1)';
	}

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']+1);
$this->Cell(0,$alto_cabecera,'Fecha: '.$_SESSION['fecha'],0,0,'R');
$this->Ln($alto_cabecera);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']+2);
$alto_cabecera ++;
$this->Cell(0,$alto_cabecera,'REPUBLICA BOLIVARIANA DE VENEZUELA',0,0,'C');		
$this->Ln($alto_cabecera);
$this->Cell(0,$alto_cabecera,'CONTRALORIA DEL ESTADO BOLIVARIANO DE GUARICO',0,0,'C');		
$this->Ln($alto_cabecera);
$this->Cell(0,$alto_cabecera,'DIRECCIÓN DE BIENES, MATERIALES, SUMINISTROS Y ARCHIVO',0,0,'C');		
$this->Ln($alto_cabecera);
$this->Cell(0,$alto_cabecera,$txt1,0,0,'C');		
$this->Ln($alto_cabecera);
$alto_cabecera --;
$this->Ln($alto_cabecera);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell(0,$alto_cabecera,'DEPENDENCIA:',1,0,'L');
$this->Ln($alto_cabecera);

$x=$this->GetX();
$y=$this->GetY();

$this->SetFont('Arial','',$_SESSION['fuente_cabecera']);
$this->Ln($alto_cabecera/2);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);

//----- POR SI SON REASIGNACIONES
if ($_SESSION['tipo']==21 or $_SESSION['tipo']==31)
	{
	$this->Cell(0,$alto_cabecera,''.$_SESSION['DIVISION_L'],0,0,'C');
	}
elseif ($_SESSION['tipo']==121 or $_SESSION['tipo']==131)
	{
	$this->Cell(0,$alto_cabecera,''.$_SESSION['DIVISION_L'],0,0,'C');
	}
elseif ($_SESSION['id_dependencia']<>'ULTIMA' and $_SESSION['id_dependencia']<>'FINAL')
	{
	$this->Cell(0,$alto_cabecera,''.$_SESSION['DIVISION_L'],0,0,'C');
	//-----------------
	}
else
	{
	$this->Cell(0,$alto_cabecera,'CONTRALORIA DEL ESTADO BOLIVARIANO DE GUARICO',0,0,'C');
	//-----------------
	}

$this->SetY($y);
$this->SetX($x);

$this->Cell(0,$alto_cabecera*2,'',1,0,'L');	
$this->Ln($alto_cabecera*2);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell(0,$alto_cabecera,'RESPONSABLE DEL ALMACEN',1,0,'L');
$this->Ln($alto_cabecera);

$x=$this->GetX();
$y=$this->GetY();

$this->SetFont('Arial','',$_SESSION['fuente_cabecera']);
$this->Cell($c,$alto_cabecera,'C.I.',0,0,'L');
$this->Cell($d,$alto_cabecera,'Apellidos y Nombres',0,0,'L');
$this->Cell(0,$alto_cabecera,'Cargo',0,0,'L');	
$this->Ln($alto_cabecera);

//	$consulta_x = "SELECT * FROM usuarios WHERE acceso=66 LIMIT 1;"; //acceso = '12' AND  AND jefe_area = '1' 
//	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//	$registro_x = $tabla_x->fetch_object();
	//----------------------------
$jefe = jefe_direccion(12);
//----------------------------

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell($c,$alto_cabecera,"V-".formato_cedula($jefe[0]),0,0,'L');
$this->Cell($d,$alto_cabecera,$jefe[1],0,0,'L');
$this->Cell(0,$alto_cabecera,$jefe[2],0,0,'L');	

$this->SetY($y);
$this->SetX($x);

$this->Cell($c,$alto_cabecera*2,'',1,0,'L');
$this->Cell($d,$alto_cabecera*2,'',1,0,'L');
$this->Cell(0,$alto_cabecera*2,'',1,0,'L');	
$this->Ln($alto_cabecera*2);

?>