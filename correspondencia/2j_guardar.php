<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
$direccion = decriptar($_POST['origen']);
$anno = decriptar($_POST['anno']);
//-------------
$numero = memo_dir($direccion, $anno);
//-------------
$consultx = "UPDATE cr_memos_ext SET estatus = 5, numero=$numero WHERE id = $id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje = "Memorando Aprobado Exitosamente!";

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$_POST['id']);
echo json_encode($info);
?>