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
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(15,20,15);
$pdf->SetAutoPageBreak(1,5);
$pdf->SetTitle('ARC');

////////// DATOS
$consulta = "SELECT * FROM rac WHERE cedula = '$ci' LIMIT 1;"; //echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$registro = $tabla->fetch_object();
// --------------
$cedula = $registro->ci;
$empleado = $registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2;
$profesion = $registro->profesion;
$cargo = $registro->cargo;
$ubicacion = $registro->ubicacion;
$fecha = $registro->fecha_ingreso;
$cuenta = $registro->cuenta;
$nomina = $registro->nomina;
$profesion = strtoupper($_SESSION['profesion'][$registro->profesion]);

$anno = 2023;
while ($anno<=date("Y"))
{
// ----------
$pdf->AddPage();
$pdf->SetFillColor(190);
//$pdf->Image('../../images/personal.png',143,19,50);
$pdf->Image('../../images/logo_nuevo.jpg',27,14,30);
$pdf->Image('../../images/todos.jpg',190,260,12);
$pdf->Image('../../images/bandera_linea.png',0,0,216,1);
$pdf->Image('../../images/bandera_linea.png',0,278,216,1);

//$instituto = instituto();
$pdf->SetFont('Times','I',11);
$pdf->SetX(51);
$pdf->Cell(98,5,'República Bolivariana de Venezuela',0,0,'C'); 
$pdf->Ln(5);
$pdf->SetX(51);
$pdf->Cell(98,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); 
$pdf->Ln(5);
$pdf->SetX(51);
$pdf->Cell(98,5,'Dirección de Talento Humano',0,0,'C'); 
$pdf->Ln(15);

$pdf->SetFont('courier','BI',17);
$pdf->Cell(0,5,"ARC - EJERCICIO FISCAL $anno",0,0,'C'); 
$pdf->Ln(10);
$pdf->SetFont('Times','B',11);
$pdf->SetX(130);
$pdf->Cell(0,5,'PERIODO',0,0,'C'); 
$pdf->Ln();
$pdf->SetX(128);
$pdf->Cell(0,5,"DESDE: 01/01/$anno   HASTA: 31/12/$anno",0,0,'C'); 
$pdf->Ln(7);

$pdf->SetFont('courier','',11.5);
$pdf->Cell(44,5,'NOMINA:',0,0,'L',0); //$pdf->Ln(17);
$pdf->SetFont('courier','B',12);
$pdf->Cell(0,5,($nomina),0,0,'L',0); $pdf->Ln(6);
$pdf->SetFont('courier','',11.5);
$pdf->Cell(44,5,'FUNCIONARIO:',0,0,'L',0); //$pdf->Ln(17);
$pdf->SetFont('courier','B',12);
$pdf->Cell(0,5,($empleado),0,0,'L',0); $pdf->Ln(6);
$pdf->SetFont('courier','',11.5);
$pdf->Cell(44,5,'CEDULA:',0,0,'L',0); //$pdf->Ln(17);
$pdf->SetFont('courier','B',12);
$pdf->Cell(0,5,formato_ci($cedula),0,0,'L',0); $pdf->Ln(6);
$pdf->SetFont('courier','',11.5);
$pdf->Cell(44,5,'CARGO:',0,0,'L',0); //$pdf->Ln(17);
$pdf->SetFont('courier','B',12);
$pdf->Cell(0,5,($cargo),0,0,'L',0); $pdf->Ln(6);
$pdf->SetFont('courier','',11.5);
$pdf->Cell(44,5,'FECHA INGRESO:',0,0,'L',0); //$pdf->Ln(17);
$pdf->SetFont('courier','B',12);
$pdf->Cell(0,5,voltea_fecha($fecha),0,0,'L',0); $pdf->Ln(6);
$pdf->SetFont('courier','',11.5);
$pdf->Ln(4);

$pdf->SetFont('Arial','B',10);
$pdf->Cell($a=30,6,'Mes',1,0,'C',0); //$pdf->Ln(17);
$pdf->Cell($b=32,6,'Salario Básico',1,0,'C',0); //$pdf->Ln(17);
$pdf->Cell($c=26,6,'Primas',1,0,'C',0); //$pdf->Ln(17);
$pdf->Cell($d=24,6,'S.S.O.',1,0,'C',0); //$pdf->Ln(17);
$pdf->Cell($e=24,6,'Paro Forzoso',1,0,'C',0); //$pdf->Ln(17);
$pdf->Cell($d,6,'FAOV',1,0,'C',0); //$pdf->Ln(17);
$pdf->Cell(0,6,'F.P.J.',1,0,'C',0); //$pdf->Ln(17);
$pdf->Ln(7);

$pdf->SetFont('courier','',9);
$i=1;
$remuneracion = 0; $bonificacion = 0;
$remuneraciont = 0; $bonificaciont = 0;
$sueldo = 0; $primas = 0;
$sso = 0; $fp = 0; $lph = 0; $fej = 0;
	
while ($i<=12)
	{
	if ($i%2==0)	{$pdf->SetFillColor(255);} else {$pdf->SetFillColor(235);}
	$pdf->Cell($a,6,$_SESSION['meses_anno'][abs($i)],0,0,'L',1); //$pdf->Ln(17);
	////////// DATOS
	$consulta = "SELECT sum(sueldo) as sueldo, sum(prof) as prof, sum(antiguedad) as antiguedad, sum(hijos) as hijos, sum(sso) as sso, sum(fp) as fp, sum(lph) as lph, sum(fej) as fej FROM nomina WHERE cedula = '$ci' AND tipo_pago = '001' AND month(fecha) = '$i' AND year(fecha) = '$anno';"; //echo $consulta;
	$tabla = $_SESSION['conexionsql']->query($consulta);
	if ($tabla->num_rows>0)
		{
		$registro = $tabla->fetch_object();	
		$sueldo += $registro->sueldo;
		$primas += $registro->prof+$registro->antiguedad+$registro->hijos;
		$sso += $registro->sso;
		$fp += $registro->fp;
		$lph += $registro->lph;
		$fej += $registro->fej;
		$pdf->Cell($b,6,formato_moneda($registro->sueldo),0,0,'R',1); 	
		$pdf->Cell($c,6,formato_moneda($registro->prof+$registro->antiguedad+$registro->hijos),0,0,'R',1); 	
		$pdf->Cell($d,6,formato_moneda($registro->sso),0,0,'R',1); 	
		$pdf->Cell($e,6,formato_moneda($registro->fp),0,0,'R',1); 	
		$pdf->Cell($d,6,formato_moneda($registro->lph),0,0,'R',1); 	
		$pdf->Cell(0,6,formato_moneda($registro->fej),0,0,'R',1); 	
		$pdf->Ln();
		}
	else
		{
		$pdf->Cell($b,6,formato_moneda(0),0,0,'R',1); 	
		$pdf->Cell($c,6,formato_moneda(0),0,0,'R',1); 	
		$pdf->Cell($d,6,formato_moneda(0),0,0,'R',1); 	
		$pdf->Cell($e,6,formato_moneda(0),0,0,'R',1); 	
		$pdf->Cell($d,6,formato_moneda(0),0,0,'R',1); 	
		$pdf->Cell(0,6,formato_moneda(0),0,0,'R',1); 	
		}

	$i++;
	}

$pdf->SetFillColor(235);
$pdf->SetFont('courier','B',10);
$pdf->Cell($a,6,'TOTALES => ',0,0,'R',0); //$pdf->Ln(17);
$pdf->Cell($b,6,formato_moneda($sueldo),0,0,'R',0); //$pdf->Ln(17);
$pdf->Cell($c,6,formato_moneda($primas),0,0,'R',0); //$pdf->Ln(17);
$pdf->Cell($d,6,formato_moneda($sso),0,0,'R',0); //$pdf->Ln(17);
$pdf->Cell($e,6,formato_moneda($fp),0,0,'R',0); //$pdf->Ln(17);
$pdf->Cell($d,6,formato_moneda($lph),0,0,'R',0); //$pdf->Ln(17);
$pdf->Cell(0,6,formato_moneda($fej),0,1,'R',0); //$pdf->Ln(17);
$pdf->Ln();
	
$pdf->SetFont('courier','B',9);
$pdf->Cell(40,7,'TOTAL ASIGNACIONES:',1,0,'R',0); //$pdf->Ln(17);
$pdf->Cell(21,7,formato_moneda($sueldo+$primas),1,0,'R',0); //$pdf->Ln(17);
$pdf->Cell(40,7,'TOTAL DEDUCCIONES:',1,0,'R',0); //$pdf->Ln(17);
$pdf->Cell(17,7,formato_moneda($sso+$fp+$lph+$fej),1,0,'R',0); //$pdf->Ln(17);
$pdf->Cell(45,7,'TOTAL NETO DEVENGADO:',1,0,'R',0); //$pdf->Ln(17);
$pdf->Cell(0,7,formato_moneda($sueldo+$primas-($sso+$fp+$lph+$fej)),1,1,'R',0); //$pdf->Ln(17);

$pdf->SetY(-65);
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,'Atentamente',0,0,'C'); $pdf->Ln(17);
$pdf->SetFont('Times','B',13);
$pdf->Cell(0,5,'_________________',0,0,'C'); $pdf->Ln(7);
$pdf->Cell(0,5,'Ramon Emilio Padrino Arvelaez',0,0,'C'); $pdf->Ln(6);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,'Director (E) de Talento Humano',0,0,'C'); $pdf->Ln(5);
//$pdf->SetFont('Times','',10);
//$pdf->Cell(0,5,'Según designación conferida por el Contralor del Estado',0,0,'C'); $pdf->Ln(4);
//$pdf->Cell(0,5,'Bolivariano de Guárico en Resolución N° 01-084-2020',0,0,'C'); $pdf->Ln(4);
//$pdf->Cell(0,5,'de fecha 03/08/2020, publicada en Gaceta Oficial',0,0,'C'); $pdf->Ln(4);
//$pdf->Cell(0,5,'Extraordinaria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(4);
//$pdf->Cell(0,5,'N° 107 de fecha 03/08/2020',0,0,'C'); 
$pdf->Ln(7);

//$pdf->SetFont('Times','',8.5);
//$pdf->Cell(0,5,'"HACIA LA CONSOLIDACIÓN Y FORTALECIMIENTO DEL SISTEMA NACIONAL DE CONTROL FISCAL"',0,0,'C');
//$pdf->Ln(3);
//$pdf->Cell(0,5,'San Juan de los Morros, Calle Mariño, Edificio Don Vito Piso 1, 2 y 4 entre Av. Bolivar y Av. Monseñor Sendrea.',0,0,'C');
//$pdf->Ln(3);
//$pdf->Cell(0,5,'Telf: (0246) 432.14.33 email: controlguarico01@hotmail.com - web: www.cebg.com.ve',0,0,'C');
//$pdf->Ln(3);
//$pdf->Cell(0,5,'R.I.F. G-20001287-0',0,0,'C');

$pdf->Image('../../images/firma_rrhh.png',42,195,80);
// FIN
$anno++;
}
$pdf->Output();
?>