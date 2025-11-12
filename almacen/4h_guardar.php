<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//----------------
$consultx = "INSERT INTO rac_carga(rac_rep, cedula, nombres, fecha_nac, usuario) VALUES ('".$_POST['oid']."', '".$_POST['txt_cedula']."', '".strtoupper($_POST['txt_nombres'])."', '".voltea_fecha($_POST['txt_fecha'])."', '".$_SESSION['CEDULA_USUARIO']."');";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
$_SESSION['conexionsql']->query("UPDATE a_areas SET hijos = hijos+1 WHERE id = '".$_POST['oid']."'");	
//$consultx = "CALL actualizar_hijos();"; //echo $consultx ;
//$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
$mensaje = "Hijo Registrado Exitosamente!";

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>