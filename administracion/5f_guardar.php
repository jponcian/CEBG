<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
if ($_POST['txt_retencion']<>6){ $_POST['txt_sustraendo']=0; $_POST['txt_cant_islr']=0; $_POST['txt_tipo_islr']=0; }
$_POST["txt_monto"] = str_replace('.','',$_POST['txt_monto']); 
$_POST["txt_monto"] = str_replace(',','.',$_POST['txt_monto']); 
//----------------
$consultx = "INSERT INTO ordenes_pago_descuentos(cant_sustraendo, codigo, sustraendo, id_orden_pago, id_descuento, descuento, porcentaje, usuario) VALUES ('".$_POST['txt_cant_islr']."', '".$_POST['txt_tipo_islr']."', '".$_POST['txt_sustraendo']."', '".$_POST['oid']."', '".$_POST['txt_retencion']."', '".$_POST['txt_monto']."', '".$_POST['txt_porcentaje2']."', '".$_SESSION['CEDULA_USUARIO']."');";
$tablx = $_SESSION['conexionsql']->query($consultx);	//$mensaje = $_POST['txt_retencion'];
//----------------
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
//---------------- POR SI ESTA EL NUMERO DE RETENCION HUERFANO
$consultx = "UPDATE ordenes_pago_retencion SET id_orden_descuento=$id WHERE id_op='".$_POST['oid']."' AND id_tipo='".$_POST['txt_retencion']."';";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------	
$mensaje = "Detalle Agregado Exitosamente!";

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$_POST['oid']);

echo json_encode($info);
?>