<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//----------------
$id = $_POST['oid'];
$detalle = $_POST['txt_tipo'];
$cantidad = $_POST['txt_cantidad'];
//----------------
$consultx = "INSERT INTO viaticos_solicitudes_detalle(id_solicitud, id_tipo, precio_u, cantidad, total, usuario) values ('$id', '$detalle', 0, '$cantidad', 0, '".$_SESSION['CEDULA_USUARIO']."');";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
$consultx = "UPDATE viaticos_solicitudes_detalle, a_item_viaticos SET viaticos_solicitudes_detalle.precio_u = a_item_viaticos.monto, viaticos_solicitudes_detalle.total = viaticos_solicitudes_detalle.cantidad * a_item_viaticos.monto WHERE viaticos_solicitudes_detalle.id_tipo = a_item_viaticos.id AND total=0;";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
$consultAx = "UPDATE viaticos_solicitudes, viaticos_solicitudes_detalle SET viaticos_solicitudes.total=viaticos_solicitudes_detalle.total WHERE viaticos_solicitudes.id=viaticos_solicitudes_detalle.id_solicitud AND viaticos_solicitudes.id = $id;";
$tablx = $_SESSION['conexionsql']->query($consultAx);	
//-------------
$mensaje = "Detalle Registrado Exitosamente!";

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>