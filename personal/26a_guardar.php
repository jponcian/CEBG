<?php
session_start();
include_once "../conexion.php";
//--------
$info = array();
$tipo = 'info';

$ci = $_POST['txt_ci'] ;
$id = $_POST['txt_firma'] ;
$valor = explode("-",$id);
$id = $valor[0];

//-------------

$consulta_x = "UPDATE a_firmas SET cedula = '$ci', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = '$id';"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
$mensaje = "Informacion Actualizada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>