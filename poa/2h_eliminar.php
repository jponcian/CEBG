<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id = $_POST['id'];
//-------------	
$_SESSION['conexionsql']->query("DELETE FROM poa_metas WHERE id='$id';");
$_SESSION['conexionsql']->query("DELETE FROM poa_metas_frecuencia WHERE id_meta = '$id';");
$_SESSION['conexionsql']->query("DELETE FROM poa_metas_gestion WHERE id_meta = '$id';");
$mensaje = 'La Meta fue eliminada correctamente...';
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$id);
echo json_encode($info);
?>