<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
//$_SESSION['conexionsql']->query("SET NAMES 'utf8'");

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }
	
class PDF extends FPDF
{
	function Footer()
	{    
		$this->SetFont('Times','I',8);
		$this->SetY(-10);
		$this->SetTextColor(120);
		$s=$this->PageNo();
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
	}	
}

$anno = $_SESSION['anno'];
if ($_GET['categoria']==0)
	{	$categoria = " 1=1 ";	}
else
	{	$categoria = " categoria = ".$_GET['categoria'];	}
$partida = $_GET['partida'];
if (trim($_GET['fecha1'])<>'') {$fecha1 = voltea_fecha($_GET['fecha1']);} else {$fecha1='';}
if (trim($_GET['fecha2'])<>'') {$fecha2 = voltea_fecha($_GET['fecha2']);} else {$fecha2=$anno.'/12/31';}

// ENCABEZADO
$pdf=new PDF('L','mm','oficio');
$pdf->AliasNbPages();
$pdf->SetMargins(10,12,10);
$pdf->SetAutoPageBreak(1,15);
$pdf->SetTitle('Libro de Partida');

// ----------
$pdf->AddPage();
$pdf->SetFillColor(91, 192, 222);
$pdf->Image('../../images/escudo.jpg',230,10,24);
$pdf->Image('../../images/logo_nuevo.jpg',70,8,30);
$pdf->Image('../../images/todos.jpg',15,200,12);
$pdf->Image('../../images/bandera_linea.png',10,39,310,2);
$pdf->SetFont('Times','',11);

// ---------------------
////$instituto = instituto();
$pdf->SetFont('Times','I',11.5);
$pdf->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $pdf->Ln(6);

$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,'LIBRO AUXILIAR DE PARTIDAS',0,0,'C'); $pdf->Ln(5);
$pdf->Cell(0,5,'Ejercicio Fiscal '.$anno,0,0,'C'); $pdf->Ln(10);

//-----------------
if ($_GET['categoria']==0)
	{	$consulta = "SELECT * FROM a_presupuesto_$anno WHERE $categoria AND codigo = '$partida' GROUP BY codigo LIMIT 1;";	
		$titulo = 'CONSOLIDADO';
	}
else
	{	$consulta = "SELECT * FROM a_presupuesto_$anno WHERE $categoria AND codigo = '$partida' LIMIT 1;";	
		$titulo = $registrox->categoria;
	}

$tablax = $_SESSION['conexionsql']->query($consulta);
$registrox = $tablax->fetch_object();
//-----------------

$pdf->Cell(0,5,'Categoria: '.$titulo.' Partida: '.$registrox->codigo.' '.$registrox->descripcion,0,0,'C'); $pdf->Ln(2);
$pdf->SetFont('Times','',12);

$pdf->Ln(5);
$pdf->SetFont('Times','B',9);
$pdf->Cell($_SESSION['a']=28,7,'Presupuesto Inicial',1,0,'C',1);
$pdf->Cell($_SESSION['b']=28,7,'Aumentos',1,0,'C',1);
$pdf->Cell($_SESSION['b']=28,7,'Disminuciones',1,0,'C',1);
$pdf->Cell($_SESSION['c']=28,7,'Credito Adicional',1,0,'C',1);
$pdf->Cell($_SESSION['d']=18,7,'Fecha',1,0,'C',1);
$pdf->Cell($_SESSION['e']=40,7,'Concepto',1,0,'C',1);
$pdf->Cell($_SESSION['f']=28,7,'Causado',1,0,'C',1);
$pdf->Cell($_SESSION['f']=28,7,'No Causado',1,0,'C',1);
$pdf->Cell($_SESSION['g']=28,7,'Orden de Pago Nº',1,0,'C',1);
$pdf->Cell($_SESSION['h']=28,7,'Monto (Bs.)',1,0,'C',1);
$pdf->Cell($_SESSION['j']=0,7,'Saldo',1,1,'C',1);
$pdf->SetFont('Times','',8);
//$pdf->Ln();
$pdf->Ln(2);
$i=0;	$alto=5;	//$total=0;
//-----------------
//-----------------
if ($_GET['categoria']==0)
	{	$consulta = "SELECT sum(original) as original FROM a_presupuesto_$anno WHERE $categoria AND codigo = '$partida' GROUP BY codigo LIMIT 1;";	
	}
else
	{	$consulta = "SELECT * FROM a_presupuesto_$anno WHERE $categoria AND codigo = '$partida' LIMIT 1;";	
	}

$tablax = $_SESSION['conexionsql']->query($consulta);
//-----------------
while ($registrox = $tablax->fetch_object())
	{
	$saldo = $registrox->original;
	$saldo_inicial = $registrox->original;
	}

//-----------------
$consulta = "SELECT * FROM (
(SELECT *,1 as marca FROM vista_libro_orden_pago WHERE anno=$anno AND $categoria AND partida='$partida' )
UNION (SELECT *,0 FROM vista_libro_tras_sal WHERE anno=$anno AND $categoria AND partida='$partida')
UNION (SELECT *,0 FROM vista_libro_tras_ing WHERE anno=$anno AND $categoria AND partida='$partida')
UNION (SELECT *,0,0 FROM vista_libro_nom_asig WHERE anno=$anno AND $categoria AND partida='$partida') 
UNION (SELECT *,0,0 FROM vista_libro_nom_des WHERE anno=$anno AND $categoria AND partida='$partida')
UNION (SELECT *,0,0 FROM vista_libro_decretos WHERE anno=$anno AND $categoria AND partida='$partida')) AS tmp ORDER BY fecha, numero;";
//echo $consulta;
$tablax = $_SESSION['conexionsql']->query($consulta);
//$registrox = $tablax->fetch_object();
//-----------------
// SEGUN LAS FECHAS
if ($tablax->num_rows>0)
	{
	$registrox = $tablax->fetch_object();
	while (fecha_a_numero($registrox->fecha)<=fecha_a_numero($fecha1) and $fecha1<>'')
		{ 
		echo $registrox->fecha.' fecha ';
		$saldo = $saldo - $registrox->total - $registrox->egreso + $registrox->ingreso + $registrox->creditos;
		$registrox = $tablax->fetch_object();
		}
	}
//------------
if ($fecha1=='')
	{
	$pdf->Cell($_SESSION['a'],5,formato_moneda($saldo_inicial),0,0,'R',0);		
	$pdf->Cell($_SESSION['b'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['b'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['c'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['d'],5,('01/01/'.$anno),0,0,'C',0);
	$pdf->Cell($_SESSION['e'],5,('PRESUPUESTO ORIGINAL'),0,0,'L',0);//utf8_decode
	$pdf->Cell($_SESSION['f'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['f'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['g'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['h'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['j'],5,formato_moneda($saldo_inicial),0,1,'R',0);
	$registrox = $tablax->fetch_object();		
	}
else
	{
	$pdf->Cell($_SESSION['a'],5,formato_moneda($saldo),0,0,'R',0);		
	$pdf->Cell($_SESSION['b'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['b'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['c'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['d'],5,(voltea_fecha($fecha1)),0,0,'C',0);
	$pdf->Cell($_SESSION['e'],5,('SALDO INICIAL'),0,0,'L',0);//utf8_decode
	$pdf->Cell($_SESSION['f'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['f'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['g'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['h'],5,'',0,0,'R',0);		
	$pdf->Cell($_SESSION['j'],5,formato_moneda($saldo),0,1,'R',0);		
	}
//-----------------
if ($tablax->num_rows>0)
	{
	do	{
		if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(240);}
		$pdf->Cell($_SESSION['a'],$alto,'',0,0,'R',1);		
		$pdf->Cell($_SESSION['b'],$alto,formato_moneda($registrox->ingreso),0,0,'R',1);		
		$pdf->Cell($_SESSION['b'],$alto,formato_moneda($registrox->egreso),0,0,'R',1);		
		$pdf->Cell($_SESSION['c'],$alto,formato_moneda($registrox->creditos),0,0,'R',1);		
		$pdf->Cell($_SESSION['d'],$alto,voltea_fecha($registrox->fecha),0,0,'C',1);
		if ($registrox->marca<>'0')
			{$pdf->Cell($_SESSION['e'],$alto,substr($registrox->contribuyente,0,30),0,0,'L',1);}
		else
			{$pdf->Cell($_SESSION['e'],$alto,substr($registrox->concepto,0,30),0,0,'L',1);}
		//$pdf->Cell($_SESSION['e'],$alto,substr($registrox->concepto,0,30),0,0,'L',1);//utf8_decode
		//$pdf->Cell($_SESSION['e'],$alto,substr($registrox->concepto,0,30),0,0,'L',1);//utf8_decode
		$pdf->Cell($_SESSION['f'],$alto,formato_moneda($registrox->total),0,0,'R',1);		
		$pdf->Cell($_SESSION['f'],$alto,formato_moneda(0),0,0,'R',1);		
		$pdf->Cell($_SESSION['g'],$alto,rellena_cero($registrox->numero,6),0,0,'C',1);		
		$pdf->Cell($_SESSION['h'],$alto,formato_moneda($registrox->total),0,0,'R',1);		
		$saldo = $saldo - $registrox->total - $registrox->egreso + $registrox->ingreso + $registrox->creditos ;
		$pdf->Cell($_SESSION['j'],$alto,formato_moneda($saldo),0,1,'R',1);		
		//-----------------
		$i++;
		}while ($registrox = $tablax->fetch_object() and fecha_a_numero($registrox->fecha)<=fecha_a_numero($fecha2));
	}

	$pdf->Ln(2);
	$pdf->SetFont('Times','B',12);
	$pdf->SetFillColor(91, 192, 222);
	if ($fecha2==$anno.'/12/31')
		{	$pdf->Cell(0,7,'Disponibilidad Bs => '.formato_moneda($saldo),1,1,'R',1);	}
	else
		{	$pdf->Cell(0,7,'Para el '.voltea_fecha($fecha2).' Disponibilidad Bs => '.formato_moneda($saldo),1,1,'R',1);	}	
	$pdf->Output();
?>