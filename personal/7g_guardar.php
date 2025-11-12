<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
if (trim($_POST['txt_cargo'])=='0' or trim($_POST['txt_original'])=='')
	{
	$mensaje = "No ha rellenado todos los campos!"; $tipo = 'alerta';
	}
//-------------
$codigo = $_GET['id']; 
$nomina = $_GET['nomina']; 
//-------------
$_POST["txt_original"] = str_replace('.','',$_POST['txt_original']); 
$_POST["txt_original"] = str_replace(',','.',$_POST['txt_original']); 
//-------------	
$consultx = "INSERT INTO a_bonos (cargo, codigo, nomina, monto) VALUES ('".$_POST["txt_cargo"]."', '$codigo', '$nomina', '".$_POST['txt_original']."');";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje = "Excepcion Registrada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>