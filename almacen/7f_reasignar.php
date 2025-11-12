<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']);
//$origen = ($_POST['origen']);
$cantidad = str_replace('.','',$_POST['cantidad']); 
$cantidad = str_replace(',','.',$cantidad);
//-------
//$_SESSION['conexionsql']->query("UPDATE bn_materiales SET por_reasignar=1 WHERE id_bien=$id");	
//-------
if ($cantidad>0)
	{
	$consultx = "DELETE FROM bn_ingresos_detalle WHERE id_bien='$id' AND estatus=0;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$consultx = "INSERT INTO bn_ingresos_detalle (id_bien, fecha, division, cantidad, usuario) VALUES ('$id', '".date('Y-m-d')."', '12', '$cantidad', '".$_SESSION['CEDULA_USUARIO']."');"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	} echo $consultx;
?>