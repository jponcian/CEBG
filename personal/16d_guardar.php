<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$_POST["txt_monto"] = str_replace('.','',$_POST['txt_monto']); 
$_POST["txt_monto"] = str_replace(',','.',$_POST['txt_monto']);
//-------------	
if ($_POST['txt_monto']<=0)
	{
	$mensaje = "El Sueldo debe ser mayor a Cero (0)!"; $tipo = 'alerta';
	}
//-------------
if ($tipo=='info')
	{
	//-------------	
	$consultx = "INSERT INTO a_sueldo_minimo (monto, fecha, usuario) VALUES ('".$_POST["txt_monto"]."', '".voltea_fecha($_POST['txt_fecha'])."', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Sueldo Registrado Exitosamente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>