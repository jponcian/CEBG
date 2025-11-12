<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$numero = $_POST['txt_control'];
$anno = anno(voltea_fecha($_POST['txt_fecha']));
$_POST["txt_precio"] = str_replace('.','',$_POST['txt_precio']); 
$_POST["txt_precio"] = str_replace(',','.',$_POST['txt_precio']); 
//----------------
$consultx = "INSERT INTO credito_adicional_detalle(tipo_orden, fecha, anno, concepto, numero, categoria, partida, cantidad, descripcion, precio_uni, total, estatus, usuario) VALUES ('".$_POST['txt_tipo']."', '".(voltea_fecha($_POST['txt_fecha']))."', ".anno(voltea_fecha($_POST['txt_fecha'])).", '".strtoupper($_POST['txt_concepto'])."', '".trim($_POST['txt_control'])."', '".$_POST['txt_categoria']."', '".$_POST['txt_partida']."', '1', '".strtoupper($_POST['txt_detalle'])."', '".$_POST['txt_precio']."', '".$_POST['txt_precio']."', '0', '".$_SESSION['CEDULA_USUARIO']."');";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//----------------
$consultx = "UPDATE credito_adicional_detalle SET anno=".anno(voltea_fecha($_POST['txt_fecha'])).", concepto='".strtoupper($_POST['txt_concepto'])."', fecha='".voltea_fecha($_POST['txt_fecha'])."', tipo_orden='".($_POST['txt_tipo'])."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE year(fecha)='$anno' AND numero=$numero AND estatus=0;";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------	
$mensaje = "Detalle Agregado Exitosamente!";

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>