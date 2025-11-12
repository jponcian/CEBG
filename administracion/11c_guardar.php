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

//---------
$consulta_x = "UPDATE nomina_solicitudes SET id_orden_pago=0, estatus=5, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_orden_pago = $id_pago;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);//echo $consulta_x;
//-------------	
$consultax = "UPDATE nomina, nomina_solicitudes SET nomina.estatus = nomina_solicitudes.estatus WHERE nomina.id_solicitud = nomina_solicitudes.id;"; 
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
//$consulta_x = "DELETE FROM ordenes_pago WHERE id = $id_pago;"; 
$consulta_x = "UPDATE ordenes_pago SET estatus=99, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = $id_pago;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$consulta_x = "DELETE FROM ordenes_pago_descuentos WHERE id_orden_pago = $id_pago;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$mensaje = "Orden de Pago Anulada y Solicitud Reversada!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>