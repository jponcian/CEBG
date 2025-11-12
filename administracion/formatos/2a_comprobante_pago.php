<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

//if ($_SESSION['VERIFICADO'] != "SI") { 
//    header ("Location: ../index.php?errorusuario=val"); 
//    exit(); 
//	}
	
class CellPDF extends FPDF
{
	function Header()
	{    
		$this->SetY(10);
		$id = decriptar($_GET['id']);
$consultx = "SELECT ordenes_pago.usuario, ordenes_pago.iva, ordenes_pago.islr, ordenes_pago.descuentos, ordenes_pago.id_contribuyente, ordenes_pago.tipo_solicitud, ordenes_pago.descripcion, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.total, ordenes_pago.estatus, ordenes_pago.num_comprobante, ordenes_pago.fecha_comprobante, YEAR(ordenes_pago.fecha) as anno, contribuyente.nombre, contribuyente.rif FROM ordenes_pago , contribuyente WHERE ordenes_pago.id = $id AND ordenes_pago.id_contribuyente = contribuyente.id ;"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		//-------------
		$anno = $registro->anno;
		$descripcion = $registro->descripcion;
		$rif = $registro->rif;
		$contribuyente = $registro->nombre;
		//-------------
		$tipo_solicitud = $registro->tipo_solicitud;
		$fecha = $registro->fecha_comprobante;
		$numero = $registro->num_comprobante;
		//-------
		$_SESSION['orden'] = $registro->numero;
		$_SESSION['descripcion'] = $registro->descripcion;
		$_SESSION['descuentos'] = $registro->descuentos;
		$_SESSION['iva'] = $registro->iva;
		$_SESSION['islr'] = $registro->islr;
		$_SESSION['total']= $registro->total;
		$_SESSION['estatus'] = $registro->estatus;
		$_SESSION['empleado'] = $registro->usuario;
		//--------------
	
		$this->SetFillColor(240);
		if (anno($fecha)<2024)
		{$this->Image('../../images/logo_2023.jpg',27,7,32);}
		else
		{$this->Image('../../images/logo_nuevo.jpg',27,7,40);}
		$this->Image('../../images/bandera_linea.png',17,41,182,1);
		$this->Image('../../images/linea.png',17,51.5,182,1);
		$this->SetFont('Times','',11);
		// ---------------------

		//$this->SetY(12);
		////$instituto = instituto();
		$this->SetFont('Times','I',11.5);
		$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Rif G-20001287-0 - Ejercicio Fiscal '.$anno,0,0,'C'); 
		$this->Ln(8);
		
		$this->SetFont('Times','B',14);
		$this->Cell(0,5,'NOTA DE DEBITO',0,0,'C'); 
		$this->Ln(8);
		
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
		$this->SetY($y+2);
		
		$this->SetFont('Times','',10);
		$this->Cell(3,5,''); 
		$this->Cell(28,5,'BENEFICIARIO:',0,0,'L'); 
		$this->SetFont('Times','B',10);
		$this->MultiCell(112,4,$contribuyente,0); 
		$this->SetY($y+2);
		$this->Cell(28+118,5,''); 
		$this->SetFont('Times','',10);
		$this->Cell(7,5,'Rif:',0,0,'L');
		$this->SetFont('Times','B',10);
		$this->Cell(0,5,formato_rif($rif),0,0,'C'); 
		$this->Ln(11);
		
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
		$this->Cell(0,6,formato_moneda($_SESSION['iva']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Islr->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['islr']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Otro->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda(($_SESSION['descuentos']-$_SESSION['iva'])-$_SESSION['islr']),1,0,'R');
		$this->Ln(6);
		$this->Cell($a,6,'');
		$this->Cell(21,6,'Neto Bs->',1,0,'R',1);
		$this->Cell(0,6,formato_moneda($_SESSION['total']),1,0,'R');
		$this->Ln(6);
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
		
//		$this->Ln(2);
//		$this->SetFont('Times','',9);
//		$this->Cell(182/3,4,'RECIBÍ CONFORME:',0,0,'L');
//		$this->Cell(182/3,4,'Nombre y Apellido:',0,0,'C');
//		$this->Ln(2);
//		$this->Cell(182/3,4,'',0,0,'L');
//		$this->Cell(182/3,4,'',0,0,'C');
//		$this->Cell(0,4,'Fecha  _____/_____/________',0,0,'C');
//		$this->Ln(7);
//		$this->Cell(182/3,4,'',0,0,'L');
//		$this->Cell(182/3,4,'',0,0,'C');
//		$this->Cell(0,4,'________________',0,0,'C');
//		$this->Ln(4);
//		$this->Cell(182/3,4,'FIRMA Y SELLO',0,0,'C');
//		$this->Cell(182/3,4,'',0,0,'C');
//		$this->Cell(0,4,'C.I.',0,0,'C');
//		//--------------
//		$this->SetFont('Times','I',8);
//		$this->SetY(-13);
//		$this->SetTextColor(120);
//		//--------------
//		$this->Cell(80,0,$_SESSION['empleado'],0,0,'L');
//		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de paginas',0,0,'R');
//		
	}	

function VCell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false)
{
	//Output a cell
	$k=$this->k;
	if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
	{
		//Automatic page break
		$x=$this->x;
		$ws=$this->ws;
		if($ws>0)
		{
			$this->ws=0;
			$this->_out('0 Tw');
		}
		$this->AddPage($this->CurOrientation,$this->CurPageSize);
		$this->x=$x;
		if($ws>0)
		{
			$this->ws=$ws;
			$this->_out(sprintf('%.3F Tw',$ws*$k));
		}
	}
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$s='';
// begin change Cell function 
	if($fill || $border>0)
	{
		if($fill)
			$op=($border>0) ? 'B' : 'f';
		else
			$op='S';
		if ($border>1) {
			$s=sprintf('q %.2F w %.2F %.2F %.2F %.2F re %s Q ',$border,
						$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
		}
		else
			$s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
	}
	if(is_string($border))
	{
		$x=$this->x;
		$y=$this->y;
		if(is_int(strpos($border,'L')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
		else if(is_int(strpos($border,'l')))
			$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
			
		if(is_int(strpos($border,'T')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
		else if(is_int(strpos($border,'t')))
			$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
		
		if(is_int(strpos($border,'R')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		else if(is_int(strpos($border,'r')))
			$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		
		if(is_int(strpos($border,'B')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		else if(is_int(strpos($border,'b')))
			$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
	}
	if(trim($txt)!='')
	{
		$cr=substr_count($txt,"\n");
		if ($cr>0) { // Multi line
			$txts = explode("\n", $txt);
			$lines = count($txts);
			for($l=0;$l<$lines;$l++) {
				$txt=$txts[$l];
				$w_txt=$this->GetStringWidth($txt);
				if ($align=='U')
					$dy=$this->cMargin+$w_txt;
				elseif($align=='D')
					$dy=$h-$this->cMargin;
				else
					$dy=($h+$w_txt)/2;
				$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
				if($this->ColorFlag)
					$s.='q '.$this->TextColor.' ';
				$s.=sprintf('BT 0 1 -1 0 %.2F %.2F Tm (%s) Tj ET ',
					($this->x+.5*$w+(.7+$l-$lines/2)*$this->FontSize)*$k,
					($this->h-($this->y+$dy))*$k,$txt);
				if($this->ColorFlag)
					$s.=' Q ';
			}
		}
		else { // Single line
			$w_txt=$this->GetStringWidth($txt);
			$Tz=100;
			if ($w_txt>$h-2*$this->cMargin) {
				$Tz=($h-2*$this->cMargin)/$w_txt*100;
				$w_txt=$h-2*$this->cMargin;
			}
			if ($align=='U')
				$dy=$this->cMargin+$w_txt;
			elseif($align=='D')
				$dy=$h-$this->cMargin;
			else
				$dy=($h+$w_txt)/2;
			$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
			if($this->ColorFlag)
				$s.='q '.$this->TextColor.' ';
			$s.=sprintf('q BT 0 1 -1 0 %.2F %.2F Tm %.2F Tz (%s) Tj ET Q ',
						($this->x+.5*$w+.3*$this->FontSize)*$k,
						($this->h-($this->y+$dy))*$k,$Tz,$txt);
			if($this->ColorFlag)
				$s.=' Q ';
		}
	}
// end change Cell function 
	if($s)
		$this->_out($s);
	$this->lasth=$h;
	if($ln>0)
	{
		//Go to next line
		$this->y+=$h;
		if($ln==1)
			$this->x=$this->lMargin;
	}
	else
		$this->x+=$w;
}

	function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
{
	//Output a cell
	$k=$this->k;
	if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
	{
		//Automatic page break
		$x=$this->x;
		$ws=$this->ws;
		if($ws>0)
		{
			$this->ws=0;
			$this->_out('0 Tw');
		}
		$this->AddPage($this->CurOrientation,$this->CurPageSize);
		$this->x=$x;
		if($ws>0)
		{
			$this->ws=$ws;
			$this->_out(sprintf('%.3F Tw',$ws*$k));
		}
	}
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$s='';
// begin change Cell function
	if($fill || $border>0)
	{
		if($fill)
			$op=($border>0) ? 'B' : 'f';
		else
			$op='S';
		if ($border>1) {
			$s=sprintf('q %.2F w %.2F %.2F %.2F %.2F re %s Q ',$border,
				$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
		}
		else
			$s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
	}
	if(is_string($border))
	{
		$x=$this->x;
		$y=$this->y;
		if(is_int(strpos($border,'L')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
		else if(is_int(strpos($border,'l')))
			$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
			
		if(is_int(strpos($border,'T')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
		else if(is_int(strpos($border,'t')))
			$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
		
		if(is_int(strpos($border,'R')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		else if(is_int(strpos($border,'r')))
			$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		
		if(is_int(strpos($border,'B')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		else if(is_int(strpos($border,'b')))
			$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
	}
	if (trim($txt)!='') {
		$cr=substr_count($txt,"\n");
		if ($cr>0) { // Multi line
			$txts = explode("\n", $txt);
			$lines = count($txts);
			for($l=0;$l<$lines;$l++) {
				$txt=$txts[$l];
				$w_txt=$this->GetStringWidth($txt);
				if($align=='R')
					$dx=$w-$w_txt-$this->cMargin;
				elseif($align=='C')
					$dx=($w-$w_txt)/2;
				else
					$dx=$this->cMargin;

				$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
				if($this->ColorFlag)
					$s.='q '.$this->TextColor.' ';
				$s.=sprintf('BT %.2F %.2F Td (%s) Tj ET ',
					($this->x+$dx)*$k,
					($this->h-($this->y+.5*$h+(.7+$l-$lines/2)*$this->FontSize))*$k,
					$txt);
				if($this->underline)
					$s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
				if($this->ColorFlag)
					$s.=' Q ';
				if($link)
					$this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$w_txt,$this->FontSize,$link);
			}
		}
		else { // Single line
			$w_txt=$this->GetStringWidth($txt);
			$Tz=100;
			if ($w_txt>$w-2*$this->cMargin) { // Need compression
				$Tz=($w-2*$this->cMargin)/$w_txt*100;
				$w_txt=$w-2*$this->cMargin;
			}
			if($align=='R')
				$dx=$w-$w_txt-$this->cMargin;
			elseif($align=='C')
				$dx=($w-$w_txt)/2;
			else
				$dx=$this->cMargin;
			$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
			if($this->ColorFlag)
				$s.='q '.$this->TextColor.' ';
			$s.=sprintf('q BT %.2F %.2F Td %.2F Tz (%s) Tj ET Q ',
						($this->x+$dx)*$k,
						($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,
						$Tz,$txt);
			if($this->underline)
				$s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
			if($this->ColorFlag)
				$s.=' Q ';
			if($link)
				$this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$w_txt,$this->FontSize,$link);
		}
	}
// end change Cell function
	if($s)
		$this->_out($s);
	$this->lasth=$h;
	if($ln>0)
	{
		//Go to next line
		$this->y+=$h;
		if($ln==1)
			$this->x=$this->lMargin;
	}
	else
		$this->x+=$w;
}
}

$id = decriptar($_GET['id']);
//-------------	

// ENCABEZADO
$pdf=new CellPDF('P','mm','LETTER');
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
$consulta = "SELECT orden.categoria, orden.partida, orden.cantidad, orden.descripcion, sum(orden.total) as total, orden_solicitudes.numero AS solicitud, orden_solicitudes.tipo_orden, ordenes_pago.numero AS pago FROM orden , orden_solicitudes , ordenes_pago WHERE ordenes_pago.id= '$id' AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden.id_solicitud = orden_solicitudes.id GROUP BY ordenes_pago.numero, orden.categoria, orden.partida"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$_SESSION['lineas'] = $tabla->num_rows;
//-----------------
$i=1;
$_SESSION['monto'] = 0;
$alto = 5;
while ($registro = $tabla->fetch_object())
	{
	if ($registro->tipo_orden=='CC' or $registro->tipo_orden=='CD' or $registro->tipo_orden=='CP') {$letra='c';}
	if ($registro->tipo_orden=='M') {$letra='m';}
	if ($registro->tipo_orden=='F') {$letra='f';}
	//----------
	//$pdf->Cell($aa,$alto,$i+1,1,0,'C',0);
	$pdf->Cell(8,$alto,$i,1,0,'C',0);
	$pdf->Cell($a,$alto,rellena_cero($registro->solicitud,6).$letra,1,0,'C',0);
	$pdf->Cell($a,$alto,rellena_cero($registro->pago,6).'p',1,0,'C',0);
	$pdf->Cell($b=9,$alto,substr($registro->categoria,0,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->categoria,2,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->categoria,4,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->categoria,6,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->categoria,8,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->partida,0,3),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->partida,3,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->partida,5,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->partida,7,2),1,0,'C',0);
	$pdf->Cell($b,$alto,substr($registro->partida,9,3),1,0,'C',0);
	$pdf->SetFillColor(255);
	$pdf->Cell($c,$alto,formato_moneda($registro->total),1,1,'R',1);
	//-----------
	$_SESSION['monto']= $_SESSION['monto'] + $registro->total;
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

if ($_SESSION['estatus']==99)	
	{
	$pdf->SetY(140);
	$pdf->SetTextColor(255,0,0);
	$pdf->SetFont('helvetica','',35);
	$pdf->Cell(0,5,'COMPROBANTE ANULADO',0,0,'C'); 
	$pdf->SetFont('Times','',10);
	$pdf->SetTextColor(0);
	}

$pdf->Output();
?>