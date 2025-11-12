<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
//------------- INFORMACION BASICA
$id = decriptar($_POST['id']);
//$oficina = ($_POST['oficina']);
//$numero = sol_viatico();
//$memo = memo_viatico($oficina);
//-------------	
$consultx = "UPDATE viaticos_solicitudes SET estatus = 5, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id='$id';";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje = "Solicitud Generada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>