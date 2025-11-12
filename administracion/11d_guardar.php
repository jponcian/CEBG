<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id_pago = decriptar($_POST['id_pago']);
$id_solicitud = decriptar($_POST['id_solicitud']);
$estatus = ($_POST['estatus']);
//-------------

$consultax = "UPDATE orden SET estatus = 0, id_solicitud=0 WHERE id_solicitud = $id_solicitud;";
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$consulta_x = "UPDATE orden_solicitudes SET descripcion='A.N.U.L.A.D.A', estatus=99, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_orden_pago = $id_pago;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
//$consulta_x = "DELETE FROM ordenes_pago WHERE id = $id_pago;"; 
$consulta_x = "UPDATE ordenes_pago SET descripcion='A.P.A.R.T.A.D.A', estatus=99, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = $id_pago;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$consulta_x = "DELETE FROM ordenes_pago_descuentos WHERE id_orden_pago = $id_pago;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$mensaje = "Solicitud y Orden de Pago Reversada!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$consultax);
echo json_encode($info);
?>