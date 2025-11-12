<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']); 
$idb = decriptar($_POST['idb']); 
//------
$_SESSION['conexionsql']->query("DELETE FROM bn_prestamos WHERE id=$id");	
//------
$consultx = "SELECT prestamo FROM bn_bienes WHERE id_bien=$idb;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
if ($registro->prestamo==0)
	{
	$_SESSION['conexionsql']->query("UPDATE bn_bienes SET prestamo=1 WHERE id_bien=$idb");	
	}
else
	{
	$_SESSION['conexionsql']->query("UPDATE bn_bienes SET prestamo=0 WHERE id_bien=$idb");	
	}
?>