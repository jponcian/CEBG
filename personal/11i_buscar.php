<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$consultx = "SELECT descripcion, desde, hasta FROM nomina WHERE tipo_pago='008' AND estatus=0;";
$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
if ($tablx->num_rows>0)
	{
	$registro = $tablx->fetch_object();
	$desde = voltea_fecha($registro->desde);
	$concepto = ($registro->descripcion);
	}
else
	{
	$desde = date('d/m/Y');
	$concepto = '';
	}
//-------------	

$info = array ("tipo"=>$tipo, "desde"=>$desde, "concepto"=>$concepto);

echo json_encode($info);
?>