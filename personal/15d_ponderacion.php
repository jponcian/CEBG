<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = $_POST['id'];
//-------------	
$consultx = "SELECT rango FROM eval_ponderaciones WHERE minimo <= $id AND maximo >= $id;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object(); 
	$mensaje = $registro->rango;
//-------------		

$info = array ("tipo"=>$tipo, "msg"=>$mensaje );

echo json_encode($info);
?>