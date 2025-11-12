<?php
session_start();
include "../../conexion.php";
include('../../funciones/auxiliar_php.php');
require('../../lib/fpdf/fpdf.php');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");
//$tabla_div = $_SESSION['conexionsql']->query($consulta_div);

//$_SESSION['AREA'] = $_GET['area'];
if ($_GET['division']=='0') 
	{	$_SESSION['id_dependencia'] = 0;	}
	else 
		{	$_SESSION['id_dependencia'] = decriptar($_GET['division']);	}

$_SESSION['tipo']= 0;

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: index.php?errorusuario=val"); 
    exit(); 
	}

class PDF extends FPDF
{
function Header()
	{
	$comprobante = 'INVENTARIO';
	include "../formatos/cabecera.php";
	include "../formatos/titulos.php";
	}	
	
function Footer()
	{   
	include "../formatos/pie.php";
	//Posición a 1,5 cm del final
	$this->SetY(-14);
	//Arial itálica 8
	$this->SetFont('Times','I',9);
	//Color del texto en gris
	$this->SetTextColor(120);
	//Número de página
	$this->Cell(100,10,'Impreso: '.$_SESSION['CEDULA_USUARIO'],0,0,'L');
	$this->Cell(0,10,'SIACEBG '.$this->PageNo().' de {nb}',0,0,'R');
	}		
}

// INICIO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,30,17);
$pdf->SetTitle('Inventario');
//-------------------
	
	$_SESSION['i'] = 0;
	$_SESSION['monto'] = 0;	
	$_SESSION['nombres']='si';
	//---------- FILTRO POR DIVISION
	if ($_SESSION['id_dependencia']=='0')
		{
		$consulta_div = "SELECT bn_dependencias.* FROM bn_bienes,	bn_dependencias WHERE bn_bienes.id_dependencia = bn_dependencias.id GROUP BY bn_dependencias.id ORDER BY bn_dependencias.id"; 
		$tabla_div = $_SESSION['conexionsql']->query($consulta_div);
		while ($registro_div = $tabla_div->fetch_object())
			{

			$_SESSION['id_dependencia'] = $registro_div->id;
			$_SESSION['DIVISION_L'] = $registro_div->division;
			$_SESSION['id_direccion'] = $registro_div->id_direccion;
			//$_SESSION['AREAS'] = '0';
			$_SESSION['monto'] = 0;
			$_SESSION['i'] = 0;
			//------------
				include "x_inventario_cuerpo.php";
				//----------
				$_SESSION['id_dependencia'] = 'ULTIMA';
				//-- RESUMEN
				include "x_inventario_resumen.php";
//			$pdf->AddPage();
			}
		//-- RESUMEN
		$_SESSION['id_dependencia'] = 'FINAL';
		$_SESSION['monto'] = 0;
		$_SESSION['i'] = 0;
		include "x_inventario_resumen_region.php";
		}
	else
		{
		$consulta_div = "SELECT bn_dependencias.* FROM bn_bienes,	bn_dependencias WHERE bn_bienes.id_dependencia = bn_dependencias.id AND bn_dependencias.id=".$_SESSION['id_dependencia']." GROUP BY bn_dependencias.id ORDER BY bn_dependencias.id LIMIT 1"; //echo $consulta_div;
		$tabla_div = $_SESSION['conexionsql']->query($consulta_div);
		while ($registro_div = $tabla_div->fetch_object())
			{ 
				//$_SESSION['id_dependencia'] = $registro_div->id;
				$_SESSION['id_direccion'] = $registro_div->id_direccion;
				$_SESSION['DIVISION_L'] = $registro_div->division; //echo $_SESSION['id_direccion'];
				//$_SESSION['AREAS'] = '0';
				$_SESSION['monto'] = 0;
				$_SESSION['i'] = 0;
				//------------
				$_SESSION['fecha'] = date('d/m/Y');
				include "x_inventario_cuerpo.php";
				//-- RESUMEN
				include "x_inventario_resumen.php";
			}
		}

$pdf->Output();
?>