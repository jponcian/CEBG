<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$cedula = decriptar($_POST['cedula']);
$id_evaluacion = decriptar($_POST['id_evaluacion']);

//-------------	ODIS
$consultau = "UPDATE eval_asignacion SET peso = '0', puntaje = '0', total = '0', estatus = 5, fecha_evaluados = CURDATE(), usuario_evaluacion = '".$_SESSION['CEDULA_USUARIO']."' WHERE cedula = $cedula AND id_evaluacion=$id_evaluacion;"; 
$tablau = $_SESSION['conexionsql']->query($consultau);	

echo $consultau;
//-------------	COMPETENCIAS
$consultx = "DELETE FROM eval_asignacion_comp WHERE cedula = $cedula AND id_evaluacion=$id_evaluacion;";
$tablx = $_SESSION['conexionsql']->query($consultx);

//-------------
$consultx = "UPDATE rac SET odis = 5 WHERE cedula = '$cedula';"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------

$mensaje = "Evaluación Actualizada Exitosamente!";
//-------------	

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultau );

echo json_encode($info);
?>