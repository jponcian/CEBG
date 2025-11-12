<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$info = array();
$carnet = ($_POST['id']);
//---------
$consultx = "SELECT cedula FROM asistencia_diaria_visita WHERE carnet='$carnet' AND (estatus=0 or estatus=2);"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{
	//--------
	$tipo = 'SALIDA';
	//--------
	}
else
	{
	//--------
	$tipo = 'ENTRADA';
	//--------
	}

//-------
$info = array ("tipo"=>$tipo);
echo json_encode($info);
?>