<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-------------	
$info = array();
$tipo = 'info';
//-------------	
$op = decriptar($_GET['op']) ;
$movimiento = decriptar($_GET['movimiento']) ;
$consultx = "UPDATE estado_cuenta SET id_orden = $op, estatus_op=1 WHERE id = $movimiento";
$tablx = $_SESSION['conexionsql']->query($consultx);
$consultx = "UPDATE ordenes_pago SET contabilidad=1, usuario='".$_SESSION[CEDULA_USUARIO]."' WHERE id = $op";
$tablx = $_SESSION['conexionsql']->query($consultx);
$consultx = "UPDATE estado_cuenta, ordenes_pago, contribuyente SET estado_cuenta.tipo_orden = ordenes_pago.tipo_solicitud, estado_cuenta.numero_orden = ordenes_pago.numero, estado_cuenta.rif_orden = contribuyente.rif, estado_cuenta.nombre_orden = contribuyente.nombre WHERE ordenes_pago.id_contribuyente = contribuyente.id AND estado_cuenta.id_orden = ordenes_pago.id AND estado_cuenta.id_orden =$op";
$tablx = $_SESSION['conexionsql']->query($consultx);
$msg = 'Movimiento Actualizado Exitosamente!';
//-------------	
$info = array ("msg"=>$msg, "tipo"=>$tipo);
echo json_encode($info);
?>