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
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class CellPDF extends FPDF
{
	function Header()
	{
		$tipo = decriptar($_GET['tipo']);
		if ($tipo==0) { $tipo ="(TODOS)"; } 
			elseif ($tipo==1) { $tipo ="(RETARDADOS)"; } 
			elseif ($tipo==2) { $tipo ="(HORARIO CORRECTO)"; } 
		
	$this->SetFillColor(2, 117, 216);
	$this->Image('../../images/logo_nuevo.jpg',30,10,35);
	//$this->Image('../../images/escudo.jpg',30,12,28);
	//$this->Image('../../images/logo_web.png',100,80,100);
	$this->SetFont('Times','B',13);
	$x=$this->GetX();
	$y=$this->GetY();
	$this->SetXY(200,25);
	$this->Cell(0,7,$_SESSION['titulo'],0,0,'R',0);
	$this->SetXY($x,$y);
	// ---------------------
	//$this->SetY(12);
	//$instituto = instituto();
	$this->SetFont('Times','I',11.5);
	$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Dirección de Despacho',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'División de Seguridad',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Rif G-20001287-0',0,0,'C'); 
	$this->Ln(8);
	
	$this->SetFont('Times','B',11);
	$this->Cell(0,5,"RELACIÓN DE ASISTENCIA DIARIA $tipo",0,0,'C'); 
	$this->Ln(7);
	
	$this->SetTextColor(255);
	$this->SetFont('Times','B',10.5);
	$this->Cell($aa=9,7,'Item',1,0,'C',1);
	$this->Cell($a=20,7,'Cedula',1,0,'C',1);
	$this->Cell($b=55,7,'Empleado',1,0,'C',1);
	$this->Cell($c=60,7,'Cargo',1,0,'C',1);
	$this->Cell($d=25,7,'Ingreso',1,0,'C',1);
	$this->Cell($d,7,'Salida',1,0,'C',1);
	$this->Cell($d,7,'Ingreso',1,0,'C',1);
	$this->Cell(0,7,'Salida',1,1,'C',1);
	}
	
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-18);
		$this->SetTextColor(120);
		//$this->Cell(0,5,'Resolución '.($_GET['id']));
		//--------------
		$s=$this->PageNo();
		while ($s>5)
		{	$s=$s-5;	}
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
$pdf->SetTitle('Relacion de Asistencia Diaria');

$fecha1 = fecha_a_numero(voltea_fecha($_GET['desde']));
$hasta1 = fecha_a_numero(voltea_fecha($_GET['hasta']));
$fecha = voltea_fecha($_GET['desde']);
$hasta = voltea_fecha($_GET['hasta']);
$tipo = decriptar($_GET['tipo']);
$cedula = ($_GET['cedula']);
$direccion = decriptar($_GET['direccion']);

if ($direccion==0) {}
	else { 	if ($cedula==0) {	$filtro = ' AND rac_historial.id_div='.$direccion;	}	else {	$filtro = ' AND rac_historial.cedula='.$cedula;	}	}
// 1 RETARDADOS 2 CORRECTO 0 TODOS.

while ($fecha1<=$hasta1)
{ 
if ($cedula==0)
	{
	$_SESSION['titulo']="FECHA ".voltea_fecha($fecha);

	$consult = "SELECT rac_historial.*, a_direcciones.direccion FROM rac_historial, a_direcciones WHERE a_direcciones.id=rac_historial.id_div AND rac_historial.fecha = '$fecha' $filtro ORDER BY rac_historial.id_div ASC, rac_historial.jefe_division DESC,  rac_historial.cedula+0 ASC"; // WHERE id_direccion='$desde'

	//echo $consult;

	if ($tipo==0) {} 
		elseif ($tipo==1) {	$consult = "SELECT rac_historial.*, a_direcciones.direccion FROM	rac_historial, asistencia_diaria, a_direcciones WHERE rac_historial.fecha = asistencia_diaria.fecha AND rac_historial.cedula = asistencia_diaria.cedula AND a_direcciones.id = rac_historial.id_div 	AND rac_historial.fecha = '$fecha' $filtro AND asistencia_diaria.estatus > 0 GROUP BY rac_historial.cedula ORDER BY rac_historial.id_div ASC,	rac_historial.jefe_division DESC, rac_historial.cedula + 0 ASC";	}
		elseif ($tipo==2) {	$consult = "SELECT rac_historial.*, a_direcciones.direccion FROM	rac_historial, asistencia_diaria, a_direcciones WHERE rac_historial.fecha = asistencia_diaria.fecha AND rac_historial.cedula = asistencia_diaria.cedula AND a_direcciones.id = rac_historial.id_div 	AND rac_historial.fecha = '$fecha' $filtro AND asistencia_diaria.estatus = 0 GROUP BY rac_historial.cedula ORDER BY rac_historial.id_div ASC,	rac_historial.jefe_division DESC, rac_historial.cedula + 0 ASC";	}
	}
else
	{
	$_SESSION['titulo']=" DEL ".voltea_fecha($fecha).' AL '.voltea_fecha($hasta);

	$consult = "SELECT rac_historial.*, a_direcciones.direccion FROM rac_historial, a_direcciones WHERE a_direcciones.id=rac_historial.id_div AND rac_historial.fecha >= '$fecha' AND rac_historial.fecha <= '$hasta' $filtro ORDER BY rac_historial.id_div ASC, rac_historial.jefe_division DESC,  rac_historial.cedula+0 ASC"; // WHERE id_direccion='$desde'

	//echo $consult;

	if ($tipo==0) {} 
		elseif ($tipo==1) {	$consult = "SELECT rac_historial.*, a_direcciones.direccion FROM	rac_historial, asistencia_diaria, a_direcciones WHERE rac_historial.fecha = asistencia_diaria.fecha AND rac_historial.cedula = asistencia_diaria.cedula AND a_direcciones.id = rac_historial.id_div 	AND rac_historial.fecha >= '$fecha' AND rac_historial.fecha <= '$hasta' $filtro AND asistencia_diaria.estatus > 0 GROUP BY rac_historial.fecha ORDER BY rac_historial.id_div ASC,	rac_historial.jefe_division DESC, rac_historial.cedula + 0 ASC";	}
		elseif ($tipo==2) {	$consult = "SELECT rac_historial.*, a_direcciones.direccion FROM	rac_historial, asistencia_diaria, a_direcciones WHERE rac_historial.fecha = asistencia_diaria.fecha AND rac_historial.cedula = asistencia_diaria.cedula AND a_direcciones.id = rac_historial.id_div 	AND rac_historial.fecha >= '$fecha' AND rac_historial.fecha <= '$hasta' $filtro AND asistencia_diaria.estatus = 0 GROUP BY rac_historial.fecha ORDER BY rac_historial.id_div ASC,	rac_historial.jefe_division DESC, rac_historial.cedula + 0 ASC";	}
	
	$fecha1 = ($hasta1);
	}

	// ----------
$pdf->AddPage();

$aa=9;
$a=20;
$b=55;
$c=60;
$d=25;

$pdf->SetFont('Times','',9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$i=0;
$nomina = '';
$direccion = '';
//-----------------

$tabla = $_SESSION['conexionsql']->query($consult);
//-----------------
$i=0; $monto=0;
while ($registro = $tabla->fetch_object())
	{
//	if ($nomina<>$registro->nomina)
//		{	
//		$pdf->SetFont('Times','B',9);
//		$pdf->SetFillColor(240, 173, 78);
//		$pdf->Cell(0,5.5,'				'.$registro->nomina,1,1,'L',1);	
//		$nomina = $registro->nomina ;
//		}
	if ($direccion<>$registro->id_div)
		{	
		$pdf->SetFont('Times','I',9);
		$pdf->SetFillColor(91, 192, 222);
		$pdf->Cell(0,5.5,'				'.$registro->direccion,1,1,'L',1);	
		$direccion = $registro->id_div ;
		}
	//----------
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(250);}
	//----------
	$pdf->SetFont('Times','',9);
	$pdf->Cell($aa,5.5,$i+1,1,0,'C',1);
	$pdf->Cell($a,5.5,$registro->cedula,1,0,'C',1);
	$pdf->SetFont('Times','',8);
	$pdf->Cell($b,5.5,substr($registro->nombre,0,50),1,0,'L',1);
	$pdf->Cell($c,5.5,$registro->cargo,1,0,'L',1);
	$pdf->SetFont('Times','',9);
	$pdf->Cell($d,5.5,hora_militar($registro->entrada1),1,0,'C',1);
	$pdf->Cell($d,5.5,hora_militar($registro->salida1),1,0,'C',1);
	$pdf->Cell($d,5.5,hora_militar($registro->entrada2),1,0,'C',1);
	$pdf->Cell($e,5.5,hora_militar($registro->salida2),1,0,'C',1);

	$pdf->Ln(5.5);
	$monto = $monto + $registro->sueldo;
	//-----------
	$i++;
	}

$pdf->SetFont('Times','B',12);
$pdf->SetFillColor(230);
//$pdf->Cell($aa+$a+$b+$c+$d+$d1,6,'TOTAL =>',1,0,'R',1);
//$pdf->Cell(0,6,'TOTAL => '.formato_moneda($monto),1,0,'R',1);
//-----------
$fecha1 += 86400;
$fecha = sube_dia($fecha);
}
$pdf->Output();
?>