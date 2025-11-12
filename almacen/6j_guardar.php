<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
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
	$consultx = "UPDATE bn_solicitudes SET estatus = 10, fecha_aprobacion = '".date('Y-m-d')."', aprobador = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = $id;";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	$consultx = "UPDATE bn_solicitudes_detalle, bn_materiales SET estatus = 10, inventario=inventario-bn_solicitudes_detalle.cant_aprobada WHERE bn_materiales.id_bien=bn_solicitudes_detalle.id_bien AND bn_solicitudes_detalle.id_solicitud = $id;";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//-------------	
	$mensaje = "Solicitud Despachada Exitosamente!";
//	}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>