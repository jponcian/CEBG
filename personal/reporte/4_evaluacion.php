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
	
class PDF extends FPDF
{
	function Header()
	{
	}
	
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-10);
		$this->SetTextColor(120);
		//--------------
//		$this->Cell(0,0,'SIACEBG',0,0,'R');
		$this->Cell(0,0,'SIACEBG '.$this->PageNo().' de {nb}',0,0,'R');
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
$pdf=new PDF('P','mm','LEGAL');
$pdf->AliasNbPages();
$pdf->SetMargins(15,5,15);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Evaluación Personal');

// ----------
$pdf->AddPage();
//$pdf->Image('../../images/logo_nuevo.jpg',35,14,25);
$pdf->SetTextColor(0);
$pdf->SetFillColor(240);

//-----------------
$id = decriptar($_GET['id']);
$cedula = decriptar($_GET['ci']);
//-----------------
$consultx1 = "SELECT ci_director, ci_jefe_area, ci_coordinador, fecha FROM eval_asignacion WHERE eval_asignacion.id_evaluacion = $id AND cedula = '$cedula';";
$tablx1 = $_SESSION['conexionsql']->query($consultx1);
$registro1 = $tablx1->fetch_object();
$ci_director = $registro1->ci_director;
$ci_coordinador = $registro1->ci_coordinador;
$ci_jefe_area = $registro1->ci_jefe_area;
$fecha = $registro1->fecha;
//----------------- DATOS DEL EVALUADO
$consultx = "SELECT *, nombre as  nombres FROM rac_historial WHERE cedula = '$cedula' AND fecha <= '$fecha' ORDER BY fecha DESC LIMIT 1;";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx); 
$registro = $tablx->fetch_object();
//$id_area = $registro->id_area;
if ($ci_coordinador>0)
	{
	//----------------- DATOS DEL COORDINADOR GENERAL
	$consultx = "SELECT nombre as nombres, cedula, cargo FROM rac_historial WHERE cedula = '$ci_coordinador' AND fecha <= '$fecha' ORDER BY fecha DESC LIMIT 1;"; // echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro0 = $tablx->fetch_object();
	}
else
	{
	//----------------- DATOS DEL JEFE DE AREA
	$consultx = "SELECT nombre as nombres, cedula, cargo FROM rac_historial WHERE cedula = '$ci_jefe_area' AND fecha <= '$fecha' ORDER BY fecha DESC LIMIT 1;"; // echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro0 = $tablx->fetch_object();
	}
//----------------- DATOS DEL DIRECTOR
$consultx = "SELECT nombre as nombres, cedula, cargo FROM rac_historial WHERE cedula = '$ci_director' AND fecha <= '$fecha' ORDER BY fecha DESC LIMIT 1;"; // echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro1 = $tablx->fetch_object();

$alto1 = 5;

$pdf->SetFont('Times','B',12);
$pdf->Cell(0,$alto1,'CONTRALORÍA DEL ESTADO BOLIVARIANO DE GUÁRICO',0,0,'C',0);
$pdf->Ln();
$pdf->Cell(0,$alto1,'FORMA EODI-08-03',0,0,'C',0);
$pdf->Ln();
$pdf->SetFont('Times','B',9);
$pdf->Cell(0,$alto1,'SECCIÓN “A”  DATOS DE IDENTIFICACIÓN',0,0,'C',0);
$pdf->Ln();

$pdf->SetFont('Times','B',10);
$pdf->Cell(0,$alto1,'DATOS DEL EVALUADO',1,0,'L',1);
$pdf->Ln();

$pdf->SetFont('Times','',9);
$pdf->Cell($a=140,$alto1,'     APELLIDOS Y NOMBRES:',1,0,'L',0);
$pdf->Cell(0,$alto1,'     CED. IDENTIDAD:',1,0,'L',0);
$pdf->Ln();
$pdf->SetFont('Times','B',10);
$pdf->Cell($a,$alto1,($registro->nombres),1,0,'L',0);
$pdf->Cell(0,$alto1,($registro->cedula),1,0,'C',0);
$pdf->Ln();
$pdf->SetFont('Times','',10);
$pdf->Cell($a=140,$alto1,'     CARGO:',1,0,'L',0);
$pdf->Cell(0,$alto1,'     CÓDIGO:',1,0,'L',0);
$pdf->Ln();
$pdf->SetFont('Times','B',10);
$pdf->Cell($a,$alto1,($registro->cargo),1,0,'L',0);
$pdf->Cell(0,$alto1,($registro->codigo),1,0,'C',0);
$pdf->Ln();
$pdf->SetFont('Times','',10);
$pdf->Cell(0,$alto1,'     UBICACIÓN  ADMINISTRATIVA COMPLETA:',1,0,'L',0);
$pdf->Ln();
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,$alto1,($registro->ubicacion),1,0,'L',0);
$pdf->Ln();

$pdf->SetFont('Times','B',10);
$pdf->Cell(0,$alto1,'DATOS DEL SUPERVISOR EVALUADOR',1,0,'L',1);
$pdf->Ln();

$pdf->SetFont('Times','',9);
$pdf->Cell($a=140,$alto1,'     APELLIDOS Y NOMBRES:',1,0,'L',0);
$pdf->Cell(0,$alto1,'C.I.:',1,0,'L',0);
$pdf->Ln();
$pdf->SetFont('Times','B',9);
$pdf->Cell($a,$alto1,($registro0->nombres),1,0,'L',0);
$pdf->Cell(0,$alto1,($registro0->cedula),1,0,'C',0);
$pdf->Ln(); 

$pdf->SetFont('Times','',9);
$pdf->Cell($a=100,$alto1,'     CARGO:',1,0,'L',0);
$pdf->Cell($b=40,$alto1,'     FIRMA:',1,0,'L',0);
$pdf->Cell(0,$alto1,'     FECHA:',1,0,'L',0);
$pdf->Ln();
$pdf->SetFont('Times','B',9);
$pdf->Cell($a,$alto1,($registro0->cargo),1,0,'L',0);
$pdf->Cell($b,$alto1,'',1,0,'L',0);
$pdf->Cell(0,$alto1,voltea_fecha($fecha),1,0,'C',0);
$pdf->Ln();

$pdf->SetFont('Times','B',10);
$pdf->Cell(0,$alto1,'APROBADO POR:  (NIVEL  INMEDIATO SUPERIOR)',1,0,'L',1);
$pdf->Ln();

$pdf->SetFont('Times','',9);
$pdf->Cell($a=140,$alto1,'     APELLIDOS Y NOMBRES:',1,0,'L',0);
$pdf->Cell(0,$alto1,'C.I.:',1,0,'L',0);
$pdf->Ln();
$pdf->SetFont('Times','B',9);
$pdf->Cell($a,$alto1,($registro1->nombres),1,0,'L',0);
$pdf->Cell(0,$alto1,($registro1->cedula),1,0,'C',0);
$pdf->Ln(); 

$pdf->SetFont('Times','',9);
$pdf->Cell($a=100,$alto1,'     CARGO:',1,0,'L',0);
$pdf->Cell($b=40,$alto1,'     FIRMA:',1,0,'L',0);
$pdf->Cell(0,$alto1,'     FECHA:',1,0,'L',0);
$pdf->Ln();
$pdf->SetFont('Times','B',9);
$pdf->Cell($a,$alto1,($registro1->cargo),1,0,'L',0);
$pdf->Cell($b,$alto1,'',1,0,'L',0);
$pdf->Cell(0,$alto1,voltea_fecha($fecha),1,0,'C',0);
$pdf->Ln();

$pdf->SetFillColor(0);
$pdf->Cell(0,$alto1/2,'',1,0,'L',1);
$pdf->SetFillColor(240);
$pdf->Ln();

$pdf->Cell(0,$alto1,'SECCIÓN “B” EVALUACIÓN DE LOS OBJETIVOS DE DESEMPEÑO',0,0,'C',0);
$pdf->Ln();

$alto1 = $alto1 + 4;
$pdf->SetFont('Times','B',9);
$pdf->Cell($a=8,$alto1,'N°',1,0,'C',0);
$pdf->Cell($b=110,$alto1,'Objetivo de Desarrollo Individual',1,0,'C',0);
$pdf->Cell($c=10,$alto1,'Peso',1,0,'C',0);
$x = $pdf->GetX();
$y = $pdf->GetY()+$alto1/2;
$pdf->Cell(40,$alto1/2,'Rangos',1,0,'C',0);
$pdf->SetXY($x,$y);
$pdf->Cell($d=8,$alto1/2,'1',1,0,'C',0);
$pdf->Cell($d,$alto1/2,'2',1,0,'C',0);
$pdf->Cell($d,$alto1/2,'3',1,0,'C',0);
$pdf->Cell($d,$alto1/2,'4',1,0,'C',0);
$pdf->Cell($d,$alto1/2,'5',1,0,'C',0);
$x = $pdf->GetX();
$pdf->SetXY($x,$y-$alto1/2);
$pdf->MultiCell(0,$alto1/2,'Peso por Rango',1,'C');

$alto1 = $alto1 - 4;
$pdf->SetFont('Times','',9);

$consultx1 = "SELECT eval_asignacion.id, eval_asignacion.id_evaluacion,	eval_asignacion.id_odi,	eval_asignacion.fecha_evaluados,	eval_asignacion.peso, eval_asignacion.puntaje, eval_asignacion.total, eval_odis.descripcion FROM eval_asignacion, eval_odis WHERE	eval_asignacion.id_odi = eval_odis.id AND eval_asignacion.id_evaluacion = $id AND eval_asignacion.estatus >= 7 AND eval_asignacion.cedula = '$cedula';";  //echo $consultx;
$tablx1 = $_SESSION['conexionsql']->query($consultx1);
while ($registro1 = $tablx1->fetch_object())
	{
	$i++;
	if ($registro1->puntaje==1)	{ $var1='X'; } else { $var1=''; }
	if ($registro1->puntaje==2)	{ $var2='X'; } else { $var2=''; }
	if ($registro1->puntaje==3)	{ $var3='X'; } else { $var3=''; }
	if ($registro1->puntaje==4)	{ $var4='X'; } else { $var4=''; }
	if ($registro1->puntaje==5)	{ $var5='X'; } else { $var5=''; }
	//----- PARA ARRANCAR CON LA LINEA
	$y1=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->SetX($x+8);
	//-----------------------------------------MULTICELL
	$pdf->SetFont('Times','',8);
	$pdf->MultiCell(110,4, (($registro1->descripcion)),1,'J');
	$y2=$pdf->GetY();
	//- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
	$pdf->SetY($y1);
	$pdf->SetX($x);
	$alto2 = $y2 - $y1;
	$pdf->SetFont('Times','',9);
	//-------------------
	$pdf->Cell($a=8,$alto2,$i,1,0,'C',0);
	$pdf->Cell($b=110,$alto2,'',1,0,'C',0);
	$pdf->Cell($c=10,$alto2,$registro1->peso,1,0,'C',0);
	$pdf->Cell($d=8,$alto2,$var1,1,0,'C',0);
	$pdf->Cell($d,$alto2,$var2,1,0,'C',0);
	$pdf->Cell($d,$alto2,$var3,1,0,'C',0);
	$pdf->Cell($d,$alto2,$var4,1,0,'C',0);
	$pdf->Cell($d,$alto2,$var5,1,0,'C',0);
	$pdf->Cell(0,$alto2,$registro1->total,1,0,'C',0);
	$pdf->Ln();
	$totalB += $registro1->total;
	}
$pdf->SetFont('Times','B',9);
$pdf->Cell($a+$b+$c+$d*5,5,'TOTAL SECCIÓN “B”',1,0,'R',0);
$pdf->Cell(0,5,$totalB,1,0,'C',0);
$pdf->Ln();

$pdf->SetFillColor(0);
$pdf->Cell(0,$alto1/3,'',1,0,'L',1);
$pdf->SetFillColor(240);
$pdf->Ln();

$pdf->SetFont('Times','B',9);
$pdf->Cell(0,$alto1,'SECCIÓN “C” EVALUACIÓN DE LAS COMPETENCIAS',0,0,'C',0);
$pdf->Ln();

$alto1 = $alto1 + 4;
$pdf->SetFont('Times','B',9);
$pdf->Cell($a=8,$alto1,'N°',1,0,'C',0);
$pdf->Cell($b=110,$alto1,'Competencias',1,0,'C',0);
$pdf->Cell($c=10,$alto1,'Peso',1,0,'C',0);
$x = $pdf->GetX();
$y = $pdf->GetY()+$alto1/2;
$pdf->Cell(40,$alto1/2,'Rangos',1,0,'C',0);
$pdf->SetXY($x,$y);
$pdf->Cell($d=8,$alto1/2,'1',1,0,'C',0);
$pdf->Cell($d,$alto1/2,'2',1,0,'C',0);
$pdf->Cell($d,$alto1/2,'3',1,0,'C',0);
$pdf->Cell($d,$alto1/2,'4',1,0,'C',0);
$pdf->Cell($d,$alto1/2,'5',1,0,'C',0);
$x = $pdf->GetX();
$pdf->SetXY($x,$y-$alto1/2);
$pdf->MultiCell(0,$alto1/2,'Peso por Rango',1,'C');

$alto1 = $alto1 - 4;
$pdf->SetFont('Times','',9);
$i=0;

$consultx1 = "SELECT eval_asignacion_comp.id, eval_asignacion_comp.id_evaluacion,	eval_asignacion_comp.id_comp,	eval_asignacion_comp.fecha_evaluados,	eval_asignacion_comp.peso, eval_asignacion_comp.puntaje, eval_asignacion_comp.total, eval_competencias.descripcion FROM eval_asignacion_comp, eval_competencias WHERE	eval_asignacion_comp.id_comp = eval_competencias.id AND eval_asignacion_comp.id_evaluacion = $id AND eval_asignacion_comp.estatus >= 7 AND eval_asignacion_comp.cedula = '$cedula';";  //echo $consultx;
$tablx1 = $_SESSION['conexionsql']->query($consultx1);
while ($registro1 = $tablx1->fetch_object())
	{
	$i++;
	if ($registro1->puntaje==1)	{ $var1='X'; } else { $var1=''; }
	if ($registro1->puntaje==2)	{ $var2='X'; } else { $var2=''; }
	if ($registro1->puntaje==3)	{ $var3='X'; } else { $var3=''; }
	if ($registro1->puntaje==4)	{ $var4='X'; } else { $var4=''; }
	if ($registro1->puntaje==5)	{ $var5='X'; } else { $var5=''; }
	//----- PARA ARRANCAR CON LA LINEA
	$y1=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->SetX($x+8);
	//-----------------------------------------MULTICELL
	$pdf->SetFont('Times','',8);
	$pdf->MultiCell(110,4, (($registro1->descripcion)),1,'J');
	$y2=$pdf->GetY();
	//- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
	$pdf->SetY($y1);
	$pdf->SetX($x);
	$alto2 = $y2 - $y1;
	$pdf->SetFont('Times','',9);
	//-------------------
	$pdf->Cell($a=8,$alto2,$i,1,0,'C',0);
	$pdf->Cell($b=110,$alto2,'',1,0,'C',0);
	$pdf->Cell($c=10,$alto2,$registro1->peso,1,0,'C',0);
	$pdf->Cell($d=8,$alto2,$var1,1,0,'C',0);
	$pdf->Cell($d,$alto2,$var2,1,0,'C',0);
	$pdf->Cell($d,$alto2,$var3,1,0,'C',0);
	$pdf->Cell($d,$alto2,$var4,1,0,'C',0);
	$pdf->Cell($d,$alto2,$var5,1,0,'C',0);
	$pdf->Cell(0,$alto2,$registro1->total,1,0,'C',0);
	$pdf->Ln();
	$totalC += $registro1->total;
	}

$pdf->SetFont('Times','B',9);
$pdf->Cell($a+$b+$c+$d*5,5,'TOTAL SECCIÓN “C”',1,0,'R',0);
$pdf->Cell(0,5,$totalC,1,0,'C',0);
$pdf->Ln();

$pdf->SetFillColor(0);
$pdf->Cell(0,$alto1/3,'',1,0,'L',1);
$pdf->SetFillColor(240);
$pdf->Ln();

//------------ PARA UNA PAGINA NUEVA
if ($pdf->GetY()>270)	{	$pdf->AddPage();	$pdf->SetY(20);	}

$pdf->SetFont('Times','B',9);
$pdf->Cell(0,$alto1,'SECCIÓN "D" RESULTADOS DE LA EVALUACIÓN',0,0,'C',0);
$pdf->Ln();

$alto1 = $alto1 + 4;
$pdf->SetFont('Times','B',12);
$pdf->Cell($b=110,$alto1,'Resumen de la Calificación  Obtenida',1,0,'C',0);
$x = $pdf->GetX();
$y = $pdf->GetY()+$alto1/2;
$pdf->SetFont('Times','B',9);
$pdf->Cell(39,$alto1/2,'SECCIÓN “B”',1,0,'C',0);
$pdf->Cell(0,$alto1/2,'SECCIÓN “C”',1,0,'C',0);
$pdf->SetXY($x,$y);
$pdf->Cell(39,$alto1/2,$totalB,1,0,'C',0);
$pdf->Cell(0,$alto1/2,$totalC,1,0,'C',0);
$x = $pdf->GetX();
$pdf->Ln();

//$alto1 = $alto1 + 6;
$pdf->SetFont('Times','B',11);
$pdf->Cell($b,$alto1,'Calificación Total',1,0,'C',0);
$pdf->SetFont('Times','B',15);
$pdf->Cell(0,$alto1,$totalB + $totalC,1,0,'C',0);
$pdf->Ln();

$pdf->SetFont('Times','B',11);
$pdf->Cell($b,$alto1,'Rango de Actuación',1,0,'C',0);
$pdf->SetFont('Times','B',13);
$pdf->Cell(0,$alto1,evaluacion($totalB + $totalC),1,0,'C',0);
$pdf->Ln();

$alto1 = $alto1 - 4;
$pdf->SetFont('Times','B',9);
$pdf->Cell(93,$alto1,'Firma del Superior Inmediato',1,0,'C',0);
$pdf->Cell(0,$alto1,'Firma del Funcionario (a) Evaluado (a)',1,0,'C',0);
$pdf->Ln();

$pdf->Cell(93,$alto1*3,'',1,0,'C',0);
$pdf->Cell(0,$alto1*2,'',1,0,'C',0);
$pdf->Ln();

$pdf->Cell(93,$alto1,'');
$pdf->Cell(0,$alto1,'Fecha:',1,0,'L',0);
$pdf->Ln();

$pdf->Ln(1);
$pdf->Cell(93*1.3,$alto1,'');
$pdf->Cell(15,$alto1,'Si',1,0,'C',0);
$pdf->Cell(15,$alto1,'No',1,0,'C',0);
$pdf->Ln();

$pdf->Cell(93*1.3,$alto1,'¿Esta Usted de Acuerdo con el Resultado de la Evaluación?',0,0,'C',0);
$pdf->Cell(15,$alto1,' ',1,0,'C',0);
$pdf->Cell(15,$alto1,' ',1,0,'C',0);
$pdf->Ln();
$pdf->Ln(1);

$pdf->Cell(0,$alto1*1.4,'Observaciones:',1,0,'L',0);



$pdf->Output();
?>