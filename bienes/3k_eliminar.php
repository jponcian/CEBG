<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']); 
//------
$consultx = "SELECT id_bien, id_origen FROM bn_reasignaciones_detalle WHERE id_reasignacion=$id;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$_SESSION['conexionsql']->query("UPDATE bn_bienes SET id_dependencia='".$registro->id_origen."' WHERE id_bien='".$registro->id_bien."'");
	}
//------
$_SESSION['conexionsql']->query("DELETE FROM bn_reasignaciones WHERE id=$id");	
$_SESSION['conexionsql']->query("DELETE FROM bn_reasignaciones_detalle WHERE id_reasignacion=$id");
?>