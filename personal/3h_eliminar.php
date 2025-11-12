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
$_SESSION['conexionsql']->query("UPDATE rac SET evaluar_odis = '0' WHERE cedula = '$id'");
$mensaje = 'El Funcionario fue Excluido correctamente...';
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>