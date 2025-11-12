<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']); 
$_SESSION['conexionsql']->query("DELETE FROM estado_cuenta_excel WHERE id=$id");	$_SESSION['conexionsql']->query("DELETE FROM estado_cuenta WHERE id_carga=$id");	
?>