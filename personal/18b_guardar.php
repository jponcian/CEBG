<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id_solicitud = decriptar($_POST['id_solicitud']);
//-------------

//---------
$consulta_x = "UPDATE nomina_solicitudes SET estatus = 0 WHERE id = $id_solicitud;"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);//echo $consulta_x;
//-------------	
$consultax = "UPDATE nomina SET nomina.estatus = 0 WHERE nomina.id_solicitud = $id_solicitud;"; 
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$mensaje = "Solicitud de Pago Reversada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>