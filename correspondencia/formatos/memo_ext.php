<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

class PDF extends FPDF
{
	function Footer()
	{    
		//$this->SetFont('Times','I',8);
//		$this->SetY(-18);
//		$this->SetTextColor(120);
//		//--------------
//		$this->Cell(80,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
//		//$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
	}	
}

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(37,25,30);
$pdf->SetDisplayMode($zoom=='real');
$pdf->SetAutoPageBreak(1, $margin=10);
$pdf->SetTitle('Memorando');

//--- COMIENZO DEL MEMO
$pdf->AddPage();
setlocale(LC_TIME, 'sp_ES','sp', 'es');

$tamaño = 0;
$id = decriptar($_GET['id']);

// DATOS DEL MEMO
$consulta = "SELECT * FROM vista_memorando_ext WHERE id=$id"; //ECHO $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($registro = $tabla->fetch_object())
	{
	//----------------------
	$firma = $registro->firma_contralor;
	if ($firma==0)
		{
		$jefe_origen = $registro->nombre;
		$cargo = strtoupper($registro->cargo);	
		$providencia = $registro->providencia;
		$fecha_prov = $registro->fecha_prov;
		}
	else
		{
		$consultax = "SELECT CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, a_direcciones.cargo, a_direcciones.providencia, a_direcciones.fecha_not, a_direcciones.fecha_prov, a_direcciones.gaceta, a_direcciones.fecha_gaceta FROM cr_memos_dir_ext,	rac,	a_direcciones WHERE cr_memos_dir_ext.firma_contralor = a_direcciones.id AND	cr_memos_dir_ext.ci_contralor = rac.cedula AND cr_memos_dir_ext.id=$id"; //ECHO $consulta;
		$tablax = $_SESSION['conexionsql']->query($consultax);
		$registrox = $tablax->fetch_object();
		//------------
		$jefe_origen = $registrox->nombre;
		$cargo = strtoupper($registrox->cargo);	
		$providencia = $registrox->providencia;
		$fecha_prov = $registrox->fecha_prov;
		}
	
	$destinatario = $registro->destinatario;
	$idoficina = $registro->direccion_origen;
	$oficina = $registro->oficina;
	$instituto = $registro->instituto;
	$dir = $registro->direccion;

	$fecha = $registro->fecha;
	$numero = $registro->numero;
	$anno = $registro->anno;
	$asunto = $registro->asunto;
	$cuerpo = $registro->cuerpo;
	//$siglas = $registro->siglas1;
	
	// ---------------------
	$pdf->Image('../../images/escudog.png',40,8,30);
	$pdf->Image('../../images/logo_nuevo.jpg',150,8,30);
	$pdf->SetFont('Times','B',12); $pdf->Ln(18);

//	$txt = $siglas.$anno.'/'.rellena_cero($numero,6);
//	$pdf->Cell(0,5,$txt);
//	$pdf->Ln(8);

	$pdf->SetFont('Times','B',13); $pdf->Ln(3);

	$txt = 'OFICIO '.rellena_cero($idoficina,2).'-'.rellena_cero($numero,4).'-'.$anno;
	$pdf->Cell(0,5,$txt,0,0,'R');
	$pdf->Ln(10);

	$pdf->SetFont('Times','',11);
	$pdf->Cell(0,5,'Sres. (a):',0,1);
	$pdf->SetFont('Times','B',11); 
	$pdf->Cell(0,5,$destinatario,0,1);
	$pdf->SetFont('Times','',11); 
	$pdf->Cell(0,5,$instituto,0,1);
	$pdf->Cell(0,5,$dir,0,1);
	$txt = 'Su Despacho:';
	$pdf->Cell(25,5,$txt,0,1);
	//$pdf->Ln();

	$pdf->SetFont('Times','B',11);
	$pdf->Cell(0,5,"Fecha: ".voltea_fecha($fecha),0,1,'R');
	
//	$txt = 'ASUNTO:';
//	$pdf->Cell(25,5,$txt);
//	$txt = $asunto;
//	$pdf->Cell(0,5,$txt);
//	$pdf->Ln(12);

	$pdf->Ln();
	
	$pdf->SetFont('Times','',12-$tamaño);

	$txt=$cuerpo;

	$pdf->MultiCell(0,5,$txt);
	$pdf->Ln(4);

	$txt='Sin otro particular al cual hacer referencia, se despide';
	$pdf->MultiCell(0,5,$txt);

	$pdf->SetY(-55);

//	$txt='Atentamente,';
//	$pdf->MultiCell(0,5,$txt,0,'C');

	// FIRMA DEL JEFE

	//$pdf->Ln(10);
	//---------------------------------
	//$pdf->Ln(4);
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,4.5,$jefe_origen,0,1,'C'); //$pdf->Ln(5);
	$pdf->SetFont('Times','B',8);
	$pdf->Cell(0,4.5,$cargo,0,1,'C'); //$pdf->Ln(5);
	$pdf->Cell(0,4.5,'Contraloria del Estado Bolivariano de Guarico',0,1,'C'); //$pdf->Ln(5);
	$pdf->SetRightMargin(70);
	$pdf->SetLeftMargin(70);
	$pdf->SetFont('Times','',8);
	$pdf->MultiCell(0,4,$providencia,0,'C');
	$pdf->Cell(0,4.5,'de fecha '.voltea_fecha($fecha_prov),0,0,'C'); $pdf->Ln(5);	//-----------
	$pdf->Ln(5);
	
	//$pdf->SetLeftMargin(22);
	//$pdf->SetRightMargin(17);

	$pdf->SetFont('Times','',8.5);
	$pdf->Cell(0,5,'"HACIA LA CONSOLIDACIÓN Y FORTALECIMIENTO DEL SISTEMA NACIONAL DE CONTROL FISCAL"',0,0,'C');
	$pdf->Ln(3);
	$pdf->Cell(0,5,'San Juan de los Morros, Calle Mariño, Edificio Don Vito Piso 1, 2 y 4 entre Av. Bolivar y Av. Monseñor Sendrea.',0,0,'C');
	$pdf->Ln(3);
	$pdf->Cell(0,5,'Telf: (0246) 432.14.33 email: controlguarico01@hotmail.com - web: www.cebg.com.ve',0,0,'C');
	//$pdf->Ln(3);
	//$pdf->Cell(0,5,'R.I.F. G-20001287-0',0,0,'C');

	$pdf->Output();
	}
else
{echo 'Error: No existen datos.';}
?>