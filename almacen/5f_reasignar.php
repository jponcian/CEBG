<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = ($_POST['id']);
$cantidad = ($_POST['cantidad']);
//-------
//$_SESSION['conexionsql']->query("UPDATE bn_materiales SET por_reasignar=1 WHERE id_bien=$id");	
//-------
if ($cantidad>0)
	{
	$consultx = "UPDATE bn_solicitudes_detalle SET aprobado=1, cant_aprobada = '$cantidad', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_detalle=$id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	} echo $consultx;
?>