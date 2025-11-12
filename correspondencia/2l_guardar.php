<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
$detalle = decriptar($_POST['detalle']);
//-------------
$consultx = "UPDATE cr_memos_ext SET estatus = 10 WHERE id = $id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
////-------------	
$consultx = "UPDATE cr_memos_ext_destino SET fecha_recepcion='".date('Y/m/d')."', estatus_recepcion = 10, usuario_recepcion='".$_SESSION['CEDULA_USUARIO']."' WHERE id = $detalle ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
////-------------	
$mensaje1 = "Correspondencia Recibida Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje1, "id"=>$_POST['id']);
echo json_encode($info);
?>