<?php

$alto_cabecera = 4;
$a=35 ; 	
$b=100 ; 
$c=80 ;

if ($_SESSION['tipo']==21 or $_SESSION['tipo']==121)
	{
	$jefe = jefe_direccion($_SESSION['id_dependencia']);
	}
elseif ($_SESSION['tipo']==31 or $_SESSION['tipo']==131)
	{
	$jefe = jefe_direccion($_SESSION['id_dependencia']);
	}
else
	{
	$jefe = jefe_direccion($_SESSION['id_direccion']); 
	//echo $_SESSION['id_direccion'];
	}
//----------------------------
$this->SetY(-37.8);

$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$this->Cell(0,$alto_cabecera,'Responsable Patrimonial Primario',1,0,'L');
$this->Ln($alto_cabecera);

$this->Cell($a,$alto_cabecera,'Cédula de Identidad',1,0,'L');
$this->Cell($b,$alto_cabecera,'Apellidos y Nombres',1,0,'L');
$this->Cell($c,$alto_cabecera,'Cargo',1,0,'L');	

$y=$this->GetY();
$this->Cell(0,$alto_cabecera*3+2+2,'Firma y Sello',1,0,'L');	
$this->SetY($y);

$this->Ln($alto_cabecera);

if ($_SESSION['nombres']=='si'){
	$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
	$this->Cell($a,$alto_cabecera+2,'V-'.formato_cedula($jefe[0]),1,0,'L');
	$this->Cell($b,$alto_cabecera+2,$jefe[1],1,0,'L');
	$this->Cell($c,$alto_cabecera+2,$jefe[2],1,0,'L');	} 
else {$_SESSION['nombres']='si';}

$this->Ln($alto_cabecera+2);

if ($_SESSION['tipo']==21 or $_SESSION['tipo']==121 or $_SESSION['tipo']==31 or $_SESSION['tipo']==131)
	{
	$empleado = empleado($_SESSION['usuariob']);
	//-----------------------------
	$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']); 
	$this->Cell($a+$b+$c,$alto_cabecera+2,'Preparado por:         '.$empleado[1].'         C.I. V-'.formato_cedula($empleado[0]).'                 Firma:',1,0,'L');
	}
else
	{
	//----------------------------
//	$consulta_x = "SELECT * FROM rac WHERE id_area = '6' LIMIT 1;"; //AND jefe_area = '1' 
//	$consulta_x = "SELECT * FROM usuarios WHERE acceso = '12' LIMIT 1;"; //AND jefe_area = '1' 
//	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//	$registro_x = $tabla_x->fetch_object();
	$jefe = jefe_direccion(12);
	//----------------------------
	
	$this->SetFont('Arial','B',$_SESSION['fuente_cabecera']-1); 
	$this->Cell($a+$b+$c,$alto_cabecera+2,'Preparado por:         '.$jefe[1].'         C.I. V-'.formato_cedula($jefe[0]).'         '.$jefe[2],1,0,'L');
	}
?>