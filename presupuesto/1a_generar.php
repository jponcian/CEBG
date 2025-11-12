<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
$_SESSION['conexionsql']->query("SET NAMES 'utf8'");

//-----------
$anno = $_SESSION['anno'] ;
$categoria = $_SESSION['categoria'] ;
$partida = $_SESSION['partida'] ;
$largog = strlen($categoria);
$largop = strlen($partida);
$partidas_en_negativo='no';
$i=3;

//error_reporting(E_ALL);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once '../lib/PHPExcel-1.8/Classes/PHPExcel.php';
//-------------------------------------------------------------------
if (!file_exists("formato_excel.xlsx")) {
	exit("Archivo Base de Excel no encontrado..." . EOL);
}

$objPHPExcel = PHPExcel_IOFactory::load("formato_excel.xlsx");

//------------ 1RA CONSULTA
$consultx = "SELECT sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE left(categoria,$largog) = '$categoria' AND left(codigo,$largop) = '$partida' ORDER BY id;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registrx = $tablx->fetch_object();

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('D'.$i, ($registrx->original))
			->setCellValue('E'.$i, ($registrx->ingreso))
			->setCellValue('F'.$i, ($registrx->egreso))	
			->setCellValue('G'.$i, ($registrx->creditos))	
			->setCellValue('H'.$i, ($registrx->modificado))	
			->setCellValue('I'.$i, ($registrx->compromiso))	
			->setCellValue('J'.$i, ($registrx->causado))	
			->setCellValue('K'.$i, ($registrx->pagado))	
			->setCellValue('L'.$i, ($registrx->disponible));	

if ($largop>0)
	{
	$aux = "AND codigo IN (SELECT categoria FROM a_presupuesto_$anno WHERE left(codigo,$largop)='$partida')";
	}
else
	{
	$aux = "";
	}
//----------
$consulta = "SELECT codigo, descripcion FROM a_presupuesto_$anno WHERE left(codigo,$largog)='$categoria' AND categoria IS NULL $aux GROUP BY codigo ORDER BY id;";
$tabla = $_SESSION['conexionsql']->query($consulta);
//echo $consulta;
//---- PRIMER CICLO
while ($registro = $tabla->fetch_object())
	{
	$categoria = $registro->codigo;
	$i++;
	//$j=0;
	$consultx = "SELECT sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE categoria='$categoria' AND left(codigo,$largop)='$partida' ORDER BY id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//echo $consulta;
	$registrx = $tablx->fetch_object();
	//---------------------
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, ($registro->codigo))
			->setCellValue('C'.$i, ($registro->descripcion))
			->setCellValue('D'.$i, ($registrx->original))
			->setCellValue('E'.$i, ($registrx->ingreso))
			->setCellValue('F'.$i, ($registrx->egreso))	
			->setCellValue('G'.$i, ($registrx->creditos))	
			->setCellValue('H'.$i, ($registrx->modificado))	
			->setCellValue('I'.$i, ($registrx->compromiso))	
			->setCellValue('J'.$i, ($registrx->causado))	
			->setCellValue('K'.$i, ($registrx->pagado))	
			->setCellValue('L'.$i, ($registrx->disponible));	

	$consultx = "SELECT * FROM a_presupuesto_$anno WHERE categoria='$categoria' AND left(codigo,$largop)='$partida' ORDER BY id;";
	//echo $consultx.'<br>';
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registrx = $tablx->fetch_object())
		{
		$i++;
		//---------------------
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, ($registrx->categoria))
			->setCellValue('B'.$i, ($registrx->codigo))
			->setCellValue('C'.$i, ($registrx->descripcion))
			->setCellValue('D'.$i, ($registrx->original))
			->setCellValue('E'.$i, ($registrx->ingreso))
			->setCellValue('F'.$i, ($registrx->egreso))	
			->setCellValue('G'.$i, ($registrx->creditos))	
			->setCellValue('H'.$i, ($registrx->modificado))	
			->setCellValue('I'.$i, ($registrx->compromiso))	
			->setCellValue('J'.$i, ($registrx->causado))	
			->setCellValue('K'.$i, ($registrx->pagado))	
			->setCellValue('L'.$i, ($registrx->disponible));
		}
	}
//---- FIN

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="presupuesto_'.date('d-m-Y').'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
