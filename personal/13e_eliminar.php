<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id = $_POST['id']; 
//-------------	
	$_SESSION['conexionsql']->query("DELETE FROM eval_competencias WHERE id='$id';");
	$mensaje = 'El Registro fue eliminado correctamente...';
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>