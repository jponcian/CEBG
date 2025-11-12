<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']); 
$_SESSION['conexionsql']->query("DELETE FROM nomina WHERE id=$id");	
$_SESSION['conexionsql']->query("DELETE FROM nomina_asignaciones WHERE id_nomina=$id");	
?>