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
if (trim($_POST['txt_cheque'])=='')
	{
	$mensaje = "No ha escrito el Numero de la Chequera!"; $tipo = 'alerta';
	}
//-------------
if ($tipo=='info')
	{
	//-------------	
	$consultx = "INSERT INTO a_cuentas_chequera (id_banco, chequera) VALUES ('".$_POST['txt_banco']."', '".$_POST['txt_cheque']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultx = "UPDATE a_cuentas , a_cuentas_chequera SET a_cuentas_chequera.banco = a_cuentas.banco,a_cuentas_chequera.cuenta = a_cuentas.cuenta  WHERE a_cuentas_chequera.id_banco = a_cuentas.id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Chequera Registrada Exitosamente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>