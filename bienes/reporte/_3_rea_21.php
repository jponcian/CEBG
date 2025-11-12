<?php
session_start();
include "../../conexion.php";
include('../../funciones/auxiliar_php.php');
require('../../lib/fpdf/fpdf.php');
//mysql_query("SET NAMES 'latin1'");

$_SESSION['tipo'] = 21;
$_SESSION['origen'] = decriptar($_GET['origen']);
$_SESSION['destino'] = decriptar($_GET['destino']);
$_SESSION['estatus'] = ($_GET['estatus']);
$_SESSION['id'] = decriptar($_GET['id']);

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: index.php?errorusuario=val"); 
    exit(); 
	}

class PDF extends FPDF
{
function Header()
	{
	$comprobante = 'REASIGNACION';
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
	$this->Cell(460,10,'SIACEBG '.$this->PageNo().' de {nb}',0,0,'C');
	}		
}

// INICIO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,30,17);
$pdf->SetTitle('Comprobante de Reasignacion');
//-------------------
	
	$_SESSION['i'] = 0;
	$_SESSION['monto'] = 0;	
	
	//----------
	if ($_SESSION['estatus']==0)
		{
		$consulta_div = "SELECT bn_reasignaciones_detalle.usuario, bn_reasignaciones_detalle.fecha, bn_dependencias.division,  bn_dependencias.id_direccion FROM bn_reasignaciones_detalle, bn_dependencias WHERE bn_reasignaciones_detalle.id_origen = ".$_SESSION['origen']." AND bn_reasignaciones_detalle.id_destino = ".$_SESSION['destino']." AND bn_reasignaciones_detalle.estatus = 0 AND bn_reasignaciones_detalle.id_destino = bn_dependencias.id GROUP BY division;"; 
		}
	else
		{
		$consulta_div = "SELECT bn_reasignaciones.usuario, bn_reasignaciones.fecha, bn_dependencias.division,  bn_dependencias.id,  bn_dependencias.id_direccion FROM bn_reasignaciones_detalle, bn_dependencias, bn_reasignaciones WHERE bn_dependencias.id =  bn_reasignaciones.division_destino AND bn_reasignaciones_detalle.id_reasignacion=bn_reasignaciones.id AND  bn_reasignaciones.id = ".$_SESSION['id']." GROUP BY bn_reasignaciones.id;"; 
		}
//		echo $consulta_div;
	$tabla_div = $_SESSION['conexionsql']->query($consulta_div);
	while ($registro_div = $tabla_div->fetch_object())
		{
			$_SESSION['id_dependencia'] = $registro_div->id_direccion;
			$_SESSION['DIVISION_L'] = $registro_div->division;
			$_SESSION['fecha'] = voltea_fecha($registro_div->fecha);
			$_SESSION['usuariob'] = ($registro_div->usuario);
			//------------
			$_SESSION['monto'] = 0;
			$_SESSION['i'] = 0;
			include "movimiento_cuerpo.php";
			//----------
		}
		
$pdf->Output();
?>