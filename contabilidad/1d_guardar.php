<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
if ($_POST['txt_banco']=='SELECCIONE')
	{
	$mensaje = "No ha seleccionado el Banco!"; $tipo = 'alerta';
	}
//-------------	
if ($_POST['txt_cuenta']<=999999999999999999)
	{
	$mensaje = "No ha escrito el Numero de Cuenta!"; $tipo = 'alerta';
	}
//-------------	
if (trim($_POST['txt_descripcion'])=='')
	{
	$mensaje = "No ha escrito la descripcion de la Cuenta!"; $tipo = 'alerta';
	}
//-------------
if ($tipo=='info')
	{
	//-------------	
	$consultx = "INSERT INTO a_cuentas (banco, cuenta, descripcion) VALUES ('".$_POST['txt_banco']."', '".$_POST['txt_cuenta']."', '".strtoupper(trim($_POST['txt_descripcion']))."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Cuenta Registrada Exitosamente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>