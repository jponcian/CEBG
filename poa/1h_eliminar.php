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
$_SESSION['conexionsql']->query("DELETE FROM poa_proyecto WHERE id='$id';");
$mensaje = 'El Proyecto fue eliminado correctamente...';
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$id);
echo json_encode($info);
?>