<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = $_POST['id'];
$estatus = $_POST['estatus'];

if ($estatus=='0')
	{
	$consultx = "UPDATE eval_odis SET estatus = 1 WHERE id = '$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	}
else
	{
	//-------------
	$consultx = "UPDATE eval_odis SET estatus = 0 WHERE id = '$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------	
	}
$mensaje = "ODI Actualizado Exitosamente!";
//-------------

$info = array ("tipo"=>$tipo, "msg"=>$mensaje);

echo json_encode($info);
?>