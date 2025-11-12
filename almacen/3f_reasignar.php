<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------

$id = ($_POST['articulo']);
$origen = ($_POST['origen']);
$cantidad = str_replace('.','',$_POST['cantidad']); 
$cantidad = str_replace(',','.',$cantidad);
//-------
//$_SESSION['conexionsql']->query("UPDATE bn_materiales SET por_reasignar=1 WHERE id_bien=$id");	
//-------
if ($id>0 and $cantidad>0)
	{
	$consultx = "INSERT INTO bn_solicitudes_detalle (id_bien, fecha, division, cantidad, usuario) VALUES ('$id', '".date('Y-m-d')."', '$origen', '$cantidad', '".$_SESSION['CEDULA_USUARIO']."');"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	}
//---------
?>