<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'utf8'");

//-------------	
$consultax = "CALL actualizar_asistencia();"; //echo $consultx ;
//$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class CellPDF extends FPDF
{
	function Header()
	{
	}
	
	function Footer()
	{    
		$this->SetY(-18);
		$this->SetFont('Times','B',15);
		$this->Cell(0,0,'NOTA: ESTIMADOS FUNCIONARIOS FAVOR NO REMARCAR LA ESCRITURA ',0,0,'C');
		$this->SetY(-12);
		$this->SetTextColor(120);
		$this->SetFont('Times','I',8);
		$this->Cell(0,0,'SIACEBG Pagina '.$this->PageNo().' - Semana '.voltea_fecha($_SESSION['fecha']),0,0,'R');
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
$pdf->SetMargins(10,15,10);
$pdf->SetAutoPageBreak(1,23);
$pdf->SetTitle('Control de Asistencia Diaria');

$i=0;
//$_GET['desde'] = '31/10/2022';
while ($i<=7 and (date('N',fecha_a_numero(voltea_fecha($_GET['desde'])))<>'1'))
	{
	$i++;
	$_GET['desde'] = voltea_fecha(sube_dia(voltea_fecha($_GET['desde'])));
//	echo date('N',fecha_a_numero(voltea_fecha($_GET['desde'])));
	}

$fecha1 = fecha_a_numero(voltea_fecha($_GET['desde']));
$hasta1 = fecha_a_numero(voltea_fecha($_GET['hasta']));
$fecha = voltea_fecha($_GET['desde']);
$hasta = voltea_fecha($_GET['hasta']);
$tipo = decriptar($_GET['tipo']);
$cedula = ($_GET['cedula']);
$direccion = decriptar($_GET['direccion']);

if ($direccion==0) {}
	else { 	if ($cedula==0) {	$filtro = ' AND rac.id_div='.$direccion;	}	else {	$filtro = ' AND rac.cedula='.$cedula;	}	}

$_SESSION['titulo']="FECHA ".voltea_fecha($fecha);

$consult = "SELECT rac.*, a_direcciones.direccion FROM rac, a_direcciones WHERE a_direcciones.id=rac.id_div $filtro ORDER BY rac.id_div ASC, rac.jefe_division DESC, rac.cedula+0 ASC"; // WHERE id_direccion='$desde'

while ($fecha1<=$hasta1)
	{	
$_SESSION['fecha'] = $fecha;
//echo $consult;
$pdf->AddPage();

$pdf->SetFillColor(2, 117, 216);
$pdf->Image('../../images/escudog.png',30,7,25);
//$pdf->Image('../../images/personal.png',125,10,35);
$pdf->Image('../../images/logo_nuevo.jpg',224,7,25);
// ---------------------
$pdf->Ln(10);
$pdf->SetFont('Times','B',20);
$pdf->Cell(0,5,"CONTROL DE ASISTENCIA",0,0,'C'); 
$pdf->Ln(10);

$pdf->SetTextColor(255);
$pdf->SetFont('Times','B',9);
$pdf->Cell($aa=8,13,'N°',1,0,'C',1);
$pdf->Cell($a=16,13,'CODIGO',1,0,'C',1);
$pdf->Cell($b=55,13,'EMPLEADO',1,0,'C',1);
$pdf->Cell($c=17,13,'CEDULA',1,0,'C',1);

$x = $pdf->GetX();
$pdf->Cell($d=33,13,'',1,0,'C',1);
$pdf->Cell($d,13,'',1,0,'C',1);
$pdf->Cell($d,13,'',1,0,'C',1);
$pdf->Cell($d,13,'',1,0,'C',1);
$pdf->Cell(0,13,'',1,0,'C',1);

$pdf->SetFont('Times','B',10);

$pdf->SetX($x);
$pdf->Cell($d,7,'LUNES',0,0,'C',0);
$pdf->Cell($d,7,'MARTES',0,0,'C',0);
$pdf->Cell($d,7,'MIERCOLES',0,0,'C',0);
$pdf->Cell($d,7,'JUEVES',0,0,'C',0);
$pdf->Cell(0,7,'VIERNES',0,0,'C',0);
$pdf->Ln(5);
	
$pdf->SetX($x);
$pdf->Cell($d,7,voltea_fecha($fecha),0,0,'C',0);
$pdf->Cell($d,7,voltea_fecha(sube_dia($fecha)),0,0,'C',0);
$pdf->Cell($d,7,voltea_fecha(sube_dia(sube_dia($fecha))),0,0,'C',0);
$pdf->Cell($d,7,voltea_fecha(sube_dia(sube_dia(sube_dia($fecha)))),0,0,'C',0);
$pdf->Cell(0,7,voltea_fecha(sube_dia(sube_dia(sube_dia(sube_dia($fecha))))),0,0,'C',0);

$pdf->Ln(7);
 
	// ----------
	$pdf->SetFont('Times','',9);
	$pdf->SetTextColor(0);
	$pdf->SetFillColor(255);
	$i=0;
	$nomina = '';
	$direccion = '';
	//-----------------

	$tabla = $_SESSION['conexionsql']->query($consult);
	//-----------------
	$i=0;
	while ($registro = $tabla->fetch_object())
		{
		if ($direccion<>$registro->id_div)
			{	
			$pdf->SetFont('Times','B',12);
			$pdf->SetFillColor(91, 192, 222);
			$pdf->Cell(0,7,$registro->direccion,1,1,'C',1);	
			$direccion = $registro->id_div ;
			}
		//----------
		if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(250);}
		//----------
		$pdf->SetFont('Times','',9);
		$pdf->Cell($aa,20,$i+1,1,0,'C',1);
		$pdf->Cell($a,20,rellena_cero($registro->rac,3),1,0,'C',1);
		$pdf->SetFont('Times','',8);
		$pdf->Cell($b,20,substr($registro->nombre,0,50),1,0,'L',1);
		$pdf->Cell($c,20,formato_cedula($registro->cedula),1,0,'C',1);

		$x = $pdf->GetX();
		$pdf->Cell($d,20,'',1,0,'C',1);
		$pdf->Cell($d,20,'',1,0,'C',1);
		$pdf->Cell($d,20,'',1,0,'C',1);
		$pdf->Cell($d,20,'',1,0,'C',1);
		$pdf->Cell(0,20,'',1,0,'C',1);

		$pdf->SetFont('Times','',6);
		$pdf->SetX($x);
		$pdf->Cell($d,7,'HORA ENTRADA / HORA SALIDA',0,0,'C',0);
		$pdf->Cell($d,7,'HORA ENTRADA / HORA SALIDA',0,0,'C',0);
		$pdf->Cell($d,7,'HORA ENTRADA / HORA SALIDA',0,0,'C',0);
		$pdf->Cell($d,7,'HORA ENTRADA / HORA SALIDA',0,0,'C',0);
		$pdf->Cell(0,7,'HORA ENTRADA / HORA SALIDA',0,0,'C',0);
		$pdf->Ln(9);

		$pdf->SetX($x);
		$pdf->Cell($d,7,'FIRMA',0,0,'C',0);
		$pdf->Cell($d,7,'FIRMA',0,0,'C',0);
		$pdf->Cell($d,7,'FIRMA',0,0,'C',0);
		$pdf->Cell($d,7,'FIRMA',0,0,'C',0);
		$pdf->Cell(0,7,'FIRMA',0,0,'C',0);
		$pdf->Ln(5);

		$pdf->Ln(5.5);
		//-----------
		$i++;
		}

	$pdf->SetFont('Times','B',12);
	$pdf->SetFillColor(230);
	//-----------
	$fecha1 += 86400*7;
	$fecha = sube_dia(sube_dia(sube_dia(sube_dia(sube_dia(sube_dia(sube_dia($fecha)))))));
	}	
$pdf->Output();
?>