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

$_SESSION['estatus'] = ($_GET['estatus']);
$_SESSION['id'] = decriptar($_GET['id']);

class PDF extends FPDF
{
function Header()
	{
	if ($_SESSION['estatus']>0)
		{
		//------ DATOS DE LA SOLICITUD
		$consulta = "SELECT bn_ingresos.numero, bn_ingresos.division as id_direccion, fecha, estatus, a_direcciones.direccion, rac.cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, rac.cargo FROM  rac, a_direcciones, bn_ingresos WHERE bn_ingresos.id = ".$_SESSION['id']." AND a_direcciones.id = bn_ingresos.division AND a_direcciones.cedula = rac.cedula AND estatus>=3 GROUP BY bn_ingresos.division ORDER BY estatus, fecha DESC;";
		}
	else
		{
		$consulta = "SELECT 'Preliminar' as numero, bn_ingresos_detalle.division as id_direccion, fecha, estatus, a_direcciones.direccion, rac.cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, rac.cargo FROM  rac, a_direcciones, bn_ingresos_detalle WHERE bn_ingresos_detalle.id_ingreso= 0 AND a_direcciones.id = bn_ingresos_detalle.division AND a_direcciones.cedula = rac.cedula AND estatus=0 GROUP BY bn_ingresos_detalle.division ORDER BY estatus, fecha DESC;";
//		echo $consulta;
		}
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$registro = $tabla->fetch_object();
	//-----------------------
	global $cargo;
	$cargo = $registro->cargo;
	
	$x=$this->GetX();
	$y=$this->GetY();
	$this->Image('../../images/logo_nuevo.jpg',25,21,20);
	//----------------------------
	
	$this->SetFont('Arial','B',14);
	//----------------------------
	
	$this->Cell(40,21,'',1,'C');
	//----------------------------
	
	$this->SetY($y);
	$this->SetX($x+40);
	$this->MultiCell(75,10.5,'INGRESO DE MATERIALES AL ALMACEN',1,'C');
	//----------------------------
	
	$this->SetFont('Arial','B',12);
	//----------------------------
	$this->SetY($y);
	$this->SetX($x+115);
	$this->Cell(35,21,rellena_cero($registro->numero,3),1,0,'C');
	$this->SetFont('Arial','B',10);
	//----------------------------
	$this->Cell(0,21,voltea_fecha($registro->fecha),1,0,'C');
	$this->SetFont('Arial','B',8);
	$this->SetY($y);
	$this->SetX($x+115);
	$this->Cell(35,7,'NUMERO',0,0,'C');
	$this->Cell(0,7,'FECHA DE CARGA',0,0,'C');
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
	$this->Cell(110,7,'Unidad Responsable:');
	$this->Cell(0,7,'Jefe o Responsable:');
	
	$this->ln(15);
	//----------------------------
	
	$this->SetFont('Arial','B',8);
	$y=$this->GetY();
	//----------------------------
	$this->Cell($a=12,10,'ITEM',1,0,'C');
	//$this->Cell($b=18,14,'CODIGO',1,0,'C');
	$this->Cell($c=125,10,'DESCRIPCION',1,0,'C');
	$this->Cell($d=20,10,'U.M.',1,0,'C');
	$this->Cell(0,10,'CANTIDAD',1,0,'C');
//	$y=$this->GetY();
//	$x=$this->GetX();
//	$this->MultiCell($e=20,7,'CANTIDAD',1,'C');
//	$this->SetY($y);
//	$this->SetX($x+$e);
//	$this->Cell($f=0,7,'PARA USO DEL ALMACEN',1,0,'C');
//	$this->ln(7);
//	$this->SetX($x+$e);
//	$this->Cell($g=20,7,'APROBADA',1,0,'C');
//	$this->Cell(0,7,'DESPACHADA',1,0,'C');
	//----------------------------
	$this->ln(10);
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
	$this->Cell(0,10,' Página '.$this->PageNo().' de {nb}',0,0,'C');
	}		
}

// INICIO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,20,17);
$pdf->AddPage();
$pdf->SetTitle('Ingreso de Materiales');
//----------------------------

$i=0;
$pdf->SetFont('Arial','',7.5);
//----------------------------
if ($_SESSION['estatus']>0)
	{
	//------ DATOS DE LA SOLICITUD
	$consultax = "SELECT * FROM bn_ingresos_detalle, bn_materiales WHERE bn_materiales.id_bien = bn_ingresos_detalle.id_bien AND id_ingreso=".$_SESSION['id'].";"; 
	}
else
	{
	//------ DATOS DE LA SOLICITUD
	$consultax = "SELECT * FROM bn_ingresos_detalle, bn_materiales WHERE bn_materiales.id_bien = bn_ingresos_detalle.id_bien AND estatus=0;"; 
	}
//echo $consultax;
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
	$pdf->MultiCell($c=125,$alto, ucfirst(strtolower($registrox->descripcion_bien)),1,'J');
	$y2=$pdf->GetY();
	//- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
	$pdf->SetY($y1);
	$pdf->SetX($x);
	$alto2 = $y2 - $y1;
	//----------------------------
	$pdf->Cell($a=12,$alto2,$i.'x',1,0,'C');
	//$pdf->Cell($b=18,$alto,$registrox->codigo,1,0,'C');
	$pdf->SetX($x+$a+$c);
	$pdf->Cell($d=20,$alto2,$registrox->unidad,1,0,'C');
	$pdf->Cell(0,$alto2,$registrox->cantidad,1,0,'C');
	//----------------------------
	$pdf->ln($alto2);
	}

while ($pdf->GetY() < 221)
	{
	$i++;	
	//----------------------------
	$pdf->Cell($a,7,'',1,0,'C');
	//$pdf->Cell($b,7,'',1,0,'C');
	$pdf->Cell($c,7,'',1,0,'L');
	$pdf->Cell($d,7,'',1,0,'C');
	$pdf->Cell($e,7,'',1,0,'C');
	$pdf->Cell($f,7,'',1,0,'C');
	$pdf->Cell($g,7,'',1,0,'C');
	//----------------------------
	$pdf->ln(7);
	}

//$pdf->ln(1);
//$y=$pdf->GetY();
//$pdf->SetFont('Arial','B',8);
////----------------------------
//$pdf->Cell($a=50,7,'',0,0,'L');
//$pdf->Cell($a,7,'',0,0,'L');
//$x=$pdf->GetX();
//$pdf->Cell(45,4,'Por el Almacén:',1,0,'C');
//$pdf->Cell(0,4,'Solicitante:',1,0,'L');
////----------------------------
//
////$pdf->SetY($y);
//$pdf->ln();
//$pdf->SetX($x);
////----------------------------
//$pdf->Cell(45,7,'Firma:',0,0,'L');
//$pdf->Cell(0,7,'Firma y Fecha:',0,0,'L');
//
//$pdf->SetFont('Arial','',7.5);
//$pdf->SetY($y);
////---- PARA LOS BORDES
//$pdf->Cell($a,28,'',0,0,'C');
//$pdf->Cell($a,28,'',0,0,'C');
//$pdf->SetY($y+4);
//$pdf->SetX($x);
//$pdf->Cell(45,24,'',1,0,'C');
//$pdf->Cell(0,24,'',1,0,'C');
//
////---- PARA LOS CARGOS
//$pdf->SetY($y+22);
//$pdf->Cell($a*2,6,'',0,0,'C');
////----------------------------
//$pdf->Cell(45,6,'JEFE DE ALMACEN',0,1,'C');
////----------------------------

$pdf->Output();
?>