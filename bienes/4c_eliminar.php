<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id = decriptar($_POST['id']); 
//-------------	
$_SESSION['conexionsql']->query("DELETE FROM bn_bienes WHERE id_bien='$id';");
$mensaje = 'El registro fue eliminado correctamente...';
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>