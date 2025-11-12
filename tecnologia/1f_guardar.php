<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = $_POST['id'];
$cedula = $_POST['cedula'];

if ($_POST['tipo']=='si')
	{
	$consultx = "DELETE FROM usuarios_accesos WHERE usuario = '$cedula' AND acceso = '$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx); echo $consultx;	
	//------------
	$consultx = "INSERT INTO usuarios_accesos (usuario, acceso) VALUES ('$cedula', $id);";
	$tablx = $_SESSION['conexionsql']->query($consultx); echo $consultx;
	}
else
	{
	//-------------
	$consultx = "DELETE FROM usuarios_accesos WHERE usuario = '$cedula' AND acceso = '$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//------------
	$id = $_POST['oid'];
	//-------------	
	}
$mensaje = "Acceso Actualizado Exitosamente!";
//-------------

$info = array ("tipo"=>$tipo, "msg"=>$mensaje);

echo json_encode($info);
?>