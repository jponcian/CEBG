<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$consulta_x = "UPDATE bn_reasignaciones SET numero = 0".trim($_POST['txt_numero']).", fecha = '".voltea_fecha($_POST['txt_fecha'])."', usuario=".$_SESSION['CEDULA_USUARIO']." WHERE id = ".($_POST['oid']).";";  //echo $consulta_x;
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$mensaje = "Reasignacion Actualizada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consulta_x);
echo json_encode($info);
?>