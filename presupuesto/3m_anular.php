<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
//-------------

$consulta_x = "UPDATE credito_adicional SET estatus=99, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = $id;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$consulta_x = "DELETE FROM credito_adicional_detalle WHERE id_credito = $id;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$mensaje = "Credito Anulado Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>