<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
if (trim($_POST['txt_cheque'])=='')
	{
	$mensaje = "No ha escrito el Numero del Cheque!"; $tipo = 'alerta';
	}
//-------------
if ($tipo=='info')
	{
	//-------------	
	$consultx = "INSERT INTO a_cuentas_cheques (id_chequera, cheque) VALUES ('".$_GET['id']."', '".$_POST['txt_cheque']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultx = "UPDATE a_cuentas_cheques , a_cuentas_chequera SET a_cuentas_cheques.chequera = a_cuentas_chequera.chequera, a_cuentas_cheques.banco = a_cuentas_chequera.banco, a_cuentas_cheques.cuenta = a_cuentas_chequera.cuenta WHERE a_cuentas_chequera.id = a_cuentas_cheques.id_chequera;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Cheque Registrado Exitosamente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>