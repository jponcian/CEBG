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
	function Header()
	{}	
	
	function Footer()
	{}	
}

$id = decriptar($_GET['id']);
//-------------	

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages('nb');
$pdf->SetMargins(17,20,17);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Punto de Cuenta');

// ----------
$pdf->AddPage();

$id = decriptar($_GET['id']);
$aprobado = ($_GET['p']);

if ($aprobado==0)
	{$consultx = "SELECT	presupuesto.*, contribuyente.nombre, area FROM contribuyente, presupuesto, a_areas WHERE presupuesto.oficina = a_areas.id AND presupuesto.estatus=0 AND id_contribuyente = $id AND presupuesto.id_contribuyente = contribuyente.id LIMIT 1;";}
else
	{$consultx = "SELECT	presupuesto.*, contribuyente.nombre, area FROM contribuyente, presupuesto, a_areas WHERE presupuesto.oficina = a_areas.id AND id_solicitud = $id AND presupuesto.id_contribuyente = contribuyente.id LIMIT 1;";}

//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------
$anno = $registro->anno;
$numero = rellena_cero($registro->numero,3);
$tipo = $registro->tipo_orden;
$fecha_presupuesto = voltea_fecha($registro->fecha_presupuesto);
$memo = $registro->memo;
$punto_cuenta = $registro->punto_cuenta;
$concepto =  $registro->concepto;
$oficina = trim($registro->area);
//--------------

$pdf->SetFillColor(240);
$pdf->Image('../../images/logo_nuevo.jpg',30,7,23);
$pdf->SetFont('Times','',11);
// ---------------------

$pdf->SetFont('Times','B',15);
$pdf->Cell(0,10,"PUNTO DE CUENTA",0,0,'C'); 
$pdf->Ln(12);

$inicio=$pdf->GetY();
$pdf->SetFont('Times','B',10);
$pdf->Cell(40,5,"",0,0,'C'); 
$pdf->Cell(2,5,"",0,0,'C'); 
$pdf->Cell(85,5,"Presentante:",0,0,'C'); 
$pdf->Ln();

$firma1 = firma(10);

$pdf->Cell(40,5,"$punto_cuenta",0,0,'C'); 
$pdf->Cell(2,5,"",0,0,'C'); 
$pdf->Cell(85,5,$firma1[1],0,0,'C'); 
$pdf->Cell(2,5,"",0,0,'C'); 
$pdf->Cell(25,5,"Fecha:",0,0,'C'); 
$pdf->Cell(2,5,"",0,0,'C'); 
$pdf->Cell(0,5,"Página:",0,0,'C'); 
$pdf->Ln();

$pdf->Cell(40,5,"",0,0,'C'); 
$pdf->Cell(2,5,"",0,0,'C'); 
$y=$pdf->GetY();
$x=$pdf->GetX();
$pdf->SetFont('Times','',10);
$pdf->Multicell(85,3.5,$firma1[2],0,'C',0);
$pdf->SetFont('Times','B',11);
$pdf->SetY($y);
$pdf->SetX($x+85);
$pdf->Cell(2,5,"",0,0,'C'); 
$pdf->Cell(25,5,"$fecha_presupuesto",0,0,'C'); 
$pdf->Cell(2,5,"",0,0,'C'); 
$pdf->Cell(0,5,"".$pdf->PageNo()." de nb",0,0,'C'); 

$pdf->SetY($inicio);
$pdf->Cell(40,18,"",1,0,'C'); 
$pdf->Cell(2,18,"",0,0,'C'); 
$pdf->Cell(85,18,"",1,0,'C'); 
$pdf->Cell(2,18,"",0,0,'C'); 
$pdf->Cell(25,18,"",1,0,'C'); 
$pdf->Cell(2,18,"",0,0,'C'); 
$pdf->Cell(0,18,"",1,0,'C'); 

$pdf->Ln(20);

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,6,"ASUNTO:",1,0,'L'); 
$pdf->Ln();

$pdf->SetFont('Times','',10);
$pdf->MultiCell(0,6,"Solicitud de autorización para dar inicio al procedimiento de contratación de “".$concepto."”" ,1); 		
//$pdf->Ln(0);

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,6,"ARGUMENTACIÓN:",1,0,'L'); 
$pdf->Ln();

$inicio=$pdf->GetY();
$pdf->SetFont('Times','',10);
$pdf->MultiCell(0,6,"En atención al memorando $memo, enviado por la Oficina de $oficina, donde se  solicita el “".$concepto."”, esta Dirección de Administración y Presupuesto procede a informarle que la disponibilidad presupuestaria y monto estimado de contratación para dicho gastos son los siguientes:",0); 		
$pdf->Ln(3);

$pdf->SetFont('Times','B',8);
$y=$pdf->GetY();
$pdf->Cell(2,12,'',0,0,'C',0);
$pdf->Cell($a=20,10,'ACTIVIDAD',1,0,'C',1);
$pdf->Cell($b=25,10,'PARTIDA',1,0,'C',1);
$pdf->Cell($d=55,10,'DENOMINACIÓN',1,0,'C',1);
$x=$pdf->GetX();
$pdf->Multicell($e=40,5,'DISPONIBILIDAD PRESUPUESTARIA Bs.',1,'C',1);
$pdf->SetY($y);
$pdf->SetX($x+$e);
$x=$pdf->GetX();
$pdf->Multicell(38,5,'MONTO ESTIMADO DE LA CONTRATACIÓN',1,'C',1);
$pdf->SetFont('Times','',10);
$i=0;
$total =0;

if ($aprobado==0)
	{$consulta = "SELECT a_partidas.codigo,	a_partidas.descripcion as partida,	SUM(total) as monto, categoria, disponibilidad FROM	contribuyente,	presupuesto	,	a_partidas	WHERE 		presupuesto.partida = a_partidas.codigo AND	presupuesto.estatus = 0 AND	id_contribuyente =  $id AND	presupuesto.id_contribuyente = contribuyente.id GROUP BY a_partidas.codigo;";}
else
	{$consulta = "SELECT a_partidas.codigo,	a_partidas.descripcion as partida,	SUM(total) as monto, categoria, disponibilidad FROM	contribuyente,	presupuesto	,	a_partidas	WHERE 		presupuesto.partida = a_partidas.codigo AND	id_solicitud =  $id AND	presupuesto.id_contribuyente = contribuyente.id GROUP BY a_partidas.codigo;";}

//-------------
$tablx = $_SESSION['conexionsql']->query($consulta);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	$y=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->Cell($a+2,5,'',0,0,'C',0);
	$pdf->Cell($b,5,'',0,0,'C',0);
	$pdf->SetFont('Times','',8);
	$pdf->Multicell($d,5,$registro->partida,1,'J',0);
	$pdf->SetFont('Times','',10);
	$y2=$pdf->GetY();
	$pdf->SetY($y);
	$pdf->SetX($x+2);
	$pdf->Cell($a,$y2-$y,substr($registro->categoria,8,2),1,0,'C',0);
	$pdf->Cell($b,$y2-$y,$registro->codigo,1,0,'C',0);
	$pdf->Cell($d,$y2-$y,'',0,0,'C',0);
	$pdf->Cell($e,$y2-$y,formato_moneda($registro->disponibilidad),1,0,'R',0);
	$pdf->Cell(38,$y2-$y,formato_moneda($registro->monto),1,0,'R',0);
	$pdf->Ln($y2-$y);
	$total += $registro->total;
	}
//-------------
$pdf->Ln(3);
$pdf->SetFont('Times','',10);
$pdf->MultiCell(0,6,"Así mismo, le indico que de acuerdo al Plan Operativo Anual Institucional $anno, es tarea de esta Dirección adquirir los Materiales, Suministros y los Servicios necesarios para el buen funcionamiento operacional de la CEBG.",0); 		
$fin=$pdf->GetY();

$pdf->SetY($inicio);
$pdf->Cell(0,$fin-$inicio,'',1);
$pdf->Ln();

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,6,"PROPUESTA:",1,0,'L'); 
$pdf->Ln();

$pdf->SetFont('Times','',10);
$pdf->MultiCell(0,6,"Se propone iniciar el procedimiento de contratación a través de la modalidad de ".tipo_compra($tipo)." en fecha $fecha_presupuesto.
Por todo lo antes expuesto, muy respetuosamente se solicita al Contralor del Estado Bolivariano de Guárico aprobar esta solicitud.",1); 		

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,6,"OBSERVACIONES DEL CONTRALOR DEL ESTADO BOLIVARIANO DE GUARICO:",1,0,'L'); 
$pdf->Ln();

$pdf->Cell(0,10,"",1,0,'L'); 
$pdf->Ln();

$pdf->Cell(0,6,'DECISIÓN:',1,0,'L',0);
$pdf->Ln();
$inicio=$pdf->GetY();
$pdf->Cell(0,10,'',1,0,'L',0);

$pdf->SetY($inicio+3);
$pdf->SetX(40);
$pdf->Cell(5,5,'',1);
$pdf->SetX(90);
$pdf->Cell(5,5,'',1);
$pdf->SetX(140);
$pdf->Cell(5,5,'',1);

$pdf->SetY($inicio+3);
$pdf->SetX(50);
$pdf->Cell(20,5,'APROBADO',0);
$pdf->SetX(100);
$pdf->Cell(20,5,'NEGADO',0);
$pdf->SetX(150);
$pdf->Cell(20,5,'DIFERIDO',0);

$pdf->SetY(-32);
$pdf->SetX(50);
$pdf->Cell(40,5,'__________________________',0,0,'C',0);
$pdf->SetX(125);
$pdf->Cell(40,5,'_________________________________',0,0,'C',0);
$pdf->Ln(5);

$firma2 = firma(11);

$pdf->SetFont('Times','B',10);

$pdf->SetX(50);
$pdf->Cell(40,5,mayuscula($firma1[1]),0,0,'C',0);
$pdf->SetX(125);
$pdf->Cell(40,5,mayuscula($firma2[1]),0,0,'C',0);
$pdf->Ln(5);

$inicio=$pdf->GetY();
$pdf->SetX(39);
$pdf->SetFont('Times','',9);
$pdf->MultiCell(60,4,($firma1[2]),0,"C"); 		

$pdf->SetY($inicio);
$pdf->SetX(120);
$pdf->MultiCell(50,4,$firma2[2],0,"C"); 		

$pdf->Output();
?>