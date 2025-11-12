<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//----------------
$id = decriptar($_POST['id']);
$_SESSION['conexionsql']->query("DELETE FROM viaticos_solicitudes WHERE id=$id");	
$_SESSION['conexionsql']->query("DELETE FROM viaticos_solicitudes_detalle WHERE id_solicitud=$id");	
?>