<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

//-----------
$anno = $_SESSION['anno'] ;
$categoria = $_SESSION['categoria'] ;
$partida = $_SESSION['partida'] ;
$resumen = $_SESSION['resumen'] ;
$largog = strlen($categoria);
$largop = strlen($partida);

class PDF extends FPDF
{
	function Header()
	{
	$this->SetFillColor(2, 117, 216);
		if ($_SESSION['anno']<2024)
			{$this->Image('../../images/logo_2023.jpg',35,10,32);}
		else
			{$this->Image('../../images/logo_nuevo.jpg',35,10,40);}
//	$this->Image('../../images/logo_nuevo.jpg',35,10,32);
	$this->SetFont('Times','',11);
	
	// ---------------------
	//$this->SetY(12);
	//$instituto = instituto();
	$this->SetFont('Times','I',11.5);
	$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Dirección de Administracion y Presupuesto',0,0,'C'); $this->Ln(5);
	$this->Cell(0,5,'Rif G-20001287-0',0,0,'C'); $this->Ln(1);
	$this->SetFont('Times','B',11.5);
	$this->Cell(0,5,'Fecha: '.date('d/m/Y'),0,0,'R'); 
	$this->Ln();
	
	$this->SetFont('Times','B',12);
	$this->Cell(0,5,'EJECUCION PRESUPUESTARIA '.$_SESSION['anno'],0,0,'C'); 
	$this->Ln(7);
	
	$this->SetTextColor(255);
	$this->SetFont('Arial','B',8);
	$this->Cell($_SESSION['a']=20,7,'Partida',1,0,'C',1);
	$this->Cell($_SESSION['b']=56,7,'Descripción',1,0,'C',1);
	$this->Cell($_SESSION['c']=21,7,'Asignación',1,0,'C',1);
	$this->Cell($_SESSION['d']=24,7,'Credito Adicional',1,0,'C',1);
	$this->Cell($_SESSION['e']=24,7,'Traslado Presupuestario',1,0,'C',1);
	$this->Cell($_SESSION['f']=24,7,'Aumento (Cred. + Trasl.)',1,0,'C',1);
	$this->Cell($_SESSION['g']=21,7,'Disminución',1,0,'C',1);
	$this->Cell($_SESSION['h']=24,7,'Total Asignación',1,0,'C',1);
	$this->Cell($_SESSION['i']=24,7,'Compromiso',1,0,'C',1);
	$this->Cell($_SESSION['j']=24,7,'Causado',1,0,'C',1);
	$this->Cell($_SESSION['k']=24,7,'Pagado',1,0,'C',1);
	$this->Cell($_SESSION['l']=0,7,'Disponible',1,1,'C',1);
	}
	
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-8);
		$this->SetTextColor(120);
		//$this->Cell(0,5,'Resolución '.($_GET['id']));
		//--------------
		$s=$this->PageNo();
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
	}	

}

// ENCABEZADO
$pdf=new PDF('L','mm','OFICIO');
$pdf->AliasNbPages();
$pdf->SetMargins(10,15,10);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Presupuesto');

// ----------
$pdf->AddPage();

$pdf->SetFont('Times','',8.5);
$pdf->SetTextColor(0);
$pdf->SetFillColor(225);
$i=0;
$linea = 1;
//-----------------
$consultx = "SELECT sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE activo=1 AND left(categoria,$largog) = '$categoria' AND left(codigo,$largop) = '$partida' ORDER BY categoria, codigo;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$registrx = $tablx->fetch_object();
$titulo = "PRESUPUESTO TOTAL"; 
if ($resumen==1 and ($categoria)=='') 
	{ 
	$titulo = 'CONSOLIDADO';
	}
$pdf->Cell($_SESSION['a']+$_SESSION['b'],5,($titulo),$linea,0,'C',1);
$pdf->Cell($_SESSION['c'],5,formato_moneda($registrx->original),$linea,0,'R',1);
$pdf->Cell($_SESSION['d'],5,formato_moneda($registrx->creditos),$linea,0,'R',1);
$pdf->Cell($_SESSION['e'],5,formato_moneda($registrx->ingreso),$linea,0,'R',1);
$pdf->Cell($_SESSION['f'],5,formato_moneda($registrx->creditos+$registrx->ingreso),$linea,0,'R',1);
$pdf->Cell($_SESSION['g'],5,formato_moneda($registrx->egreso),$linea,0,'R',1);
$pdf->Cell($_SESSION['h'],5,formato_moneda($registrx->original+$registrx->creditos+$registrx->ingreso-$registrx->egreso),$linea,0,'R',1);
$pdf->Cell($_SESSION['i'],5,formato_moneda($registrx->compromiso),$linea,0,'R',1);
$pdf->Cell($_SESSION['j'],5,formato_moneda($registrx->causado),$linea,0,'R',1);
$pdf->Cell($_SESSION['k'],5,formato_moneda($registrx->pagado),$linea,0,'R',1);
$pdf->Cell($_SESSION['l'],5,formato_moneda($registrx->disponible),$linea,0,'R',1);
$pdf->ln();
//-----------------
if ($largop>0)
	{
	$aux = "AND codigo IN (SELECT categoria FROM a_presupuesto_$anno WHERE activo=1 AND left(codigo,$largop)='$partida')";
	}
else
	{
	$aux = "";
	}
$pdf->SetFillColor(240);
//----------
$consulta = "SELECT codigo, descripcion FROM a_presupuesto_$anno WHERE activo=1 AND left(codigo,$largog)='$categoria' AND categoria IS NULL $aux GROUP BY codigo ORDER BY categoria, codigo;";
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($resumen==1) 
	{ 
	$consulta = "SELECT codigo, 'RESUMEN' as descripcion FROM a_presupuesto_$anno WHERE activo=1 ORDER BY categoria, codigo LIMIT 1;"; //echo $consulta; 
	}	
$tabla = $_SESSION['conexionsql']->query($consulta);
//echo $consulta;
//---- PRIMER CICLO
while ($registro = $tabla->fetch_object())
	{
	$categoria = $registro->codigo;
	$i++;
	$j=0;
	$consultx = "SELECT sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE activo=1 AND categoria='$categoria' AND left(codigo,$largop)='$partida' ORDER BY categoria, codigo;";
	if ($resumen==1) 
		{
		$consultx = "SELECT sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE activo=1 AND left(codigo,$largop)='$partida' ORDER BY categoria, codigo;";
		}	
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//echo $consulta;
	$registrx = $tablx->fetch_object();
	if ($registro->descripcion<>'RESUMEN')
		{

		//-------------------
		$pdf->SetFont('Times','',7);
		//----- PARA ARRANCAR CON LA LINEA
		$y1=$pdf->GetY();
		$x=$pdf->GetX();
		$pdf->SetX($x);
		//-----------------------------------------MULTICELL
		$pdf->MultiCell($_SESSION['a']+$_SESSION['b'],5,(($registro->descripcion)),$linea,'J',1);
		$y2=$pdf->GetY();
		//- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
		$pdf->SetY($y1);
		$pdf->SetX($x+$_SESSION['a']+$_SESSION['b']);
		$alto2 = $y2 - $y1;
		//-------------------
		$pdf->SetFont('Times','',8.5);
		//---------------------------------------------------

//	$pdf->Cell($_SESSION['a']+$_SESSION['b'],$alto2,($registro->descripcion),$linea,0,'C');
	$pdf->Cell($_SESSION['c'],$alto2,formato_moneda($registrx->original),$linea,0,'R',1);
	$pdf->Cell($_SESSION['d'],$alto2,formato_moneda($registrx->creditos),$linea,0,'R',1);
	$pdf->Cell($_SESSION['e'],$alto2,formato_moneda($registrx->ingreso),$linea,0,'R',1);
	$pdf->Cell($_SESSION['f'],$alto2,formato_moneda($registrx->creditos+$registrx->ingreso),$linea,0,'R',1);
	$pdf->Cell($_SESSION['g'],$alto2,formato_moneda($registrx->egreso),$linea,0,'R',1);
	$pdf->Cell($_SESSION['h'],$alto2,formato_moneda($registrx->original+$registrx->creditos+$registrx->ingreso-$registrx->egreso),$linea,0,'R',1);
	$pdf->Cell($_SESSION['i'],$alto2,formato_moneda($registrx->compromiso),$linea,0,'R',1);
	$pdf->Cell($_SESSION['j'],$alto2,formato_moneda($registrx->causado),$linea,0,'R',1);
	$pdf->Cell($_SESSION['k'],$alto2,formato_moneda($registrx->pagado),$linea,0,'R',1);
	$pdf->Cell($_SESSION['l'],$alto2,formato_moneda($registrx->disponible),$linea,0,'R',1);
	$pdf->ln();
}
	$consultx = "SELECT * FROM a_presupuesto_$anno WHERE activo=1 AND categoria='$categoria' AND left(codigo,$largop)='$partida' ORDER BY categoria, codigo;";
	if ($resumen==1) 
		{ 
		$consultx = "SELECT codigo, descripcion, sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE activo=1 AND categoria IS NOT NULL AND left(codigo,$largop)='$partida' GROUP BY codigo ORDER BY codigo;";
		}	
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registrx = $tablx->fetch_object())
	{
		$j++;
			//-------------------
			$pdf->SetFont('Times','',7.5);
			//----- PARA ARRANCAR CON LA LINEA
			$y1=$pdf->GetY();
			$x=$pdf->GetX();
			$pdf->SetX($x+$_SESSION['a']);
			//-----------------------------------------MULTICELL
			$pdf->MultiCell($_SESSION['b'],5, (($registrx->descripcion)),$linea,'J');
			$y2=$pdf->GetY();
			//- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
			$pdf->SetY($y1);
			$pdf->SetX($x);
			$alto2 = $y2 - $y1;
			//-------------------
			$pdf->SetFont('Times','',8.5);
			//---------------------------------------------------
		$pdf->Cell($_SESSION['a'],$alto2,formato_partida2($registrx->codigo),$linea,0,'R');
//		$pdf->SetFont('Times','',8);
		$pdf->Cell($_SESSION['b'],5,'');
//		$pdf->SetFont('Times','',8.5);
		$pdf->Cell($_SESSION['c'],$alto2,formato_moneda($registrx->original),$linea,0,'R');
		$pdf->Cell($_SESSION['d'],$alto2,formato_moneda($registrx->creditos),$linea,0,'R');
		$pdf->Cell($_SESSION['e'],$alto2,formato_moneda($registrx->ingreso),$linea,0,'R');
		$pdf->Cell($_SESSION['f'],$alto2,formato_moneda($registrx->creditos+$registrx->ingreso),$linea,0,'R');
		$pdf->Cell($_SESSION['g'],$alto2,formato_moneda($registrx->egreso),$linea,0,'R');
		$pdf->Cell($_SESSION['h'],$alto2,formato_moneda($registrx->original+$registrx->creditos+$registrx->ingreso-$registrx->egreso),$linea,0,'R');
		$pdf->Cell($_SESSION['i'],$alto2,formato_moneda($registrx->compromiso),$linea,0,'R');
		$pdf->Cell($_SESSION['j'],$alto2,formato_moneda($registrx->causado),$linea,0,'R');
		$pdf->Cell($_SESSION['k'],$alto2,formato_moneda($registrx->pagado),$linea,0,'R');
		$pdf->Cell($_SESSION['l'],$alto2,formato_moneda($registrx->disponible),$linea,0,'R');
		$pdf->ln();
		
		if ($pdf->GetY()>180 and $pdf->PageNo()<22) {		$pdf->AddPage();	}
	}
}

//$pdf->Output();
?>