<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'utf8'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}

class CellPDF extends FPDF
{
	function Header()
	{
	$this->SetFillColor(2, 117, 216);
	$this->Image('../../images/logo_nuevo.jpg',30,10,30);
	// ---------------------
	//$this->SetY(12);
	//$instituto = instituto();
	$this->SetFont('Times','I',11.5);
	$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Dirección de Bienes, Materiales, Suministros y Archivo',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Rif G-20001287-0',0,0,'C'); 
	$this->Ln(8);
	
	}
	
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-18);
		$this->SetTextColor(120);
		//--------------
		$s=$this->PageNo();
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
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

// ENCABEZADO
$pdf=new CellPDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,15,17);
$pdf->SetAutoPageBreak(1,23);
$pdf->SetTitle('Ficha Bien Nacional');

$id = decriptar($_GET['id']);

// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',11);
$pdf->SetTextColor(0);
$pdf->SetFillColor(240);
//-----------------

$consultx = "SELECT bn_bienes.*, a_direcciones.direccion, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, bn_dependencias.codigo, bn_dependencias.division FROM bn_bienes INNER JOIN bn_dependencias ON bn_bienes.id_dependencia = bn_dependencias.id INNER JOIN a_direcciones ON bn_dependencias.id_direccion = a_direcciones.id INNER JOIN rac ON a_direcciones.cedula = rac.cedula WHERE id_bien = '$id';";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-----------------
$pdf->Ln();
$pdf->Cell(50,6,'ETIQUETA PERMANENTE',1,0,'L',1);
$pdf->Cell(50,6,$registro->numero_bien,1,0,'C',0);
//$pdf->Cell(50,6,'ETIQUETA ORIGINAL',1,0,'C',1);
//$pdf->Cell(0,6,$registro->numero_bien,1,0,'C',1);
$pdf->Ln(8);

$pdf->Cell(30,6,'DESCRIPCION',1,0,'L',1);
$pdf->Cell(0,6,$registro->descripcion_bien,1,0,'L',0);
$pdf->Ln(8);

$pdf->Cell(50,6,'FECHA DE ADQUISICION',1,0,'L',1);
$pdf->Cell(70,6,voltea_fecha($registro->fecha_adquisicion),1,0,'C',0);
$pdf->Cell(50,6,'CONDICION',1,0,'L',1);
$pdf->Cell(0,6,$registro->conservacion,1,0,'C',0);
$pdf->Ln(8);

$pdf->Cell(50,6,'MARCA',1,0,'L',1);
$pdf->Cell(70,6,$registro->marca,1,0,'L',0);
$pdf->Cell(50,6,'MODELO',1,0,'L',1);
$pdf->Cell(0,6,$registro->modelo,1,0,'L',0);
$pdf->Ln();

$pdf->Cell(50,6,'FABRICANTE',1,0,'L',1);
$pdf->Cell(70,6,$registro->fabricante,1,0,'L',0);
$pdf->Cell(50,6,'SERIAL',1,0,'L',1);
$pdf->Cell(0,6,$registro->serial,1,0,'L',0);
$pdf->Ln(8);

$contribuyente = contribuyente($registro->proveedor);
$proveedor = $registro->proveedor.' - ' . $contribuyente[1];

$pdf->Cell(40,6,'PROVEEDOR',1,0,'L',1);
$pdf->Cell(0,6,$proveedor,1,0,'L',0);
$pdf->Ln();

$pdf->Cell(40,6,'ORDEN DE COMPRA',1,0,'L',1);
$pdf->Cell(70,6,$registro->orden_compra,1,0,'C',0);
$pdf->Cell(40,6,'FACTURA',1,0,'L',1);
$pdf->Cell(0,6,$registro->factura,1,0,'C',0);
$pdf->Ln();

$pdf->Cell(40,6,'COSTO',1,0,'L',1); 
$pdf->Cell(40,6,formato_moneda($registro->valor),1,0,'C',0);
$pdf->Cell(40,6,'CUENTA',1,0,'L',1);
$pdf->Cell(0,6,formato_partida($registro->cuenta).' '.partida($registro->cuenta),1,0,'L',0);
$pdf->Ln(8);

$pdf->Cell(50,6,'UBICACION',1,0,'L',1);
$pdf->Cell(0,6,$registro->division,1,0,'L',0);
$pdf->Ln();
$pdf->Cell(50,6,'CUSTODIO',1,0,'L',1);
$pdf->Cell(0,6,$registro->nombre,1,0,'L',0);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$x = $pdf->GetX()+70;
$y = $pdf->GetY();

$consulta_div = "SELECT bn_bienes.*, division, codigo FROM bn_bienes, bn_dependencias WHERE bn_bienes.id_dependencia = bn_dependencias.id AND id_bien = '$id';"; 
$tabla_div = $_SESSION['conexionsql']->query($consulta_div);
while ($registro_div = $tabla_div->fetch_object())
	{
	//-------------
	$pdf->Image('../../images/logo_nuevo.jpg',$x,$y,30);

	if ($_SERVER['HTTP_HOST']=='localhost')
		{$pdf->Image("http://localhost/cebg/scripts/qr_generador.php?code=".$registro_div->numero_bien,$x+70.5,$y-1,34,34,"png");}
	else
		{$pdf->Image("http://app.cebg.com.ve/scripts/qr_generador.php?code=".$registro_div->numero_bien,$x+70.5,$y,34,34,"png");}
	//-----------
	$pdf->SetFont('Times','',8);
	$pdf->SetXY($x+77,$y-1);
	$pdf->Cell(21,5,date('d/m/Y'),0,0,'C');
	$pdf->SetFont('Times','',6.5);
	$pdf->SetXY($x+27.5,$y);
	$pdf->Cell(46,5,('REPUBLICA BOLIVARINA DE VENEZUELA'),0,0,'C');
	$pdf->SetXY($x+27.5,$y+4);
	$pdf->Cell(46,5,('CONTRALORIA DEL ESTADO BOLIVARIANO'),0,0,'C');
	$pdf->SetXY($x+27.5,$y+7);
	$pdf->Cell(46,5,('DE GUARICO'),0,0,'C');
	$pdf->SetXY($x+27.5,$y+11);
	$pdf->Cell(46,5,('DIRECCION DE ADMINISTRACION Y'),0,0,'C');
	$pdf->SetXY($x+27.5,$y+14);
	$pdf->Cell(46,5,('PRESUPUESTO'),0,0,'C');
	//$pdf->Cell(50,5,($registro_div->division),0,0,'C');
	$pdf->SetXY($x+27.5,$y+21);
	$pdf->SetFont('Times','',20);
	$pdf->Cell(46,5,$registro_div->numero_bien,0,0,'C');
	$pdf->SetXY($x+1,$y+28);
	$pdf->SetFont('Times','',6.5);
	$pdf->Cell(100,5,($registro_div->division),0,0,'C');
	$pdf->SetXY($x+1,$y-2);
	$pdf->Cell(100,35,'',1,0,'C');
	//-----------
	$x += 102;
	}

$pdf->Output();
?>