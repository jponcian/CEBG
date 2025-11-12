<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
if ($tipo=='info')
	{
	//-------------	
	$consultx = "INSERT INTO rrhh_dias_feriados (fecha, usuario) VALUES ( '".voltea_fecha($_POST['txt_fecha'])."', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Sueldo Registrado Exitosamente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>