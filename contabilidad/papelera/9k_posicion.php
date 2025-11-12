<?php
session_start();
include_once "../conexion.php";
//----------------
list($accion,$id,$ordenado)=explode('-', $_POST['id']);

if ($accion=='S')
	{
	$consultx = "SELECT ordenado FROM estado_cuenta WHERE ordenado>$ordenado LIMIT 1";
	}
if ($accion=='B')
	{
	$consultx = "SELECT ordenado FROM estado_cuenta WHERE ordenado<$ordenado LIMIT 1";
	}
//------------
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$ordenado = $registro->ordenado;
//------------
$consultx = "UPDATE estado_cuenta SET ordenado=$ordenado WHERE id=$id;";
$tablx = $_SESSION['conexionsql']->query($consultx);
?>