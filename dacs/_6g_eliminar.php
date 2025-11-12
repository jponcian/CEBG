<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']); 
$_SESSION['conexionsql']->query("DELETE FROM asistencia_diaria_visita WHERE id=$id");	
//$_SESSION['conexionsql']->query("DELETE FROM cr_memos_div_destino WHERE id_correspondencia=$id");	
//$_SESSION['conexionsql']->query("DELETE FROM cr_memos_ext_instruccion WHERE id_correspondencia=$id");	
?>