<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$codigo = $_POST['id']; 
//-------------	
$_SESSION['conexionsql']->query("DELETE FROM a_bonos WHERE codigo='$codigo';");
$mensaje = 'La Nomina y todas sus Excepciones fueron eliminadas correctamente...';
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>