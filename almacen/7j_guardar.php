<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
//-------------
$consultx = "SELECT bn_ingresos_detalle.division FROM bn_ingresos_detalle WHERE bn_ingresos_detalle.id_bien = '$id' AND estatus = 0 GROUP BY bn_ingresos_detalle.division LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$registrox = $tablx->fetch_object();
	$division = $registrox->division;
	//-------------	
	$consultx = "INSERT INTO bn_ingresos(solicitante, memo, anno, numero, fecha, division, usuario) VALUES ( '".$_SESSION['CEDULA_USUARIO']."', '".memo_ing($division)."', '".date('Y')."', '".num_ing()."', '".date('Y-m-d')."', '$division', '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
	//-------------	
	$consultx = "UPDATE bn_ingresos_detalle, bn_materiales SET inventario = inventario + bn_ingresos_detalle.cantidad , id_ingreso = $id, estatus = 10, fecha = '".date('Y-m-d')."', bn_ingresos_detalle.usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE bn_ingresos_detalle.id_bien = bn_materiales.id_bien AND id_ingreso = 0 AND estatus = 0 AND division = $division;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Solicitud Generada Exitosamente!";
	}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>