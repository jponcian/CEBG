<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$id = $_POST['id']; 
$estatus = $_POST['estatus']; 
$_SESSION['conexionsql']->query("UPDATE a_cuentas SET estatus=$estatus WHERE id=$id");	
//-------------	
?>