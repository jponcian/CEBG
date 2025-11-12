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
$consultx = "SELECT	bn_bienes.descripcion_bien, bn_bienes.id_bien, prestamo, asistencia_diaria.id FROM bn_bienes, bn_prestamos,	asistencia_diaria WHERE bn_bienes.id_bien = bn_prestamos.id_bien AND numero_bien = '$bien' AND bn_prestamos.id_asistencia = asistencia_diaria.id ORDER BY bn_prestamos.id DESC LIMIT 1";
$tablx = $_SESSION['conexionsql']->query($consultx);
//--------
if ($tablx->num_rows>0)	{
	$registro = $tablx->fetch_object();
	if ($registro->prestamo==1)	{
		if ($id>$registro->id)	{
			$id_bien = $registro->id_bien;
			$mensaje = $registro->descripcion_bien;
			//------- 
			$hora = date('H:i:s');
			//-------
			$consultx = "INSERT INTO bn_prestamos (fecha, tipo, id_asistencia, id_bien, descripcion_bien, usuario) VALUES ('".date('Y/m/d')."', '1', '$id', '$id_bien', '$mensaje', '".$_SESSION['CEDULA_USUARIO']."');"; 
			$tablx = $_SESSION['conexionsql']->query($consultx);
			//-------
			$consultx = "UPDATE bn_bienes SET prestamo = 0 WHERE numero_bien = $bien;"; 
			$tablx = $_SESSION['conexionsql']->query($consultx);
			}
		else
			{$mensaje = 'La Fecha/Hora de Ingreso no puede ser menor a la hora de Salida'; $tipo='error';} 
		}
	else
		{$mensaje = 'El Bien Nacional NO se encuentra en calidad de prestamo'; $tipo='error';} 
	}
else
	{$mensaje = 'No existe el Numero de Bien Nacional!'; $tipo='error';}
//-------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?> 