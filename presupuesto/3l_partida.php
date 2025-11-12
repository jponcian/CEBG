<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = $_POST['id'];
//-------------
$consultx = "SELECT descripcion FROM a_partidas WHERE codigo='$id' LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$registro_x->descripcion, "consulta"=>$consultx);
echo json_encode($info);
?>