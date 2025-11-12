<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

$_POST["txt_precio1"] = str_replace('.','',$_POST['txt_precio1']); 
$_POST["txt_precio1"] = str_replace(',','.',$_POST['txt_precio1']); 
$_POST["txt_precio2"] = str_replace('.','',$_POST['txt_precio2']); 
$_POST["txt_precio2"] = str_replace(',','.',$_POST['txt_precio2']); 
$anno = $_POST['oanno'];
//----------------
if ($_GET['tipo']==1)
	{	$consultx = "INSERT INTO traslados(id_traspaso, anno, fecha, concepto, categoria1, partida1, monto1, categoria2, partida2, monto2, usuario) VALUES (0, ".($_POST['oanno']).", '".voltea_fecha($_POST['txt_fecha'])."', '".strtoupper($_POST['txt_concepto'])."', '".$_POST['txt_categoria1']."', '".$_POST['txt_partida1']."', ".$_POST['txt_precio1'].", '', '', 0, '".$_SESSION['CEDULA_USUARIO']."')";	}
//----------------
if ($_GET['tipo']==2)
	{	$consultx = "INSERT INTO traslados(id_traspaso, anno, fecha, concepto, categoria1, partida1, monto1, categoria2, partida2, monto2, usuario) VALUES (0, ".($_POST['oanno']).", '".voltea_fecha($_POST['txt_fecha'])."', '".strtoupper($_POST['txt_concepto'])."', '', '', 0, '".$_POST['txt_categoria2']."', '".$_POST['txt_partida2']."', ".$_POST['txt_precio2'].", '".$_SESSION['CEDULA_USUARIO']."')";	}
//-------------	
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------	
$consultx = "CALL actualizar_presupuesto_$anno();";	
//$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje = "Traspaso Agregado Exitosamente!";

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>