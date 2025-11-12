<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['oid']);
//----------------
$consultx = "INSERT INTO cr_memos_ext_destino(id_correspondencia, direccion_destino,usuario) VALUES ('".$id."', '".$_POST['txt_destino']."', '".$_SESSION['CEDULA_USUARIO']."');";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
$_SESSION['conexionsql']->query("UPDATE cr_memos_ext_destino, a_direcciones SET cr_memos_ext_destino.ci_jefe_destino = a_direcciones.cedula WHERE cr_memos_ext_destino.direccion_destino=a_direcciones.id AND cr_memos_ext_destino.id_correspondencia = $id");	
//-------------
$mensaje = "Destinatario Registrado Exitosamente!";

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>