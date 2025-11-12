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

$consultax = "UPDATE orden SET numero=0, estatus=0, id_solicitud=0, usuario_solicitud='', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_solicitud='$id_solicitud';"; 
$tablax = $_SESSION['conexionsql']->query($consultax);
//---------
$consultax = "UPDATE orden_solicitudes SET descripcion='R.E.V.E.R.S.A.D.A', estatus=99, id_contribuyente = 1000, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id='$id_solicitud';"; 
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$mensaje = "Orden Reversada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>