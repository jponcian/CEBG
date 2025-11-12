<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$cedula = $_GET['id'];
//$fecha = (voltea_fecha($_GET['fecha']));
//----------------
$consultx = "SELECT cedula FROM rac WHERE cedula LIKE '%$cedula%' ;";//AND fecha_nacimiento='$fecha'
$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
if ($tablx->num_rows>0)
	{
	$msg = 'Informacion validada correctamente!';
	$registro = $tablx->fetch_object();
	$cedula = encriptar($registro->cedula);
	}
else
	{
	$msg = 'Informacion Incorrecta';
	$tipo = 'alerta';
	}
//-------------	
$info = array ("tipo"=>$tipo, "msg"=>$msg, "cedula"=>$cedula, "consultx"=>$consultx);
echo json_encode($info);
?>