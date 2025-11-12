<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id = decriptar($_POST['id']); 
$id_meta = decriptar($_POST['id_meta']); 
$mes = decriptar($_POST['mes']); 
$cantidad = decriptar($_POST['cantidad']); 
//-------------	
$_SESSION['conexionsql']->query("UPDATE poa_metas_frecuencia SET cantidad_gestion = cantidad_gestion - $cantidad WHERE id_meta = $id_meta AND mes = '$mes';");
//-------------	
$_SESSION['conexionsql']->query("DELETE FROM poa_metas_gestion WHERE id='$id';");
$mensaje = 'La Gestion fue eliminada correctamente...';
//-------------
$consulta = "UPDATE poa_metas_frecuencia SET estatus = 0 WHERE cantidad<=0;"; 
$tablax = $_SESSION['conexionsql']->query($consulta);
$consulta = "UPDATE poa_metas_frecuencia SET estatus = 5 WHERE cantidad>cantidad_gestion AND cantidad_gestion>0;";
$tabla_x = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
$consulta = "UPDATE poa_metas_frecuencia SET estatus = 10 WHERE cantidad<=cantidad_gestion;";
$tabla_x = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>