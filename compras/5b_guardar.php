<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
$id_solicitud = decriptar($_POST['id_solicitud']);
$estatus = ($_POST['estatus']);
//-------------
$consultax = "SELECT numero, fecha, id_contribuyente, tipo_orden FROM orden WHERE id=$id LIMIT 1;";  //echo $consultax;
$tablax = $_SESSION['conexionsql']->query($consultax);
$registro = $tablax->fetch_object();
$numero = ($registro->numero);
$fecha = ($registro->fecha);
$id_contribuyente = ($registro->id_contribuyente);
$tipo_orden = ($registro->tipo_orden);
//-------------

$consultax = "UPDATE orden SET estatus=99, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE numero='$numero' AND fecha='$fecha' AND id_contribuyente='$id_contribuyente' AND tipo_orden='$tipo_orden';"; 
$tablax = $_SESSION['conexionsql']->query($consultax);
//---------
if ($estatus>0)
	{
	$consulta_x = "UPDATE orden_solicitudes SET estatus=99, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = ".$id_solicitud.";"; 
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	}
//-------------	
$mensaje = "Orden Anulada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>