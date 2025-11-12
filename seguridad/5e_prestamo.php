<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$info = array();
$id = ($_GET['id']);
$bien = ($_GET['bien']);
$tipo='info';
//-------
$consultx = "SELECT descripcion_bien, id_bien, prestamo FROM bn_bienes WHERE numero_bien = $bien;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
//--------
if ($tablx->num_rows>0)	{
	$registro = $tablx->fetch_object();
	if ($registro->prestamo==0)	{
		$id_bien = $registro->id_bien;
		$mensaje = $registro->descripcion_bien;
		//------- 
		$hora = date('H:i:s');
		//-------
		$consultx = "INSERT INTO bn_prestamos (fecha, id_asistencia, id_bien, descripcion_bien, usuario) VALUES ('".date('Y/m/d')."', '$id', '$id_bien', '$mensaje', '".$_SESSION['CEDULA_USUARIO']."');"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------
		$consultx = "UPDATE bn_bienes SET id_prestamo = '$id', prestamo = 1 WHERE numero_bien = $bien;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		}
	else
		{$mensaje = 'El Bien Nacional ya se encuentra en calidad de prestamo'; $tipo='error';} 
	}
else
	{$mensaje = 'No existe el Numero de Bien Nacional!'; $tipo='error';}
//-------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?> 