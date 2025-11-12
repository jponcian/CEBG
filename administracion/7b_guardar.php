<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id_pago = decriptar($_POST['id_pago']);
$id_solicitud = decriptar($_POST['id_solicitud']);
//-------------
$consultax = "UPDATE orden SET estatus=99, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_solicitud='$id_solicitud';"; 
$tablax = $_SESSION['conexionsql']->query($consultax);
//---------
$consulta_x = "UPDATE orden_solicitudes SET estatus=99, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = $id_solicitud;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$consulta_x = "UPDATE ordenes_pago SET estatus=99, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = $id_pago;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$consulta_x = "DELETE FROM ordenes_pago_descuentos WHERE id_orden_pago = $id_pago;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$mensaje = "Orden Anulada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>