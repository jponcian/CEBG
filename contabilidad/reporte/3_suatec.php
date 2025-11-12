<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class CellPDF extends FPDF
{
	function Header()
	{
	$fecha = ($_GET['fecha1']);
	$fechaf = ($_GET['fecha2']);
	$fecha1 = voltea_fecha($_GET['fecha1']);
	$fecha2 = voltea_fecha($_GET['fecha2']);
	$tipo = (8);
	
	$consulta = "SELECT * FROM a_descuentos WHERE id = $tipo;";
	$tabla = $_SESSION['conexionsql']->query($consulta);
	$registro = $tabla->fetch_object();
	$rif_beneficiario = $registro->rif_beneficiario;
	$beneficiario = $registro->beneficiario;
	$titulo = $registro->decripcion;

	$this->SetFillColor(230);
	$this->Image('../../images/logo_nuevo.jpg',30,10,25);
	$this->Image('../../images/escudo.jpg',210,10,26);
	//$this->Image('../../images/logo_web.png',100,80,100);
	$this->SetFont('Times','',11);

	// ---------------------
	$this->SetY(10);
	$this->SetFont('Times','I',11.5);
	$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Gobernacion del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Superintendencia de Administración Tributaria del Estado Guárico',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'SUATEG',0,0,'C'); $this->Ln(10);

	$this->SetFont('Times','',10);
	$this->Cell(3,5,''); 
	$this->Cell(50,5,'R.I.F.:',0,0,'L'); 
	$this->SetFont('Times','B',10);
	$this->Cell(113,5,'G-20001287-0',0); 
	$this->Ln(5);
	$this->SetFont('Times','',10);
	$this->Cell(3,5,''); 
	$this->Cell(50,5,'NOMBRE / RAZON SOCIAL:',0,0,'L'); 
	$this->SetFont('Times','B',10);
	$this->Cell(113,5,'CONTRALORIA DEL ESTADO BOLIVARIANO DE GUARICO',0); 
	$this->Ln(5);
	$this->SetFont('Times','',10);
	$this->Cell(3,5,''); 
	$this->Cell(50,5,'DIRECCION FISCAL:',0,0,'L'); 
	$this->SetFont('Times','B',10);
	$this->Cell(113,5,'San Juan los Morros, Calle Mariño entre Bolívar y Sendrea, Edif. Don Vito.',0); 
	$this->Ln(5);
	//-----------
	$this->SetFont('Times','',10);
	$this->Cell(180,5,'');
	$this->Cell(46,5,'MES/AÑO A REPORTAR:',0,0,'C'); //$this->Ln(6);
	$this->SetFont('Times','B',11);
	$this->Cell(0,5,mes(voltea_fecha($fecha)).'/'.anno(voltea_fecha($fecha)),0); 
		
	//$this->Cell(0,5,'del '.($fecha).' al '.($fechaf),0,0,'C'); 
	$this->Ln(10);
	//-----------
	$this->SetFont('Times','BU',12);
	$this->Cell(0,5,'RELACION MENSUAL DEL IMPUESTO TRES POR CIEN (3X100) ENTES Y ORGANOS PUBLICOS',0,0,'C'); 
	$this->Ln(10);
	//$this->Cell(0,5,'del '.($fecha).' al '.($fechaf),0,0,'C'); 
	//$this->Ln(10);

	$this->SetFont('Times','B',9);
	$this->Cell(22,7,'Comprobante',1,0,'C',1);
	$this->Cell(15,7,'Fecha',1,0,'C',1);
	$this->Cell(13,7,'Periodo',1,0,'C',1);
	$this->Cell(60,7,'Razon Social',1,0,'C',1);
	$this->Cell(20,7,'Rif',1,0,'C',1);
	$this->Cell(20,7,'Factura',1,0,'C',1);
	$this->Cell(15,7,'Fecha',1,0,'C',1);
	$this->Cell(20,7,'Transferencia',1,0,'C',1);
	$this->Cell(25,7,'Banco',1,0,'C',1);
	$this->Cell(25,7,'Monto',1,0,'C',1);
	$this->Cell(0,7,'Impuesto',1,1,'C',1);
		
	}
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-13);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
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

$fecha = ($_GET['fecha1']);
$fechaf = ($_GET['fecha2']);
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);

// ENCABEZADO
$pdf=new CellPDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(12,12,12);
$pdf->SetAutoPageBreak(1,23);
$pdf->SetTitle('Relacion SUATEC');

$pdf->AddPage();
// ----------
$consulta1 = "SELECT * FROM vista_suatec WHERE fecha_op >= '$fecha1' AND fecha_op <= '$fecha2' AND estatus>=10 AND estatus<=15;";
//echo $consulta1;
$tabla1 = $_SESSION['conexionsql']->query($consulta1);
//-----------------
$pdf->SetFont('Times','',8);	
$pdf->SetFillColor(255);

while ($registro1 = $tabla1->fetch_object())
	{
	$monto += $registro1->monto;
	$descuento += $registro1->descuento;
	// ----------
	$pdf->Cell(22,6.5,anno(($registro1->fecha)).mes(($registro1->fecha)).rellena_cero($registro1->numero,8),1,0,'R',0);
	$pdf->Cell(15,6.5,voltea_fecha($registro1->fecha),1,0,'C',0);
	$pdf->Cell(13,6.5,mes($registro1->fecha).'-'.anno($registro1->fecha),1,0,'C',0);
	$pdf->Cell(60,6.5,($registro1->nombre),1,0,'L',0);
	$pdf->Cell(20,6.5,($registro1->rif),1,0,'C',0);
	$pdf->Cell(20,6.5,($registro1->factura),1,0,'R',0);
	$pdf->Cell(15,6.5,voltea_fecha($registro1->fecha_factura),1,0,'C',0);
	$pdf->Cell(20,6.5,'',1,0,'L',0);
	$pdf->Cell(25,6.5,'',1,0,'L',0);
	$pdf->Cell(25,6.5,formato_moneda($registro1->monto),1,0,'R',0);
	$pdf->Cell(0,6.5,formato_moneda($registro1->descuento),1,0,'R',0);
	// ----------
	$pdf->Ln(6.5);
	}

$pdf->SetFont('Times','B',10);
$pdf->Cell(22+15+13+60+20+20+15+20+25,7,'Total Bs => ',1,0,'R',1);
$pdf->Cell(25,7,formato_moneda($monto),1,0,'R',1);
$pdf->Cell(0,7,formato_moneda($descuento),1,0,'R',1);
$pdf->Ln(15);

$pdf->SetFont('Times','BU',10);
$pdf->Cell(0,6,'Cuentas a Depositar de Recaudacion',0,0,'L',0);
$pdf->Ln(10);

$pdf->SetFont('Times','BU',10);
$pdf->Cell(42,6,'R.I.F.:',0,0,'L',1);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,6,'G-20009151-7',0,0,'L',1);
$pdf->Ln(6);

$pdf->SetFont('Times','BU',10);
$pdf->Cell(42,6,'Nombre de la Institucion:',0,0,'L',1);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,6,'SUPERINTENDENCIA DE ADMINISTRACIÓN TRIBUTARIA DEL ESTADO GUÁRICO',0,0,'L',1);
$pdf->Ln(6);

$pdf->SetFont('Times','BU',10);
$pdf->Cell(42,6,'Cuentas Corrientes:',0,0,'L',1);
$pdf->Ln(6);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,6,'Banco de Venezuela N° 0102-0854-81-0000086037',0,1,'L',1);
$pdf->Cell(0,6,'Banco Bicentenario N° 0175-0557-26-0072955597',0,1,'L',1);
$pdf->Cell(0,6,'Banco B.O.D            N° 0116-0024-75-0023-435860',0,1,'L',1);

$pdf->Output();
?>