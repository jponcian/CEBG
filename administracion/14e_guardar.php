<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

$_POST["txt_precio"] = str_replace('.','',$_POST['txt_precio']); 
$_POST["txt_precio"] = str_replace(',','.',$_POST['txt_precio']); 
list($id_rif0,$rif0) = explode("/",$_POST['txt_id_rif']);
//----------------
$consultx = "INSERT INTO orden(control, factura, fecha_factura, tipo_orden, id_contribuyente, rif, fecha, anno, concepto, numero, categoria, partida, cantidad, descripcion, precio_uni, total, estatus, usuario) VALUES (0, 0, '".date('Y/m/d')."', 'F', '$id_rif0', '".strtoupper($rif0)."', '".date('Y/m/d')."', YEAR('".date('Y/m/d')."'), '".strtoupper($_POST['txt_concepto'])."', '0', '".$_POST['txt_categoria']."', '".$_POST['txt_partida']."', '".$_POST['txt_cantidad']."', '".strtoupper($_POST['txt_detalle'])."', '".$_POST['txt_precio']."', '".$_POST['txt_cantidad']*$_POST['txt_precio']."', '0', '".$_SESSION['CEDULA_USUARIO']."');";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//----------------
$consultx = "UPDATE orden SET concepto='".strtoupper($_POST['txt_concepto'])."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id_contribuyente='$id_rif0' AND estatus=0 AND tipo_orden='F';";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------	
$mensaje = "Detalle Agregado Exitosamente!";

$info = array ("tipo"=>$tipo, "id"=>encriptar($id_rif0), "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>