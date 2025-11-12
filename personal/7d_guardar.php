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
list($codigo,$nomina) = explode("-",$_POST['txt_nomina']);
//-------------	
if (trim($_POST['txt_nomina'])=='0')
	{
	$mensaje = "No ha rellenado todos los campos!"; $tipo = 'alerta';
	}
//-------------
if ($tipo=='info')
	{
	//-------------	
	$consultx = "INSERT INTO a_bonos (cargo, codigo, nomina, monto) VALUES ('Todos', '$codigo', '$nomina', '".$_POST['txt_monto']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Nomina Registrada Exitosamente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>