<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$tipo = $_GET['tipo'];
$id_cont = $_POST['id'];
$fact = $_POST['fact'];

//----------------
$consultx = "SELECT factura, numero FROM orden_solicitudes WHERE id_contribuyente = $id_cont AND factura='$fact';"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$registro = $tablx->fetch_object();
	$tipo = 'alerta';
	//$fecha = voltea_fecha($registro->fecha);
	$factura = ($registro->factura);
	$numero = ($registro->numero);
	$msg = "El Contribuyente ya posee la Orden $numero con este numero de Factura";
	}
//-------------	

$info = array ("tipo"=>$tipo, "numero"=>$numero, "msg"=>$msg);

echo json_encode($info);
?>