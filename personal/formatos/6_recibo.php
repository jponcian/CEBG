<?php
session_start();
ob_end_clean();
session_start();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');

//if ($_SESSION['VERIFICADO'] != "SI") { 
//    header ("Location: ../index.php?errorusuario=val"); 
//    exit(); 
//	}

if ($_GET['id']<>'0' and $_GET['id']<>'')
	{	
	$ci = decriptar($_GET['id']);	
	$hasta = decriptar($_GET['t']);	
	}
else
	{	$ci = $_POST['id'];	}

class PDF extends FPDF
{	
	function Footer()
	{    
		$this->SetTextColor(50);
		$this->SetFont('courier','I',11);
		$this->SetY(-12);
		$this->Cell(0,0,'SIACEBG',0,0,'L');
	}	
}

// ENCABEZADO
$pdf=new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(20,18,20);
$pdf->SetAutoPageBreak(1,5);
$pdf->SetTitle('Recibo de Pago');

////////// DATOS
//$ci = 9884788;
$consulta = "SELECT * FROM rac WHERE cedula = '$ci' LIMIT 1;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$registro = $tabla->fetch_object();
// --------------
$rac = $registro->rac;
$codigo = $registro->codigo;
$cedula = $registro->ci;
$empleado = $registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2;
$profesion = $registro->profesion;
$fecha = $registro->fecha_ingreso;
$cuenta = $registro->cuenta;
$banco = $registro->banco;
$profesion = strtoupper($_SESSION['profesion'][$registro->profesion]);
$annos = annos(anno($registro->fecha_ingreso), mes($registro->fecha_ingreso) , anno($hasta), mes($hasta));
$anos_servicio = intval($annos) + intval($registro->anos_servicio);

//$code = generarRuta('13','amfm',substr($cedula, 6, 4), $ci);

$consulta = "SELECT id, sueldo_mensual, desde, hasta, cargo, ubicacion, nomina FROM nomina WHERE (tipo_pago='001' or tipo_pago='003') AND hasta = '$hasta' AND cedula = '$ci' ORDER BY tipo_pago, hasta DESC LIMIT 1;"; //tipo_pago='002' or 
$tabla = $_SESSION['conexionsql']->query($consulta);
$registro = $tabla->fetch_object();
$nomina = $registro->nomina;
$id = $registro->id; 
$cargo = $registro->cargo;
$ubicacion = $registro->ubicacion;
$sueldo = $registro->sueldo_mensual;
$periodo = voltea_fecha($registro->desde).' al '.voltea_fecha($registro->hasta);
$hasta = $registro->hasta;
// ----------
$pdf->AddPage();
$pdf->SetFillColor(190);
//$pdf->Image('../../images/personal.png',195,19,50);
$pdf->Image('../../images/logo_nuevo.jpg',45,14,30);
$pdf->Image('../../images/todos.jpg',190,260,12);
$pdf->Image('../../images/bandera_linea.png',0,0,280,1);
$pdf->Image('../../images/bandera_linea.png',0,215,280,1);

//$instituto = instituto();
$pdf->SetFont('courier','I',10);
$pdf->SetX(91);
$pdf->Cell(98,5,'República Bolivariana de Venezuela',0,0,'C'); 
$pdf->Ln(5);
$pdf->SetX(91);
$pdf->Cell(98,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); 
$pdf->Ln(5);
$pdf->SetX(91);
$pdf->Cell(98,5,'Dirección de Talento Humano',0,0,'C'); 
$pdf->Ln(10);

$pdf->SetX(190);
$pdf->SetFont('courier','',11.5);
$pdf->Cell(18,5,'Fecha:',0,0,'L',0); //$pdf->Ln(17);
$pdf->SetFont('courier','B',12);
$pdf->Cell(0,5,voltea_fecha(date('Y/m/d')),0,0,'C',0); 
$pdf->Ln(1);

$pdf->SetFont('courier','BIU',17);
$pdf->Cell(0,5,'RECIBO DE PAGO',0,0,'C'); 
$pdf->Ln(10);

$pdf->SetFont('courier','',11.5);
$pdf->Cell(35,7,'Fecha Ingreso:',0,0,'L',0); //$pdf->Ln(17);
$pdf->SetFont('courier','B',12);
$pdf->Cell(35,7,voltea_fecha($fecha),0,0,'C',0); 

$pdf->SetFont('courier','',11.5);
$pdf->Cell(22,7,'Nomina:',0,0,'L',0); //$pdf->Ln(17);
$pdf->SetFont('courier','B',12);
$pdf->Cell(0,7,($nomina),0,0,'L',0); 
$pdf->Ln();

$pdf->SetFont('courier','',11.5);
$pdf->Cell($a=30,8,'Cedula',1,0,'C',0); //$pdf->Ln(17);
$pdf->Cell($b=80,8,'Apellidos y Nombres',1,0,'C',0); //$pdf->Ln(17);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->MultiCell($c=24,4,"Codigo de Nomina",1,'C');
$pdf->SetY($y);
$pdf->SetX($x+$c);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->MultiCell($d=60,4,"Años de Servicio en la Administracion Publica",1,'C');
$pdf->SetY($y);
$pdf->SetX($x+$d);
$pdf->Cell(0,8,'Sueldo Mensual',1,1,'C',0); //$pdf->Ln(17);

$pdf->SetFont('courier','B',10);
$pdf->Cell($a,5,formato_ci($cedula),1,0,'C',0); 
$pdf->Cell($b,5,($empleado),1,0,'C',0); 
$pdf->Cell($c,5,($codigo),1,0,'C',0); 
$pdf->Cell($d,5,($anos_servicio),1,0,'C',0); 
$pdf->Cell(0,5,formato_moneda($sueldo),1,0,'C',0); 
$pdf->Ln(7);

$pdf->SetFont('courier','B',11);
$pdf->Cell($b=120,6,'Concepto',1,0,'C',0); //$pdf->Ln(17);
$pdf->Cell($c=60,6,'Asignaciones',1,0,'C',0); //$pdf->Ln(17);
$pdf->Cell(0,6,'Deducciones',1,0,'C',0); 
$pdf->Ln(7);

$pdf->SetFont('courier','',10);
$i=0;
////////// DATOS
$consulta = "SELECT nomina.desde, nomina.hasta, nomina.tipo_pago, a_asignaciones.decripcion, nomina_asignaciones.asignaciones, nomina.cedula FROM nomina , nomina_asignaciones , a_asignaciones WHERE nomina.hasta='$hasta' AND nomina_asignaciones.id_nomina = nomina.id AND nomina_asignaciones.id_asignacion = a_asignaciones.id AND a_asignaciones.id IN (SELECT id FROM a_asignaciones WHERE activo=1) AND nomina_asignaciones.cedula = '$ci' ORDER BY a_asignaciones.id;";// nomina.id=$id AND nomina.tipo_pago='001' AND
$tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
while ($registro = $tabla->fetch_object())
	{
	$i++;
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(235);}
	$pdf->Cell($b,6,$registro->decripcion,0,0,'L',1); //$pdf->Ln(17);
	$pdf->Cell($c,6,formato_moneda($registro->asignaciones),0,0,'R',1); //$pdf->Ln(17);
	$pdf->Cell(0,6,formato_moneda(0),0,1,'R',1); //$pdf->Ln(17);
	$asignaciones = $asignaciones + $registro->asignaciones;
	}

////////// DATOS
$consulta = "SELECT nomina.desde, nomina.hasta, nomina.tipo_pago, a_descuentos.decripcion, nomina_descuentos.descuento, nomina.cedula FROM nomina , nomina_descuentos , a_descuentos  WHERE nomina.id=$id AND (tipo_pago='001' or tipo_pago='003') AND nomina_descuentos.id_nomina = nomina.id AND nomina_descuentos.id_descuento = a_descuentos.id AND nomina.cedula = '$ci' ORDER BY a_descuentos.id ASC;"; //or tipo_pago='002' 
//echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
while ($registro = $tabla->fetch_object())
	{
	$i++;
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(235);}
	$pdf->Cell($b,6,$registro->decripcion,0,0,'L',1); //$pdf->Ln(17);
	$pdf->Cell($c,6,formato_moneda(0),0,0,'R',1); //$pdf->Ln(17);
	$pdf->Cell(0,6,formato_moneda($registro->descuento),0,1,'R',1); //$pdf->Ln(17);
	$descuentos = $descuentos + $registro->descuento;
	}

$pdf->SetFillColor(235);
$pdf->SetFont('courier','B',10);
$pdf->Cell($b,6,'Total de Asignaciones y Deducciones en Bs => ',0,0,'R',0); //$pdf->Ln(17);
$pdf->Cell($c,6,formato_moneda($asignaciones),0,0,'R',0); //$pdf->Ln(17);
$pdf->Cell(0,6,formato_moneda($descuentos),0,1,'R',0); //$pdf->Ln(17);

$pdf->Cell($b,6,'',0,0,'R',0); //$pdf->Ln(17);
$pdf->Cell($c,6,'Monto Total a Cobrar =>',0,0,'R',1); //$pdf->Ln(17);
$pdf->SetFont('courier','B',10);
$pdf->Cell(0,6,formato_moneda($asignaciones-$descuentos),0,1,'R',1); //
$pdf->Ln(10);


$pdf->SetFont('courier','I',11);
$pdf->Cell(25,5,'Período:',0,0,'L'); 
$pdf->SetFont('courier','B',11);
$pdf->Cell(0,5,$periodo,0,0,'L'); 
$pdf->Ln();

$pdf->SetFont('courier','I',11);
$pdf->Cell(63,5,'Ubicacion Administrativa:',0,0,'L'); 
$pdf->SetFont('courier','B',11);
$pdf->Cell(0,5,'CEBG',0,0,'L'); 
$pdf->Ln();

$pdf->SetFont('courier','I',11);
$pdf->Cell(65,5,'Dependencia de Adscripcion:',0,0,'L'); 
$pdf->SetFont('courier','B',11);
$pdf->Cell(0,5,$ubicacion,0,0,'L'); 
$pdf->Ln();

$pdf->SetFont('courier','I',11);
$pdf->Cell(25,5,'Cargo:',0,0,'L'); 
$pdf->SetFont('courier','B',11);
$pdf->Cell(0,5,$cargo,0,0,'L'); 
$pdf->Ln();

$pdf->SetFont('courier','I',11);
$pdf->Cell(25,5,'Banco:',0,0,'L'); 
$pdf->SetFont('courier','B',11);
$pdf->Cell(0,5,$banco,0,0,'L'); 
$pdf->Ln();

$pdf->SetFont('courier','I',11);
$pdf->Cell(25,5,'Cuenta:',0,0,'L'); 
$pdf->SetFont('courier','B',11);
$pdf->Cell(0,5,formato_cuenta($cuenta),0,0,'L'); 
$pdf->Ln();



$pdf->SetY(-44);
$pdf->SetFont('Times','B',13);
$pdf->SetX(170); $pdf->Cell(0,5,'_________________',0,0,'C'); $pdf->Ln(7);
$pdf->SetX(170); $pdf->Cell(0,5,'Ramon Emilio Padrino Arvelaez',0,0,'C'); $pdf->Ln(6);
$pdf->SetX(170); $pdf->SetFont('Times','B',10);
$pdf->SetX(170); $pdf->Cell(0,5,'Director (E) de Talento Humano',0,0,'C'); $pdf->Ln(5);

$pdf->Image('../../images/firma_rrhh.png',148,135,80);

// FIN
	
$pdf->Output();
?>