<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
//-------------	
$txt_descripcion = (trim($_POST['txt_descripcion']));
//-------------	
if ($txt_descripcion<>'')
	{	$consultx = "INSERT INTO eval_competencias (descripcion, estatus, usuario) VALUES ('$txt_descripcion', '0', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		$mensaje = "Registro Creado Exitosamente!";}
else
	{	$tipo = 'error';	$mensaje = "Por Favor Rellene todos los Campos!"; }
//-------------	

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>