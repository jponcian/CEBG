<?php
session_start();
ob_end_clean();
session_start();

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}

include "../../conexion.php";
include('../../funciones/auxiliar_php.php');
require('../../lib/fpdf/fpdf.php');

class PDF extends FPDF
	{
	function Footer()
		{
		//Posición a 1,5 cm del final
		$this->SetY(-15);
		//Arial itálica 8
		$this->SetFont('Times','I',9);
		//Color del texto en gris
		$this->SetTextColor(120);
		//Número de página
		$this->Cell(0,0,'SIACEBG',0,0,'R');
		}	
	}

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(22,25,17);
$pdf->SetDisplayMode($zoom=='real');
$pdf->SetAutoPageBreak(1, $margin=10);
$pdf->SetTitle('Memorando Reasignacion');

//--- COMIENZO DEL MEMO
$pdf->AddPage();
setlocale(LC_TIME, 'sp_ES','sp', 'es');

$tamaño = 0;
$id_reasignacion = decriptar($_GET['id']);

$i = 0;
$consulta = "SELECT id_reasignacion FROM bn_reasignaciones_detalle WHERE id_reasignacion=".$id_reasignacion;
$tabla = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
while ($registro = $tabla->fetch_object())
	{ $i++;	}
// DATOS DEL MEMO
$consulta = "SELECT bn_reasignaciones.* FROM bn_reasignaciones WHERE bn_reasignaciones.id=".$id_reasignacion;
$tabla = $_SESSION['conexionsql']->query($consulta);
if ($registro = $tabla->fetch_object())
	{
	$funcionario = empleado($registro->usuario);
	$jefe = strtoupper($registro->jefe_actual);
	$cargo = strtoupper($registro->cargo_actual);
	// ---------------------
	$jefe_destino = $registro->jefe_destino;
	$cargo_destino = strtoupper($registro->cargo_destino);

	$pdf->Image('../../images/logo_nuevo.jpg',28,12,25);
	$pdf->SetFont('Times','B',11); $pdf->Ln(15);
	
	$txt = 'SIGLAS/'.$registro->siglas.$registro->anno.'/'.$registro->numero;
	$pdf->Cell(0,5,$txt);
	
	$pdf->SetFont('Times','B',13); $pdf->Ln(8);
	
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
	$pdf->Cell(0,5,$cargo);
	$pdf->Ln(8);
	
	$txt = 'FECHA:';
	$pdf->Cell(25,5,$txt);
	$txt = voltea_fecha($registro->fecha);
	$pdf->Cell(0,5,$txt);
	$pdf->Ln(8);
	
	$txt = 'ASUNTO:';
	$pdf->Cell(25,5,$txt);
	$txt = 'REASIGNACIÓN DE BIENES NACIONALES';
	$pdf->Cell(0,5,$txt);
	$pdf->Ln(12);
	
	$pdf->SetFont('Times','',12-$tamaño);
	
	// POR SI ESTAN ASIGNANDO LOS BIENES
	if ($registro->id==3)
		{	
		// POR SI HAY VARIOS BIENES
		if ($i==1) 
			{
			$txt='Tengo el honor de dirigirme a usted, en la oportunidad de reasignarle, el bien nacional descrito en el comprobante anexo, el cual quedará adscrito al inventario bajo su responsabilidad por lo que se requiere tomar en cuenta las Normas Básicas Generales de Control Interno de los Bienes Nacionales:';
			}
		else
			{
			$txt='Tengo el honor de dirigirme a usted, en la oportunidad de reasignarle, los bienes nacionales descritos en el comprobante anexo, los cuales quedarán adscritos al inventario bajo su responsabilidad por lo que se requiere tomar en cuenta las Normas Básicas Generales de Control Interno de los Bienes Nacionales:';
			}

		$pdf->MultiCell(0,5,$txt);
		$pdf->Ln(4);

		$txt='-Velar por la custodia, el buen uso y mantenimiento de los Bienes a su cargo.
		-El Responsable de los Bienes Muebles (primarios y de uso) en cada unidad de trabajo, responde penal, civil, administrativa y disciplinariamente por las fallas e irregularidades administrativas que cometieran en el manejo de los mismos, conforme a lo establecido en la Ley Orgánica de Bienes Públicos, Ley Orgánica de la Contraloría General de la Republica y del Sistema Nacional de Control Fiscal y su Reglamento.
		-Al momento de dejar un cargo administrativo u operativo, el funcionario saliente deberá presentar un acta de entrega de los bienes asignados al funcionario entrante o jefe inmediato superior.';
		$pdf->MultiCell(0,5,$txt);
		$pdf->Ln(4);

		$txt='En este sentido se le solicita muy respetuosamente la máxima colaboración para dar cumplimiento a la Normativa legal vigente.';
		$pdf->MultiCell(0,5,$txt);
		$pdf->Ln(4); 
		}
	// POR SI ESTAN DEVOLVIENDO LOS BIENES
	else
		{
		$pdf->Ln(12);
		// POR SI HAY VARIOS BIENES
		if ($i==1) 
			{
			$txt='Tengo el honor de dirigirme a usted, en la oportunidad de brindarle un cordial y respetuoso saludo, y a su vez reasignarle, el bien nacional descrito en el comprobante anexo, que se encuentra asignado en esta División según nuestro inventario.';
			}
		else
			{
			$txt='Tengo el honor de dirigirme a usted, en la oportunidad de brindarle un cordial y respetuoso saludo, y a su vez reasignarle, los bienes nacionales descritos en el comprobante anexo, que se encuentran asignados en esta División según nuestro inventario.';
			}

		$pdf->MultiCell(0,5,$txt);
		$pdf->Ln(6);

		}
		
	$txt='Sin otro particular al cual hacer referencia, se despide';
	$pdf->MultiCell(0,5,$txt);
	
	$pdf->SetY(-75);
	
	$txt='Atentamente,';
	$pdf->MultiCell(0,5,$txt,0,'C');
	
	// FIRMA DEL JEFE
	
	$pdf->Ln(10);
	//---------------------------------
	$cedula = "C.I. N° V-" .$registro->cedula_actual;
	$cargo = $registro->cargo_actual;
	$providencia = $registro->providencia_actual;
	$fecha_prov = $registro->fecha_prov_actual;
	$gaceta = $registro->gaceta_actual;
	$fecha_gac = $registro->fecha_gaceta_actual;
	
	//---------------------------------
	$pdf->Ln(4);
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,5,$jefe,0,0,'C'); $pdf->Ln(5);
	$pdf->SetFont('Times','B',8);
	$pdf->Cell(0,5,$cargo,0,0,'C'); $pdf->Ln(5);
	$pdf->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $pdf->Ln(5);
	$pdf->SetRightMargin(70);
	$pdf->SetLeftMargin(70);
	$pdf->SetFont('Times','',8);
	$pdf->MultiCell(0,4,$providencia,0,'C');
	$pdf->Cell(0,5,'de fecha '.voltea_fecha($fecha_prov),0,0,'C'); $pdf->Ln(5);
	//-----------
	
	$pdf->SetLeftMargin(22);
	$pdf->SetRightMargin(17);

	$pdf->Ln(5);
	$txt = extraer_iniciales($jefe).'/'.strtolower(extraer_iniciales($funcionario[1]));
	$pdf->MultiCell(0,5,$txt,0,'L');
	// FIN
	
	$pdf->Output();
}
else
{echo 'Error: No existen datos.';}
?>