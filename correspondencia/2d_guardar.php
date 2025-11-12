<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_GET['id']);
$observacion = ($_POST['txt_concepto']);
//-------------
$consultx = "UPDATE cr_memos_ext SET estatus = 7, observacion='$observacion' , usuario_aprobador='".$_SESSION['CEDULA_USUARIO']."' , fecha_aprobacion='".date('Y/m/d')."' WHERE id = $id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje = "Correspondencia Aprobada Exitosamente!";

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$_POST['id']);
echo json_encode($info);
?>