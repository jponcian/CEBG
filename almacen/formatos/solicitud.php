<?php
session_start();
ob_end_clean();
session_start();

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}

include "../../conexion.php";
include('../../funciones/auxiliar_php.php');
require('../../lib/fpdf/fpdf.php');

$_SESSION['origen'] = decriptar($_GET['origen']);
$_SESSION['estatus'] = ($_GET['estatus']);
$_SESSION['id'] = decriptar($_GET['id']);

class PDF extends FPDF
{
function Header()
	{
	if ($_SESSION['estatus']>0)
		{
		//------ DATOS DE LA SOLICITUD
		$consulta = "SELECT bn_solicitudes.numero, bn_solicitudes.division as id_direccion, fecha, estatus, a_direcciones.direccion, rac.cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, rac.cargo FROM  rac, a_direcciones, bn_solicitudes WHERE bn_solicitudes.id = ".$_SESSION['id']." AND a_direcciones.id = bn_solicitudes.division AND a_direcciones.cedula = rac.cedula AND estatus>=3 GROUP BY bn_solicitudes.division ORDER BY estatus, fecha DESC;";
		}
	else
		{
		//------ DATOS DE LA SOLICITUD
		$consulta = "SELECT 'Preliminar' as numero, bn_solicitudes_detalle.fecha, a_direcciones.direccion, rac.cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, rac.cargo FROM bn_solicitudes_detalle,	a_direcciones, rac WHERE bn_solicitudes_detalle.division = a_direcciones.id AND a_direcciones.cedula = rac.cedula  AND bn_solicitudes_detalle.division = ".$_SESSION['origen']." AND estatus = 0;"; 
		}
//	echo $consulta;
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$registro = $tabla->fetch_object();
	//-----------------------
	global $cargo;
	$cargo = $registro->cargo;
	$_SESSION['estatus'] = $registro->estatus;
	
	$x=$this->GetX();
	$y=$this->GetY();
	$this->Image('../../images/logo_nuevo.jpg',25,21,23);
	//----------------------------
	
	$this->SetFont('Arial','B',14);
	//----------------------------
	
	$this->Cell(40,21,'',1,'C');
	//----------------------------
	
	$this->SetY($y);
	$this->SetX($x+40);
	$this->MultiCell(75,10.5,'SOLICITUD DE MATERIALES AL ALMACEN',1,'C');
	//----------------------------
	
	$this->SetFont('Arial','B',12);
	//----------------------------
	$this->SetY($y);
	$this->SetX($x+115);
	$this->Cell(35,21,($registro->numero),1,0,'C');
	$this->SetFont('Arial','B',10);
	//----------------------------
	$this->Cell(0,21,voltea_fecha($registro->fecha),1,0,'C');
	$this->SetFont('Arial','B',8);
	$this->SetY($y);
	$this->SetX($x+115);
	$this->Cell(35,7,'N° CONSECUTIVO',0,0,'C');
	$this->Cell(0,7,'FECHA EMISIÓN',0,0,'C');
	$this->ln(22);
	//----------------------------
	
	$this->SetFont('Arial','',8);
	$y=$this->GetY();
	//----------------------------
	$area = $registro->direccion;
	$this->Cell(110,14,$area,1,0,'C');
	$this->Cell(0,14,$registro->nombre,1,0,'C');
	//----------------------------
	$this->SetY($y);
	//----------------------------
	$this->SetFont('Arial','B',8);
	$this->Cell(110,7,'Unidad Solicitante:');
	$this->Cell(0,7,'Jefe o Responsable:');
	
	$this->ln(15);
	//----------------------------
	
	$this->SetFont('Arial','B',8);
	$y=$this->GetY();
	//----------------------------
	$this->Cell($a=12,14,'ITEM',1,0,'C');
	//$this->Cell($b=18,14,'CODIGO',1,0,'C');
	$this->Cell($c=76+18,14,'DESCRIPCION',1,0,'C');
	$this->Cell($d=12,14,'U.M.',1,0,'C');
	$y=$this->GetY();
	$x=$this->GetX();
	$this->MultiCell($e=20,7,'CANTIDAD SOLICITADA',1,'C');
	$this->SetY($y);
	$this->SetX($x+$e);
	$this->Cell($f=0,7,'PARA USO DEL ALMACEN',1,0,'C');
	$this->ln(7);
	$this->SetX($x+$e);
	$this->Cell($g=20,7,'APROBADA',1,0,'C');
	$this->Cell(0,7,'DESPACHADA',1,0,'C');
	//----------------------------
	$this->ln(7);
	}	
	
function Footer()
	{   
	//Posición a 1,5 cm del final
	$this->SetY(-14);
	//Arial itálica 8
	$this->SetFont('Times','I',9);
	//Color del texto en gris
	$this->SetTextColor(120);
	//Número de página
	$this->Cell(460,10,' '.$this->PageNo().' de {nb}',0,0,'C');
	}		
}

// INICIO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,20,17);
$pdf->AddPage();
$pdf->SetTitle('Solicitud de Materiales');
//----------------------------

$i=0;
$pdf->SetFont('Arial','',7.5);
//----------------------------
if ($_SESSION['estatus']>0)
	{
	//------ DATOS DE LA SOLICITUD
	$consultax = "SELECT * FROM bn_solicitudes_detalle, bn_materiales WHERE bn_materiales.id_bien = bn_solicitudes_detalle.id_bien AND id_solicitud=".$_SESSION['id'].";"; 
	}
else
	{
	//------ DATOS DE LA SOLICITUD
	$consultax = "SELECT * FROM bn_solicitudes_detalle, bn_materiales WHERE bn_materiales.id_bien = bn_solicitudes_detalle.id_bien AND division=$origen".$_SESSION['origen']." AND estatus=0;"; 
	}//echo $consultax;
//---------------
$tablax = $_SESSION['conexionsql']->query($consultax);
while ($registrox = $tablax->fetch_object())
	{
	$i++;	
	$alto = 5.5;
	//----- PARA ARRANCAR CON LA LINEA
	$y1=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->SetX($x+12);
	//-----------------------------------------MULTICELL
	$pdf->MultiCell($c=76+18,$alto, ucfirst(strtolower($registrox->descripcion_bien)),1,'J');
	$y2=$pdf->GetY();
	//- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
	$pdf->SetY($y1);
	$pdf->SetX($x);
	$alto2 = $y2 - $y1;
	//----------------------------
	$pdf->Cell($a=12,$alto2,$i,1,0,'C');
	//$pdf->Cell($b=18,$alto,$registrox->codigo,1,0,'C');
	$pdf->SetX($x+$a+$c);
	$pdf->Cell($d=12,$alto2,$registrox->unidad,1,0,'C');
	$pdf->Cell($e=20,$alto2,$registrox->cantidad,1,0,'C');
	if ($_SESSION['estatus']>0)	
		{	$pdf->Cell($f=20,$alto2,$registrox->cant_aprobada,1,0,'C');
			$pdf->Cell($g=0,$alto2,$registrox->cant_aprobada,1,0,'C');}
	else
		{	$pdf->Cell($f=20,$alto2,'',1,0,'C');
			$pdf->Cell($g=0,$alto2,'',1,0,'C');}
	//----------------------------
	$pdf->ln($alto2);
	}

while ($pdf->GetY() < 221)
	{
	$i++;	
	//----------------------------
	$pdf->Cell($a,7,$i,1,0,'C');
	//$pdf->Cell($b,7,'',1,0,'C');
	$pdf->Cell($c,7,'',1,0,'L');
	$pdf->Cell($d,7,'',1,0,'C');
	$pdf->Cell($e,7,'',1,0,'C');
	$pdf->Cell($f,7,'',1,0,'C');
	$pdf->Cell($g,7,'',1,0,'C');
	//----------------------------
	$pdf->ln(7);
	}

$pdf->ln(1);
$y=$pdf->GetY();
$pdf->SetFont('Arial','B',8);
//----------------------------
$pdf->Cell($a=50,7,'',0,0,'L');
$pdf->Cell($a,7,'',0,0,'L');
$x=$pdf->GetX();
$pdf->Cell(45,4,'Por el Almacén:',1,0,'C');
$pdf->Cell(0,4,'Solicitante:',1,0,'L');
//----------------------------

//$pdf->SetY($y);
$pdf->ln();
$pdf->SetX($x);
//----------------------------
$pdf->Cell(45,7,'Firma:',0,0,'L');
$pdf->Cell(0,7,'Firma y Fecha:',0,0,'L');

$pdf->SetFont('Arial','',7.5);
$pdf->SetY($y);
//---- PARA LOS BORDES
$pdf->Cell($a,28,'',0,0,'C');
$pdf->Cell($a,28,'',0,0,'C');
$pdf->SetY($y+4);
$pdf->SetX($x);
$pdf->Cell(45,24,'',1,0,'C');
$pdf->Cell(0,24,'',1,0,'C');

//---- PARA LOS CARGOS
$pdf->SetY($y+22);
$pdf->Cell($a*2,6,'',0,0,'C');
//----------------------------
$pdf->Cell(45,6,'JEFE DE ALMACEN',0,1,'C');
//----------------------------
if ($_SESSION['estatus']==6)	
	{
	$pdf->SetY(140);
	$pdf->SetTextColor(255,0,0);
	$pdf->SetFont('helvetica','',35);
	$pdf->Cell(0,5,'NO APROBADA',0,0,'C'); 
	$pdf->SetFont('Times','',10);
	$pdf->SetTextColor(0);
	}
$pdf->Output();
?>