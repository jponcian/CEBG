<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']); 
//-------------
$consultx = "SELECT * FROM bn_ingresos_detalle WHERE id_ingreso = '$id';";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registrox = $tablx->fetch_object())
	{
	//-------------	
	$consultx = "UPDATE bn_materiales SET inventario = inventario - ".$registrox->cantidad.", usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_bien = ".$registrox->id_bien.";";
	$tablax = $_SESSION['conexionsql']->query($consultx); 
	//-------------	
	}
$_SESSION['conexionsql']->query("DELETE FROM bn_ingresos WHERE id=$id");	
$_SESSION['conexionsql']->query("DELETE FROM bn_ingresos_detalle WHERE id_ingreso=$id");	
?>