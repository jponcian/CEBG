<?php
session_start();
ob_end_clean();
session_start();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

//if ($_SESSION['VERIFICADO'] != "SI") { 
//    header ("Location: ../index.php?errorusuario=val"); 
//    exit(); 
//	}

if ($_GET['id']<>'0')
	{	$_SESSION['id_ct'] = decriptar($_GET['id']);	}
else
	{	$_SESSION['id_ct'] = $_POST['id'];	}

class CellPDF extends FPDF
{
	function Header()
	{}
	
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-18);
		$this->SetTextColor(120);
		$this->Cell(0,0,'SIACEBG',0,0,'R');
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
$pdf->SetMargins(20,20,20);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Solicitud'); 
$linea = 7;

////////// DATOS
$consulta = "SELECT rrhh_permisos.tipo as tip, rrhh_permisos.*, rac.* FROM rrhh_permisos, rac WHERE rrhh_permisos.cedula = rac.cedula AND id = ".$_SESSION['id_ct'].";"; 
$tabla = $_SESSION['conexionsql']->query($consulta);
$registro = $tabla->fetch_object();

// --------------
$tipo = $registro->tip;
$cedula = ($registro->ci);
$empleado = $registro->nombre." ".$registro->nombre2." ".$registro->apellido." ".$registro->apellido2;$profesion = trim($registro->profesion);
$cargo = trim($registro->cargo);
$ubicacion = $registro->ubicacion;
$fecha = voltea_fecha($registro->fecha);
$sueldo = $registro->sueldo;
$descripcion = $registro->descripcion;
$soporte = $registro->soporte;
$periodo = $registro->periodo;
$desde = voltea_fecha($registro->desde);
$hora1 = ($registro->hora1);
$hasta = voltea_fecha($registro->hasta);
$hora2 = ($registro->hora2);
$incorporacion = voltea_fecha($registro->incorporacion);
$hora3 = ($registro->hora3);
$habiles = $registro->habiles;
$horas = $registro->horas;
$calendario = $registro->calendario;
$jefe = $registro->jefe;
$jefe_cargo = $registro->jefe_cargo;

$code = generarRuta('17','amfm',substr($cedula, 6, 4), $_SESSION['id_ct']);

// ----------
$pdf->AddPage();
$pdf->SetFillColor(235);
$pdf->Image('../../images/logo_nuevo.jpg',30,10,35);
//$pdf->Image('../../images/logo_claro.png',53,60,110);
$pdf->Image('../../images/bandera_linea.png',0,0,216,1);
$pdf->Image('../../images/bandera_linea.png',0,278,216,1);
if ($_SERVER['HTTP_HOST']=='localhost')
	{$pdf->Image("http://localhost/samatfram/scripts/qr_generador.php?code=".$code,180,245,25,25,"png");}
else	{$pdf->Image("http://app.cebg.com.ve/scripts/qr_generador.php?code=".$code,180,245,25,25,"png");}

//$instituto = instituto();
$pdf->SetFont('Times','I',11);
$pdf->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); 
$pdf->Ln(5);
$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); 
$pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Talento Humano',0,0,'C'); 
$pdf->Ln(5);

$pdf->SetX(170);
$pdf->SetFont('Times','B',13);
$pdf->Cell(30,7,"FECHA",0,1,'C',0);

$pdf->SetX(170);
$pdf->SetFont('Times','B',13);
$pdf->Cell(30,6,$fecha,0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Times','BIU',14);
$pdf->Cell(0,5,'SOLICITUD DE PERMISO',0,0,'C'); 
$pdf->Ln(15);

$pdf->SetFont('Times','B',10);
$pdf->Cell(100,7,"APELLIDOS Y NOMBRES",1,0,'C',1);
$pdf->Cell(0,7,"CEDULA DE IDENTIDAD",1,1,'C',1);
$pdf->SetFont('Times','',10);
$pdf->Cell(100,7,$empleado,1,0,'C',0);
$pdf->Cell(0,7,substr($cedula,0,10),1,0,'C',0);
$pdf->Ln();

$pdf->SetFont('Times','B',10);
$pdf->Cell(70,7,"CARGO",1,0,'C',1);
$pdf->Cell(0,7,"UNIDAD ADMINISTRATIVA",1,1,'C',1);
$pdf->SetFont('Times','',10);
$pdf->Cell(70,7,$cargo,1,0,'C',0);
$pdf->Cell(0,7,$ubicacion,1,0,'C',0);
$pdf->Ln();
$pdf->Ln(1);

$pdf->SetFont('Times','B',10);
$pdf->Cell(0,8,"MOTIVO DE LA SOLICITUD",1,1,'C',1);
$pdf->SetFont('Times','',10);
$pdf->MultiCell(0,7,$descripcion,1,'J',0);
$pdf->Ln(1); 

$pdf->SetFont('Times','B',10);
$pdf->MultiCell(0,7,"DE EXISTIR CONSTANCIAS U OTROS DOCUMENTOS DE SOPORTE
FAVOR SEÑALARLOS Y ANEXAR ORIGINALES",1,'C',1);
$pdf->SetFont('Times','',10);
$pdf->MultiCell(0,7,$soporte,1,'J',0);
$pdf->Ln(1); 

$pdf->SetFont('Times','B',10);
$pdf->Cell(70,8,"FECHA DE SALIDA:",1,0,'C',1);
$pdf->SetFont('Times','',10);
$pdf->Cell(40,8,($desde),1,0,'C',0);
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,8,"HORA:",1,0,'C',1);
$pdf->SetFont('Times','',10);
$pdf->Cell(0,8,$hora1,1,0,'C',0);
$pdf->Ln(8); 

$pdf->SetFont('Times','B',10);
$pdf->Cell(70,8,"HASTA LA FECHA:",1,0,'C',1);
$pdf->SetFont('Times','',10);
$pdf->Cell(40,8,($hasta),1,0,'C',0);
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,8,"HORA:",1,0,'C',1);
$pdf->SetFont('Times','',10);
$pdf->Cell(0,8,$hora2,1,0,'C',0);
$pdf->Ln(8); 

$pdf->SetFont('Times','B',10);
$pdf->Cell(70,8,"FECHA DE INCORPORACIÓN:",1,0,'C',1);
$pdf->SetFont('Times','',10);
$pdf->Cell(40,8,($incorporacion),1,0,'C',0);
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,8,"HORA:",1,0,'C',1);
$pdf->SetFont('Times','',10);
$pdf->Cell(0,8,$hora3,1,0,'C',0);
$pdf->Ln(8); 

$pdf->SetFont('Times','B',10);
$pdf->Cell(110,21,"LAPSO DE DURACIÓN DEL PERMISO",1,0,'C',1);

if ($habiles==0)
	{
	$pdf->Cell(0,10,"TIEMPO",1,0,'C',1);
	$pdf->Ln(); 
	$pdf->SetFont('Times','',10);
	$pdf->Cell(110,21,"");
	$pdf->Cell(0,11,$horas,1,0,'C',0);
	}
else
	{
	$pdf->Cell(0,7,"DIAS",1,0,'C',1);
	$pdf->Ln(); 
	$pdf->Cell(110,21,"");
	$pdf->Cell(35,7,"HABILES",1,0,'C',1);
	$pdf->Cell(0,7,"CALENDARIO",1,0,'C',1);
	$pdf->Ln(); 
	$pdf->SetFont('Times','',10);
	$pdf->Cell(110,21,"");
	$pdf->Cell(35,7,$habiles,1,0,'C',0);
	$pdf->Cell(0,7,$calendario,1,0,'C',0);
	}

//$pdf->Ln(20); 
//$pdf->Cell(115,8,"");
//$pdf->Cell(45,8,"___________________",0,0,"C");
//$pdf->Ln(8); 
//$pdf->SetFont('Times','B',12);
//$pdf->Cell(115,8,"");
//$pdf->Cell(45,8,'FUNCIONARIO',0,0,"C");


$pdf->SetY(-55); 
$pdf->SetFont('Times','',12);
$pdf->Cell(60,8,"Permiso Otorgado por:");
$pdf->Ln(20); 
$pdf->Cell(20,8,"");
$pdf->Cell(70,8,$jefe,0,0,"C");
//$pdf->Cell(45,8,"___________________",0,0,"C");
$pdf->Ln(8); 
$pdf->SetFont('Times','B',12);
$pdf->Cell(20,8,"");
$pdf->Cell(70,8,$jefe_cargo,0,0,"C");

// FIN
	
$pdf->Output();
?>