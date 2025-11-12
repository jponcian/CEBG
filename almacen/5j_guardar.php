<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$estatus = '6';
$id = decriptar($_POST['id']);
//-------------
$consultx = "SELECT aprobado FROM bn_solicitudes_detalle WHERE id_solicitud = $id AND aprobado > 0 LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$estatus = '5';
	}
//-------------
//$consultx = "SELECT aprobado FROM bn_solicitudes_detalle WHERE id_solicitud = $id AND aprobado = 0 LIMIT 1;"; //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//if ($tablx->num_rows>0)
//	{
//	$mensaje = "No ha autorizado todos los materiales!";
//	$tipo = 'alerta';
//	}
//else
//	{
	//-------------	
	$consultx = "UPDATE bn_solicitudes SET estatus = $estatus, fecha_aprobacion = '".date('Y-m-d')."', aprobador = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = $id;";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	$consultx = "UPDATE bn_solicitudes_detalle SET estatus = 5 WHERE id_solicitud = $id;";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//-------------	
	$mensaje = "Solicitud Procesada Exitosamente!";
//	}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>