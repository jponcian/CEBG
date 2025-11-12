<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-12);
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

//------------
if ($_GET['estatus']==0)
	{ 	$id = decriptar($_GET['id']);		}
else
	{ 	$id = decriptar($_GET['solicitud']);	}
$tipo = ($_GET['tipo']);

//------------
if ($_GET['estatus']==0)
	{ 	$consultx = "SELECT *, num_nomina as numero FROM nomina WHERE estatus=0 AND id_cont = $id AND tipo_pago='$tipo' LIMIT 1;";		}
else
	{ 	$consultx = "SELECT * FROM nomina_solicitudes WHERE id = $id LIMIT 1;"; 	}
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------
$tipo_pago = $registro->tipo_pago;
$fecha_sol = $registro->fecha_sol;
$numero = $registro->numero;
$nomina = $registro->nomina;
$anno = $registro->anno;
$fecha = $registro->fecha;
$desde = $registro->desde;
$hasta = $registro->hasta;
$asignaciones = $registro->asignaciones;
$descuentos = $registro->descuentos;
$total = $registro->total;
$solicitud = $registro->num_sol_pago;
$quincena = $registro->descripcion;
//--------------
if ($_GET['estatus']==0)
	{ 	$consulta = "SELECT COUNT(cedula) FROM nomina WHERE estatus=0 AND id_cont = $id AND tipo_pago='$tipo' GROUP BY cedula;";  	
		$fecha_sol = date('Y-m-d'); //$numero = 0; 	
		}
else
	{ 	$consulta = "SELECT COUNT(cedula) FROM nomina WHERE id_solicitud = $id GROUP BY cedula;";	}
$tabla = $_SESSION['conexionsql']->query($consulta);
$trabajadores = $tabla->num_rows;

//-------------

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(17,12,17);
$pdf->SetAutoPageBreak(1,20);
$pdf->SetTitle('Preliminar');

// ----------
$pdf->AddPage();
$pdf->SetFillColor(235);
$pdf->Image('../../images/logo_nuevo.jpg',27,7,40);
//$pdf->Image('../../images/bandera_linea.png',17,41,182,0);
$pdf->SetFont('Times','',11);

$municipio = 'Francisco de Miranda';
// ---------------------
//$pdf->SetY(12);
//$instituto = instituto();
$pdf->SetFont('Times','I',11.5);
$pdf->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Talento Humano',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Ejercicio Fiscal '.$anno,0,0,'C'); $pdf->Ln(7);

$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,'PRELIMINAR - LISTADO',0,0,'C'); 
$pdf->Ln(10);

$y=$pdf->GetY();
$pdf->SetY(20);
//$pdf->SetX(150);
$pdf->SetFont('Arial','B',13);
if ($solicitud>0)
	{
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(0,5,'Solicitud: '.rellena_cero($solicitud,5),0,0,'R'); $pdf->Ln(7);
	$pdf->SetTextColor(255,0,0);
	$pdf->Cell(0,5,'Nomina: '.rellena_cero($numero,5),0,0,'R'); $pdf->Ln(7);
	}
$pdf->SetFont('Arial','B',11);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,5,'Fecha: '.voltea_fecha($fecha_sol),0,0,'R'); //$pdf->Ln(10);
$pdf->SetTextColor(0);
$pdf->SetY($y);

$pdf->SetFont('Times','B',8);
$pdf->MultiCell(0,5,"Preliminar Pago Correspondiente a: $quincena, de fecha: ".voltea_fecha($desde).".",0,'J');// ".rellena_cero($numero,3)."
$pdf->Ln(3);

$alto = 6;
$pdf->SetFillColor(245);
//-----------------
if ($_GET['estatus']==0)
	{ 	$consultax = "SELECT Sum(nomina.sueldo) as sueldo, Sum(nomina.prof) as prof, Sum(nomina.antiguedad) as antiguedad, Sum(nomina.hijos) as hijos, Sum(nomina.bono) as bono, Sum(nomina.asignaciones) as asignaciones, Sum(nomina.sso) as sso, Sum(nomina.fp) as fp, Sum(nomina.lph) as lph, Sum(nomina.fej) as fej, Sum(nomina.fusamieg) as fusamieg, Sum(nomina.descuentos) as descuentos, Sum(nomina.total) as total FROM nomina WHERE estatus=0 AND nomina.id_cont = $id AND nomina.tipo_pago='$tipo' GROUP BY nomina.id_cont, nomina.tipo_pago;";  	}
else
	{ 	$consultax = "SELECT Sum(nomina.sueldo) as sueldo, Sum(nomina.prof) as prof, Sum(nomina.antiguedad) as antiguedad, Sum(nomina.hijos) as hijos, Sum(nomina.bono) as bono, Sum(nomina.asignaciones) as asignaciones, Sum(nomina.sso) as sso, Sum(nomina.fp) as fp, Sum(nomina.lph) as lph, Sum(nomina.fej) as fej, Sum(nomina.fusamieg) as fusamieg, Sum(nomina.descuentos) as descuentos, Sum(nomina.total) as total FROM nomina WHERE nomina.id_solicitud = $id GROUP BY nomina.id_solicitud;";	}
$tablax = $_SESSION['conexionsql']->query($consultax); //echo $consultax;
$registrox = $tablax->fetch_object();
//-----------------
$pdf->SetFont('Times','B',9);
$pdf->Cell(7+25+50+20+65+40,$alto+1,'TOTAL GENERAL => ',1,0,'R',1);
$pdf->Cell($o,$alto,formato_moneda($registrox->total),1,1,'R',1);

//$pdf->SetFillColor(245);
$pdf->SetFont('Times','B',8);
$pdf->Cell($aa=7,6,'N°',1,0,'C',1);
$pdf->Cell($a=25,6,'Cedula',1,0,'C',1);
$pdf->Cell($b=60,6,'Empleado',1,0,'C',1);
$pdf->Cell($d=20,6,'Fecha Ingreso',1,0,'C',1);
$pdf->Cell($e=65,6,'Cargo',1,0,'C',1);
//$pdf->Cell($h=25,5,'Bono',1,0,'C',1);
$pdf->Cell($i=30,6,'Asignacion',1,0,'C',1);
//$pdf->Cell($m=25,5,'Salario Diario',1,0,'C',1);
//$pdf->Cell($n=25,5,'Dias Trabajados',1,0,'C',1);
$pdf->Cell($o=0,6,'Neto a Pagar',1,1,'C',1);
//$pdf->Cell($p=0,5,'Firma Conforme',1,1,'C',1);
$pdf->SetFont('Times','',8);
//$pdf->Ln();
$pdf->SetFillColor(255);
$alto = 6;

$pdf->SetFont('Times','',8);

if ($_GET['estatus']==0)
	{ 	$consulta = "SELECT nomina.*, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, rac.fecha_ingreso FROM nomina, rac WHERE estatus=0 AND nomina.id_cont = $id AND nomina.tipo_pago='$tipo' AND rac.cedula = nomina.cedula ORDER BY nomina.ubicacion ASC, nomina.cedula;";  	}
else
	{ 	$consulta = "SELECT nomina.*, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, rac.fecha_ingreso FROM nomina, rac WHERE nomina.id_solicitud=$id AND rac.cedula = nomina.cedula ORDER BY nomina.ubicacion ASC, nomina.cedula;";		}
$tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
//-----------------
$z=0;
$monto = 0;
$ubicacion = '';
while ($registro = $tabla->fetch_object())
{
	if ($ubicacion<>$registro->ubicacion)
		{	
		$pdf->SetFillColor(245);
		$pdf->Cell($d+$e+$h+$c+$aa+$a+$b,$alto,$registro->ubicacion,1,0,'L',1);	//$registro->categoria.' '.
		$ubicacion = $registro->ubicacion ;
		//-----------------
		//if ($totalizar)
			//{	
				//-----------------
				if ($_GET['estatus']==0)
					{ 	$consultax = "SELECT Sum(nomina.sueldo) as sueldo, Sum(nomina.prof) as prof, Sum(nomina.antiguedad) as antiguedad, Sum(nomina.hijos) as hijos, Sum(nomina.bono) as bono, Sum(nomina.asignaciones) as asignaciones, Sum(nomina.sso) as sso, Sum(nomina.fp) as fp, Sum(nomina.lph) as lph, Sum(nomina.fej) as fej, Sum(nomina.fusamieg) as fusamieg, Sum(nomina.descuentos) as descuentos, Sum(nomina.total) as total FROM nomina WHERE estatus=0 AND nomina.id_cont = $id AND nomina.tipo_pago='$tipo' AND ubicacion='$ubicacion' ORDER BY nomina.ubicacion ASC;";  	}
				else
					{ 	$consultax = "SELECT Sum(nomina.sueldo) as sueldo, Sum(nomina.prof) as prof, Sum(nomina.antiguedad) as antiguedad, Sum(nomina.hijos) as hijos, Sum(nomina.bono) as bono, Sum(nomina.asignaciones) as asignaciones, Sum(nomina.sso) as sso, Sum(nomina.fp) as fp, Sum(nomina.lph) as lph, Sum(nomina.fej) as fej, Sum(nomina.fusamieg) as fusamieg, Sum(nomina.descuentos) as descuentos, Sum(nomina.total) as total FROM nomina WHERE nomina.id_solicitud = $id AND ubicacion='$ubicacion' GROUP BY nomina.id_solicitud;";	}
				$tablax = $_SESSION['conexionsql']->query($consultax);
				$registrox = $tablax->fetch_object();
				//-----------------
				$pdf->SetFont('Times','B',8);
				$pdf->Cell($i+$m+$n,$alto,'Sub Total Categoría => ',1,0,'R',1);
				$pdf->Cell($o,$alto,formato_moneda($registrox->total),1,1,'R',1);
				//$pdf->Cell($p,$alto,'',1,1,'R',0);
				$pdf->SetFont('Times','',8);
			//}		
		$pdf->SetFillColor(255);
		}
	//----------
	$pdf->Cell($aa,$alto,$z+1,1,0,'C',0);
	$pdf->Cell($a,$alto,$registro->cedula,1,0,'C',0);
	$pdf->SetFont('Times','',7);
	$pdf->Cell($b,$alto,$registro->nombre,1,0,'L',0);
	$pdf->SetFont('Times','',9);
	$pdf->Cell($d,$alto,voltea_fecha($registro->fecha_ingreso),1,0,'C',1);
	$pdf->SetFont('Times','',8);
	$pdf->Cell($e,$alto,($registro->cargo),1,0,'L',1);
	$pdf->SetFont('Times','',9);
	//$pdf->Cell($h,$alto,formato_moneda($registro->bono),1,0,'R',1);
	$pdf->Cell($i,$alto,formato_moneda($registro->asignaciones),1,0,'R',1);
	//$pdf->Cell($m,$alto,formato_moneda($registro->asignaciones/$registro->dias_trabajados),1,0,'R',0);
	//$pdf->Cell($n,$alto,($registro->dias_trabajados),1,0,'R',0);
	$pdf->Cell($o,$alto,formato_moneda($registro->total),1,0,'R',0);
	$pdf->Cell($p,$alto,'',1,0,'R',0);
	//-----------
	$pdf->Ln();
	$z++;
	}

$pdf->SetAutoPageBreak(1,0);
$pdf->SetY(-35);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell($a=43,5,'Elaborado por:',0,0,'L');
$pdf->Cell($a,5,'Visto Bueno',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->Cell($a,5,'Paguese',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->SetY(-35);
$pdf->Cell(20,20,'',0,0);
$pdf->Cell($a,20,'',1,0,'L');
$pdf->Cell($a,20,'',1,0,'C');
$pdf->Cell($a,20,'',1,0,'C');
$pdf->Cell($a,20,'',1,0,'C');
$pdf->Cell($a,20,'',1,0,'C');
$pdf->SetY(-26);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell($a,5,'',0,0,'L');
$pdf->Cell($a,5,'Director(a) de Personal',0,0,'C');
$pdf->Cell($a,5,'Sello',0,0,'C');
$pdf->Cell($a,5,'Director(a) de Administracion y',0,0,'C');
$pdf->Cell($a,5,'Sello',0,0,'C');
$pdf->SetY(-23);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell($a,5,'Analista de Talento Humano',0,0,'L');
$pdf->Cell($a,5,'y Recursos Humanos',0,0,'C');
$pdf->Cell($a,5,'',0,0,'C');
$pdf->Cell($a,5,'Presupuesto',0,0,'C');

$pdf->Output();
?>