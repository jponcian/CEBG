<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
//$_SESSION['conexionsql']->mysql_query("SET NAMES 'utf8'");

//if ($_SESSION['VERIFICADO'] != "SI") { 
//    header ("Location: ../index.php?errorusuario=val"); 
//    exit(); 
//	}
	
class PDF extends FPDF
{
	function Header()
	{    
		$this->SetY(10);
		$id = decriptar($_GET['id']);
		$consultx = "SELECT ordenes_pago.*, contribuyente.rif, contribuyente.nombre as sujeto FROM ordenes_pago , contribuyente WHERE ordenes_pago.id = $id AND ordenes_pago.id_contribuyente = contribuyente.id LIMIT 1;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		$contribuyente = $registro->id_contribuyente;
		$rif = $registro->rif;
		$sujeto = $registro->sujeto;
		$tipo_solicitud = $registro->tipo_solicitud;
		$fecha = $registro->fecha_comprobante;
		$numero = $registro->num_comprobante;
		$asignaciones = $registro->asignaciones;
		$transferencia = $registro->num_pago;
		$fecha_pago = voltea_fecha($registro->fecha_pago);
		$banco = $registro->banco;
		$cuenta = $registro->cuenta;
		$banco2 = $registro->banco2;
		$cuenta2 = $registro->cuenta2;
		$_SESSION['orden'] = $registro->numero;
		$_SESSION['descripcion'] = $registro->descripcion;
		$_SESSION['descuentos'] = $registro->descuentos;
		$_SESSION['islr'] = $registro->islr;
		$_SESSION['total']= $registro->total;
		$_SESSION['empleado'] = $registro->usuario;
		//--------------
		$id_solicitud = 999999999999;
		$consultx = "SELECT anno, id FROM nomina_solicitudes WHERE id_orden_pago = $id;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro = $tablx->fetch_object())
			{
			$anno = $registro->anno;
			$id_solicitud = $id_solicitud .','. $registro->id;
			}
		$_SESSION['id_solicitud'] = $id_solicitud;
		//--------------
	
		$this->SetFillColor(240);
		if (anno($fecha)<2024)
		{$this->Image('../../images/logo_2023.jpg',27,7,32);}
		else
		{$this->Image('../../images/logo_nuevo.jpg',27,7,40);}
		$this->Image('../../images/bandera_linea.png',17,41,182,1);
		$this->SetFont('Times','',11);
		
		$municipio = 'Francisco de Miranda';
		// ---------------------
		//$this->SetY(12);
		//$instituto = instituto();
		$this->SetFont('Times','I',11.5);
		$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Rif G-20001287-0 - Ejercicio Fiscal '.$anno,0,0,'C'); 
		$this->Ln(8);
		
		$this->SetFont('Times','B',14);
		$this->Cell(0,5,'NOTA DE DEBITO',0,0,'C'); 
		$this->Ln(8);
		//$this->SetFont('Times','',10);
		//$this->Cell(0,5,'NOMINA',0,0,'C'); 
		//$this->Ln(7);
		
		$y=$this->GetY();
		$this->SetY(20);
		//$this->SetX(150);
		$this->SetFont('Arial','B',13);
		$this->SetTextColor(0,0,255);
//		$this->Cell(0,5,'Nro: '.rellena_cero($numero,5),0,0,'R'); 
		$this->Ln(7);
		$this->SetFont('Arial','B',11);
		$this->SetTextColor(255,0,0);
		//$this->Cell(0,5,'Fecha: '.voltea_fecha($fecha),0,0,'R'); //
		$this->SetTextColor(0);
		$this->SetY($y);
		$this->Ln(2);
		
		$this->SetFont('Times','',10);
		$this->Cell(3,5,''); 
		$this->Cell(28,5,'BENEFICIARIO:',0,0,'L'); 
		$this->SetFont('Times','B',10);
		$this->Cell(118,5,$sujeto,0,0,'L'); 
		$this->SetFont('Times','',10);
		$this->Cell(7,5,'Rif:',0,0,'L');
		$this->SetFont('Times','B',10);
		$this->Cell(0,5,formato_rif($rif),0,0,'C'); 
		$this->Ln(6);

		$consultx = "SELECT * FROM ordenes_pago_pagos WHERE id_orden = $id ;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro = $tablx->fetch_object())
			{
			//-------
			$transferencia = $registro->num_pago;
			$fecha_pago = voltea_fecha($registro->fecha_pago);
			$asignaciones = $registro->asignaciones;
			$monto= $registro->monto;
			$banco = $registro->banco;
			$cuenta = $registro->cuenta;
			$tipo = 'TRANSFERENCIA:';
//			if ($registro->tipo_pago==1) 
//				{
//				$this->Image('../../images/linea.png',17,100,182,1);
//				$tipo = 'CHEQUE:';
//				$this->SetFont('Arial','',10);
//				$this->Cell(175,5,formato_moneda($_SESSION['total']),0,0,'R'); 
//				$this->Ln(10);
//				$this->Cell(20,5,''); 
//				$this->Cell(0,5,$contribuyente,0,0,'L'); 
//				$this->Ln(10);
//				$this->SetFont('Arial','',8);
//				$this->Cell(10,5,''); 
//				$this->Multicell(0,5,strtoupper(valorEnLetras($_SESSION['total'])),0,'L'); 
//				$this->Ln(8);
//				$this->SetFont('Arial','',9);
//				$this->Cell(10,5,''); 
//				$this->Cell(0,5,'Calabozo Estado Guarico, '.voltea_fecha2($fecha),0,0,'L'); 
//				$this->SetY(102);
//				}

			$this->SetFont('Times','',10);
			$this->Cell(23,6,'Transferencia:',1,0,'C',1); 
			$this->SetFont('Times','B',10);
			$this->Cell(25,6,$transferencia,1,0,'L'); 
			$this->SetFont('Times','',10);
			$this->Cell(13,6,'Fecha:',1,0,'C',1); 
			$this->SetFont('Times','B',10);
			$this->Cell(18,6,$fecha_pago,1,0,'C',0); 
			$this->SetFont('Times','',10);
			$this->Cell(16,6,'BANCO:',1,0,'L',1); 
			$this->SetFont('Times','B',10);
			$this->Cell(41,6,$banco.' '.substr($cuenta,16,4),1,0,'L'); 
            $this->SetFont('Times','',10);
			$this->Cell(16,6,'MONTO:',1,0,'L',1); 
			$this->SetFont('Times','B',10);
			$this->Cell(0,6,formato_moneda($monto),1,0,'R'); 
			$this->Ln();
			}

		$this->Ln(5);
		$this->SetFont('Times','',10);
		$this->Cell(0,6,'IMPUTACION PRESUPUESTARIA',1,1,'C',1); 
		$this->SetFont('Times','B',9.5);
		$this->Cell(8,6,'Item',1,0,'C',1);
		$this->Cell($a=20,6,'Compromiso',1,0,'C',1);
		$this->Cell($a,6,'Causado',1,0,'C',1);
		//$this->Cell($a+1,6,'Partida',1,0,'C',1);
		$this->Cell($b=9,6,'Sec',1,0,'C',1);
		$this->Cell($b,6,'Prg',1,0,'C',1);	
		$this->Cell($b,6,'SPrg',1,0,'C',1);	
		$this->Cell($b,6,'Pry',1,0,'C',1);	
		$this->Cell($b,6,'Act',1,0,'C',1);	
		$this->Cell($b,6,'Par',1,0,'C',1);	
		$this->Cell($b,6,'Gen',1,0,'C',1);	
		$this->Cell($b,6,'Esp',1,0,'C',1);	
		$this->Cell($b,6,'SEsp',1,0,'C',1);	
		$this->Cell($b,6,'Aux',1,0,'C',1);	
		$this->Cell($c=0,6,'Total',1,0,'C',1);	
		$this->Ln();
	}	
	
	function Footer()
	{    
		//-------------------------------------------------
		$this->SetY(-75);
		$this->SetFillColor(245);
		$alto = 7;
		$this->Cell($a=130,6,'DETALLES',1,0,'L',1);
		$y=$this->GetY();
		$this->Ln(6);
		$this->SetFont('Times','B',9);
		$this->MultiCell($a,4,$_SESSION['descripcion'],1);
		$y2=$this->GetY();
		
		$this->SetY($y);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Van... Bs->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['monto']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Iva->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda(0),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Islr->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['islr']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Otro->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['descuentos']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Neto Bs->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['total']),1,1,'R');
		//$this->Ln(6.5);
		$y3=$this->GetY();
		
		if ($y2>$y3)
			{
			$this->SetY($y2);
			}
		
		$this->Cell(182/3,20,'',1,0,'L');
		$this->Cell(182/3,20,'',1,0,'C');
//		$this->Cell(0,20,'',1,0,'C');

		if ($y2>$y3)
			{
			$this->SetY($y2);
			}

		$this->Ln(2);
		$this->SetFont('Times','',9);
//		$this->Cell(182/3,4,'RECIBÍ CONFORME:',0,0,'L');
//		$this->Cell(182/3,4,'Nombre y Apellido:',0,0,'C');
		$this->Ln(2);
//		$this->Cell(182/3,4,'',0,0,'L');
//		$this->Cell(182/3,4,'',0,0,'C');
//		$this->Cell(0,4,'Fecha  _____/_____/________',0,0,'C');
		$this->Ln(7);
//		$this->Cell(182/3,4,'',0,0,'L');
//		$this->Cell(182/3,4,'',0,0,'C');
//		$this->Cell(0,4,'________________',0,0,'C');
		$this->Ln(4);
		$this->Cell(182/3,4,'Director de Admon y Presupuesto',0,0,'C');
		$this->Cell(182/3,4,'Analista de Finanzas',0,0,'C');
//		$this->Cell(0,4,'C.I.',0,0,'C');
		//--------------
		$this->SetFont('Times','I',8);
		$this->SetY(-13);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['empleado'],0,0,'L');
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de paginas',0,0,'R');
		
	}	
}

$id = decriptar($_GET['id']);
//-------------	

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(17,80,17);
$pdf->SetAutoPageBreak(1,73);
$pdf->SetTitle('Comprobante de Pago');

// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',9);
$a=20;
$b=9;
$c=0;

//-----------------
$consulta = "SELECT nomina_asignaciones.categoria, nomina_asignaciones.partida, a_partidas.descripcion,
sum(nomina_asignaciones.asignaciones) as asignaciones, sum(nomina_asignaciones.total_asignacion) as total_asignaciones, nomina_solicitudes.num_sol_pago FROM nomina_solicitudes, nomina , nomina_asignaciones, a_partidas WHERE nomina_solicitudes.id = nomina.id_solicitud AND nomina.id_solicitud in (".$_SESSION['id_solicitud'].") AND nomina.id = nomina_asignaciones.id_nomina AND a_partidas.codigo = nomina_asignaciones.partida GROUP BY nomina_asignaciones.categoria, nomina_asignaciones.partida ORDER BY num_sol_pago, nomina_asignaciones.categoria, nomina_asignaciones.partida;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$_SESSION['lineas'] = $tabla->num_rows;
//-----------------
$i=1;
$_SESSION['monto'] = 0;
$alto = 5;
while ($registro = $tabla->fetch_object())
	{
	//----------
	//$pdf->Cell($aa,$alto,$i+1,1,0,'C',0);
	$pdf->Cell(8,$alto,$i,1,0,'C',0);
	$pdf->Cell($a,$alto,rellena_cero($registro->num_sol_pago,6).'n',1,0,'C',0);
	$pdf->Cell($a,$alto,rellena_cero($_SESSION['orden'],6).'p',1,0,'C',0);
	$pdf->Cell($b=9,$alto,substr($registro->categoria,0,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->categoria,2,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->categoria,4,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->categoria,6,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->categoria,8,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->partida,0,3),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->partida,3,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->partida,5,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->partida,7,2),1,0,'C',0);
	$pdf->Cell($b,$alto,'000',1,0,'C',0);
	$pdf->SetFillColor(255);
	$pdf->Cell($c,$alto,formato_moneda($registro->asignaciones),1,1,'R',1);
	//-----------
	$_SESSION['monto']= $_SESSION['monto'] + $registro->asignaciones;
	$_SESSION['lineas']--;
	$i++;
	}

if ($pdf->GetY()<$y=205)
	{
	$y = $y - 0.5;
	$pdf->Cell(8,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($a,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($a,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell($b,$y-$pdf->GetY(),'',1,0,'C',0);
	$pdf->Cell(0,$y-$pdf->GetY(),'',1,1,'C',0);
	}

$pdf->Output();
?>