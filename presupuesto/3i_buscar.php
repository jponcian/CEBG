<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$numero = $_POST['id'];
$fecha = anno(voltea_fecha($_POST['fecha']));
//----------------
$consultx = "SELECT numero, concepto, fecha, tipo_orden FROM credito_adicional_detalle WHERE year(fecha)='$fecha' AND numero=$numero AND estatus=0 LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$registro = $tablx->fetch_object();
	$fecha = voltea_fecha($registro->fecha);
	$concepto = ($registro->concepto);
	$numero = ($registro->numero);
	$tipo_orden = ($registro->tipo_orden);
	}
else
	{
	$tipo = 'alerta';
	}
//-------------	

$info = array ("tipo"=>$tipo, "numero"=>$numero, "fecha"=>$fecha, "tipo_orden"=>$tipo_orden, "concepto"=>$concepto, "consulta"=>$consultx);

echo json_encode($info);
?>