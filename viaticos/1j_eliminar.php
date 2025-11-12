<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//----------------
$id = decriptar($_POST['id']);
$id_solicitud = decriptar($_POST['id_solicitud']);
//-------------
$_SESSION['conexionsql']->query("DELETE FROM viaticos_solicitudes_detalle WHERE id=$id");	
//-------------
$consultAx = "UPDATE viaticos_solicitudes SET total=0 WHERE id=$id_solicitud;";
$tablx = $_SESSION['conexionsql']->query($consultAx);
//-------------
$consultAx = "UPDATE viaticos_solicitudes, viaticos_solicitudes_detalle SET viaticos_solicitudes.total=viaticos_solicitudes_detalle.total WHERE viaticos_solicitudes.id=viaticos_solicitudes_detalle.id_solicitud AND viaticos_solicitudes.id = $id_solicitud;";
$tablx = $_SESSION['conexionsql']->query($consultAx); echo $consultAx;	
//-------------
?>