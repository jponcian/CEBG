<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']); 
$_SESSION['conexionsql']->query("UPDATE bn_bienes SET por_reasignar=0 WHERE id_bien=$id");	
$_SESSION['conexionsql']->query("DELETE FROM bn_reasignaciones_detalle WHERE id_bien=$id AND estatus = 0");	
?>