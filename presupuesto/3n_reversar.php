<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
//-------------

$consulta_x = "DELETE FROM credito_adicional WHERE id = $id;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$consulta_x = "UPDATE credito_adicional_detalle SET estatus=0, id_credito = 0, usuario_credito = NULL WHERE id_credito = $id;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$mensaje = "Credito Reversado Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>