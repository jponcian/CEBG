<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = decriptar($_GET['id']);

$_POST["txt_precio".$id] = str_replace('.','',$_POST['txt_precio'.$id]); 
$_POST["txt_precio".$id] = str_replace(',','.',$_POST['txt_precio'.$id]);

//----------------
$consult = "UPDATE orden SET total='".($_POST['txt_cant'.$id])*($_POST['txt_precio'.$id])."', cantidad='".($_POST['txt_cant'.$id])."',  precio_uni='".($_POST['txt_precio'.$id])."', fecha_factura='".voltea_fecha($_POST['txt_fecha'.$id])."', factura='".strtoupper($_POST['txt_factura'.$id])."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id=$id;"; 
$tablx = $_SESSION['conexionsql']->query($consult);	
//-------------	
$mensaje = "Detalle Modificado Exitosamente!";


$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>