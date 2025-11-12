<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$cedula = $_SESSION['CEDULA_USUARIO'];

//-------------	ODIS
$consultau = "UPDATE eval_asignacion SET estatus = 9, fecha_aceptado = CURDATE(), usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE estatus=7 AND cedula='$cedula';"; 
$tablau = $_SESSION['conexionsql']->query($consultau);	
//-------------	

//-------------	COMPETENCIAS
$consultau = "UPDATE eval_asignacion_comp SET estatus = 9, fecha_aceptado = CURDATE(), usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE estatus=7 AND cedula='$cedula';"; 
$tablau = $_SESSION['conexionsql']->query($consultau);	
//-------------

$consultx = "UPDATE rac SET odis=9 WHERE cedula = '$cedula';";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------	

$mensaje = "Evaluación Aceptada Exitosamente!";
//-------------	

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultau );

echo json_encode($info);
?>