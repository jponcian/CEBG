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
$consultx = "SELECT id_odi FROM eval_asignacion WHERE id_odi='$id';";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$mensaje = 'ESTE ODI HA SIDO ASIGNADO NO SE PUEDE ELIMINAR...';
	$tipo = 'error';
	}
else
	{	
	$_SESSION['conexionsql']->query("DELETE FROM eval_odis WHERE id='$id';");
	$mensaje = 'El Registro fue eliminado correctamente...';
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>