<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$consulta_x = "UPDATE ordenes_pago SET numero = 0".trim($_POST['txt_numero']).", fecha = '".voltea_fecha($_POST['txt_fecha'])."', usuario=".$_SESSION['CEDULA_USUARIO']." WHERE id = ".($_POST['oid']).";";  //echo $consulta_x;
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$consulta_x = "UPDATE orden_solicitudes SET num_orden_pago = 0".trim($_POST['txt_numero'])." WHERE id = ".($_POST['oid']).";";  //echo $consulta_x;
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$consultx = "SELECT id_ret, a_retenciones.id, a_retenciones.decripcion, ordenes_pago_retencion.numero, ordenes_pago_retencion.fecha FROM ordenes_pago_retencion, a_retenciones WHERE ordenes_pago_retencion.id_tipo = a_retenciones.id AND ordenes_pago_retencion.id_op = ".($_POST['oid']).";"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	//-------------	
	$consulta_x = "UPDATE ordenes_pago_retencion SET numero = '".($_POST['txt_num_'.$registro->id_ret])."', fecha = '".voltea_fecha($_POST['txt_fecha_'.$registro->id_ret])."' WHERE id_ret = ".$registro->id_ret.";";  //echo $consulta_x;
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
	//-------------	
	}
//-------------	
$mensaje = "Orden Actualizada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consulta_x);
echo json_encode($info);
?>