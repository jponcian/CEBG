<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
//$id = decriptar($_POST['id']);
//-------------
$consultx = "UPDATE cr_memos_dir_ext SET estatus = 10 WHERE id = $id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje1 = "Memorando Enviado Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje1, "id"=>$_POST['id']);
echo json_encode($info);
?>