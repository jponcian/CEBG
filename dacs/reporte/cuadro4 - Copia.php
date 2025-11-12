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
$pdf=new CellPDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,20,17);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Relacion de Ciudadanos Atendidos');

$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);

// ----------
$pdf->AddPage();
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);

//--------------
function cantidad($tipo, $fecha1, $fecha2)
	{
	// ------------
	$consultax = "SELECT id_tickets, id_atencion FROM dacs_atencion, dacs_atencion_gestion	WHERE dacs_atencion.id = dacs_atencion_gestion.id_tickets AND dacs_atencion.fecha >= '$fecha1' AND  dacs_atencion.fecha <= '$fecha2' AND  dacs_atencion_gestion.id_atencion IN ('$tipo');";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	//-----------
	return $tablax->num_rows;	
	}
//-----------------

$pdf->SetFont('Times','BU',10);
$pdf->Cell(0,5.5,'1.	ESTADISTICA QUE  ARROJO PARA EL INFORME MENSUAL',0,0,'L',1);
$pdf->Ln(10);
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5.5,'Cuadro N° 04',0,0,'C',1);
$pdf->Ln(6);
$pdf->Cell(0,5.5,"Ciudadanos Atendidos desde el ".voltea_fecha($fecha1)." al ".voltea_fecha($fecha2).".",0,0,'C',1);
$pdf->Ln(7);

$pdf->SetFont('Times','B',10);
$pdf->Cell($a=50,5.5,'MOTIVO',1,0,'C',1);
$pdf->Cell($b=100,5.5,'TIPO DE ATENCIÓN',1,0,'C',1);
$pdf->Cell(0,5.5,'Nº CIUDADANOS',1,0,'C',1);
$pdf->Ln();

$x=$pdf->GetX();
$y=$pdf->GetY();
$yo=$pdf->GetY();
$pdf->MultiCell($a,80,'Solicitud de Información',1,'C');

$pdf->SetXY($x+$a,$y);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,8,'Para la presentación de la Declaración Jurada de Patrimonio: Se indican datos necesarios para realizar ingreso o cese, según sea el caso.',1,'J');

$pdf->SetXY($x,$y+24);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,8,'Para el manejo del Sistema de Registro de Órganos y Entes (SISROE): Se indican orientaciones para el correcto manejo del mismo.',1,'J');

$pdf->SetXY($x,$y+24);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,8,'Asesoría de Denuncia.',1,'J');

$pdf->SetXY($x,$y+8);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,8,'Asesorías Telefónicas: Llamadas telefónicas Realizadas y Recibidas: Relacionadas a la DJP, SISROE Y C.G.R.',1,'J');

$pdf->SetXY($x,$y+16);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,8,'Sub-Total',1,'R');

$pdf->SetXY($x+$b,$yo);
$x=$pdf->GetX();
$y=$pdf->GetY();
$txt=(cantidad(1, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->MultiCell(0,24,$txt,1,'C');

$pdf->SetXY($x,$yo+24);
$x=$pdf->GetX();
$y=$pdf->GetY();
$txt=(cantidad(2, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->MultiCell(0,24,$txt,1,'C');

$pdf->SetXY($x,$yo+48);
$x=$pdf->GetX();
$y=$pdf->GetY();
$txt=(cantidad(3, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->MultiCell(0,8,$txt,1,'C');

$pdf->SetXY($x,$yo+56);
$x=$pdf->GetX();
$y=$pdf->GetY();
$txt=(cantidad(4, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->MultiCell(0,16,$txt,1,'C');

$pdf->SetXY($x,$yo+72);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell(0,8,$subtotal,1,'C'); $total += $subtotal;

$x=$pdf->GetX();
$y=$pdf->GetY();
$yo=$pdf->GetY();
$pdf->MultiCell($a,8,'Presentación de la Declaración Jurada de Patrimonio',1,'C');

$pdf->SetXY($x+$a,$y);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,8,'Por ingreso a la función pública.',1,'J');

$pdf->SetXY($x,$y+8);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,8,'Por cese a la función pública.',1,'J');

$subtotal = 0;
$pdf->SetXY($x+$b,$yo);
$x=$pdf->GetX();
$y=$pdf->GetY();
$txt=(cantidad(5, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->MultiCell(0,8,$txt,1,'C');

$pdf->SetXY($x,$yo+8);
$x=$pdf->GetX();
$y=$pdf->GetY();
$txt=(cantidad(6, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->MultiCell(0,8,$txt,1,'C');

$pdf->Cell($a+$b,8,'Presentación de la Declaración Jurada de Sobrevivencia',1,0,'R',1);
$txt=(cantidad(7, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->Cell(0,8,$txt,1,0,'C',1);
$pdf->Ln();

$pdf->Cell($a+$b,8,'Presentación de la Declaración por Actualización Procesada',1,0,'R',1);
$txt=(cantidad(8, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->Cell(0,8,$txt,1,0,'C',1);
$pdf->Ln();

$pdf->Cell($a+$b,8,'Presentación de la Declaración por Actualización no Procesada',1,0,'R',1);
$txt=(cantidad(9, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->Cell(0,8,$txt,1,0,'C',1);
$pdf->Ln();

$pdf->Cell($a+$b,8,'Denuncia',1,0,'R',1);
$txt=(cantidad(10, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->Cell(0,8,$txt,1,0,'C',1);
$pdf->Ln();

$pdf->Cell($a+$b,8,'Sub-Total',1,0,'R',1);
$pdf->Cell(0,8,$subtotal,1,0,'C',1); $total += $subtotal;
$pdf->Ln();

$x=$pdf->GetX();
$y=$pdf->GetY();
$yo=$pdf->GetY();
$pdf->MultiCell($a,8,'Impedimento para ingresar al sistema de Declaración Jurada de Patrimonio',1,'C');

$pdf->SetXY($x+$a,$y);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,6,'Elaboración del Reporte de Novedades y remisión del mismo a la CGR (vía correo electrónico) para la obtención de una nueva clave y usuario por extravío de los correos electrónico y/o bloqueo de usuarios.',1,'J');

$subtotal = 0;
$pdf->SetXY($x+$b,$yo);
$x=$pdf->GetX();
$y=$pdf->GetY();
$txt=(cantidad(11, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->MultiCell(0,24,$txt,1,'C',1);

$pdf->Cell($a+$b,8,'Sub-Total',1,0,'R',1);
$pdf->Cell(0,8,$subtotal,1,0,'C',1); $total += $subtotal;
$pdf->Ln();

$x=$pdf->GetX();
$y=$pdf->GetY();
$yo=$pdf->GetY();
$pdf->MultiCell($a,16,'',1,'C');

$pdf->SetXY($x+$a,$y);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,8,'Solicitud de Capacitación',1,'J');

$pdf->SetXY($x,$y+8);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell($b,8,'Solicitud Cambio de Teléfono Jefe O.A.C. Contraloría Municipal de Roscio.',1,'J');

$subtotal = 0;
$pdf->SetXY($x+$b,$yo);
$x=$pdf->GetX();
$y=$pdf->GetY();
$txt=(cantidad(12, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->MultiCell(0,8,$txt,1,'C',1);

$pdf->SetXY($x,$yo+8);
$x=$pdf->GetX();
$y=$pdf->GetY();
$txt=(cantidad(13, $fecha1, $fecha2)); $subtotal+=$txt;
$pdf->MultiCell(0,8,$txt,1,'C',1);

$pdf->Cell($a+$b,8,'Sub-Total',1,0,'R',1);
$pdf->Cell(0,8,$subtotal,1,0,'C',1); $total += $subtotal;
$pdf->Ln();

$pdf->Cell($a+$b,8,'TOTAL CIUDADANOS ATENDIDOS',1,0,'R',1);
$pdf->Cell(0,8,$total,1,0,'C',1);
$pdf->Ln(12);

$pdf->Cell($a+$b,8,'Fuente: Dirección de Atención al Ciudadano y Control Social.',0,0,'L',1);
$pdf->Ln();

//----------

$pdf->Output();
?>