<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$num_nomina = decriptar($_POST['num']);
//-------------
$consultx = "DELETE FROM nomina WHERE id_solicitud='$num_nomina';";
$tablx = $_SESSION['conexionsql']->query($consultx);	
$consultx = "DELETE FROM nomina_solicitudes WHERE id='$num_nomina';";
$tablx = $_SESSION['conexionsql']->query($consultx);	
$consultx = "DELETE FROM nomina_descuentos WHERE id_nomina NOT IN (SELECT id FROM nomina);";
$tablx = $_SESSION['conexionsql']->query($consultx);	
$consultx = "DELETE FROM nomina_asignaciones WHERE id_nomina NOT IN (SELECT id FROM nomina);";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------	
$mensaje = "Nomina Eliminada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>