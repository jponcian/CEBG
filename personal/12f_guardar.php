<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
//-------------	
$txt_direccion2 = (($_POST['txt_direccion2']));
$txt_area2 = (($_POST['txt_area2']));
$txt_descripcion = (trim($_POST['txt_descripcion']));
$peso = (trim($_POST['txt_peso']));
$txt_proyecto = (trim($_POST['txt_proyecto']));
//-------------	
if ($txt_area2>0 and $txt_descripcion<>'')
	{	$consultx = "INSERT INTO eval_odis (id_proyecto, peso_o, id_direccion, id_area, descripcion, estatus, usuario) VALUES ('$txt_proyecto', '$peso', '$txt_direccion2', '$txt_area2', '$txt_descripcion', '0', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		$mensaje = "Registro Creado Exitosamente!";}
else
	{	$tipo = 'error';	$mensaje = "Por Favor Rellene todos los Campos!"; }
//-------------	

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>