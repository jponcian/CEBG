<?php
session_start();
include_once "../conexion.php";
//----------------
list($accion,$id,$ordenado)=explode('-', $_POST['id']);
//---------------
$consultx = "SELECT id_banco, fecha FROM estado_cuenta WHERE id=$id LIMIT 1";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$id_banco = $registro->id_banco;
$fecha = $registro->fecha;
//---------------
if ($accion=='S')
	{
	$consultx = "SELECT ordenado FROM estado_cuenta WHERE ordenado<$ordenado AND id_banco='$id_banco' AND fecha='$fecha' ORDER BY ordenado DESC LIMIT 1";
	}
if ($accion=='B')
	{
	$consultx = "SELECT ordenado FROM estado_cuenta WHERE ordenado>$ordenado AND id_banco='$id_banco' AND fecha='$fecha' ORDER BY ordenado LIMIT 1";
	}
//------------
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$ordenado = $registro->ordenado; 
//------------
$consultx = "UPDATE estado_cuenta SET ordenado=($ordenado-0.01) WHERE id=$id;";
$tablx = $_SESSION['conexionsql']->query($consultx);
?>