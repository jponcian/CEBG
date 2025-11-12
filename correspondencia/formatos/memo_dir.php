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
		$this->SetFont('Times','I',8);
		$this->SetY(-18);
		$this->SetTextColor(120);
		//--------------
		$this->Cell(80,0,$_SESSION['CEDULA_USUARIO'],0,0,'L');
		//$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de {nb}',0,0,'R');
	}	
}

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(22,25,17);
$pdf->SetDisplayMode($zoom=='real');
$pdf->SetAutoPageBreak(1, $margin=10);
$pdf->SetTitle('Memorando');

//--- COMIENZO DEL MEMO
$pdf->AddPage();
setlocale(LC_TIME, 'sp_ES','sp', 'es');

$tamaño = 0;
$id = decriptar($_GET['id']);
	
$consulta = "SELECT * FROM vista_memorando_div WHERE id=$id"; //ECHO $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($registro = $tabla->fetch_object())
	{
	//----------------------
	$jefe_origen = $registro->nombre;
	$jefe_destino = $registro->nombre2;
	$direccion_origen = $registro->direccion;
	$direccion_destino = $registro->direccion2;
	$jefe = $registro->ci_jefe_destino;
	$cargo_origen = strtoupper($registro->cargo);
	$cargo_destino = strtoupper($registro->cargo2);
	$fecha = $registro->fecha;
	$numero = $registro->numero;
	$anno = $registro->anno;
	$asunto = $registro->asunto;
	$cuerpo = $registro->cuerpo;
	$providencia = $registro->providencia;
	$fecha_prov = $registro->fecha_prov;
	$siglas = $registro->siglas1;
	
	if ($jefe==$_SESSION['CEDULA_USUARIO'])
		{
		$consulta = "UPDATE cr_memos_div_destino SET usuario_recepcion = '".$_SESSION['CEDULA_USUARIO']."', fecha_recepcion = '".date('Y-m-d')."', estatus_recepcion = 10 WHERE id=$id"; //ECHO $consulta;
		$tabla = $_SESSION['conexionsql']->query($consulta);
		}
	// ---------------------
	$pdf->Image('../../images/logo_nuevo.jpg',20,8,35);
	$pdf->SetFont('Times','B',12); $pdf->Ln(18);

	$txt = $siglas.'-'.$anno.'-'.rellena_cero($numero,6);
	$pdf->Cell(0,5,'NUMERO '.$txt,0,0,'R');
	$pdf->Ln(8);

	$pdf->SetFont('Times','B',13); $pdf->Ln(3);

	$txt = 'M E M O R A N D O';
	$pdf->Cell(0,5,$txt,0,0,'C');
	$pdf->Ln(8);

	$pdf->SetFont('Times','B',11); $pdf->Ln(8);

	$txt = 'PARA:';
	$pdf->Cell(25,5,$txt);
	$pdf->Cell(0,5,$jefe_destino);
	$pdf->Ln();

	$txt = '';
	$pdf->Cell(25,5,$txt);
	$pdf->Cell(0,5,$cargo_destino);
	$pdf->Ln(8);

	$txt = 'DE:';
	$pdf->Cell(25,5,$txt);
	$pdf->Cell(0,5,$cargo_origen);
	$pdf->Ln(8);

	$txt = 'FECHA:';
	$pdf->Cell(25,5,$txt);
	$txt = voltea_fecha($fecha);
	$pdf->Cell(0,5,$txt);
	$pdf->Ln(8);

	$txt = 'ASUNTO:';
	$pdf->Cell(25,5,$txt);
	$txt = $asunto;
	$pdf->Cell(0,5,$txt);
	$pdf->Ln(12);

	$pdf->SetFont('Times','',12-$tamaño);

	$txt=$cuerpo;

	$pdf->MultiCell(0,5,$txt);
	$pdf->Ln(4);

	$txt='Sin otro particular al cual hacer referencia, se despide';
	$pdf->MultiCell(0,5,$txt);

	$pdf->SetY(-75);

	$txt='Atentamente,';
	$pdf->MultiCell(0,5,$txt,0,'C');

	// FIRMA DEL JEFE

	$pdf->Ln(10);
	//---------------------------------
	$pdf->Ln(4);
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,5,$jefe_origen,0,0,'C'); $pdf->Ln(5);
	$pdf->SetFont('Times','B',8);
	$pdf->Cell(0,5,$cargo_origen,0,0,'C'); $pdf->Ln(5);
	$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guarico',0,0,'C'); $pdf->Ln(5);
	$pdf->SetRightMargin(70);
	$pdf->SetLeftMargin(70);
	$pdf->SetFont('Times','',8);
	$pdf->MultiCell(0,4,$providencia,0,'C');
	$pdf->Cell(0,5,'de fecha '.voltea_fecha($fecha_prov),0,0,'C'); $pdf->Ln(5);	//-----------

	$pdf->SetLeftMargin(22);
	$pdf->SetRightMargin(17);

	// USUARIO QUE HIZO LA REASIGNACION
	//list ($funcionario, $rol, $origen, $rol2, $origen2) = funcion_funcionario($funcionario);

	$pdf->Ln(5);
	//$txt = extraer_iniciales($jefe).'/'.strtolower(extraer_iniciales($funcionario));
	//$pdf->MultiCell(0,5,$txt,0,'L');
	// FIN

	$pdf->Output();
	}
else
{echo 'Error: No existen datos.';}
?>