<?php
session_start();
include_once "../conexion.php";
//----------------
$id = $_POST['id']; 
//-------------
$_SESSION['conexionsql']->query("UPDATE rrhh_permisos SET estatus=10, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id=$id AND estatus=0");	
//-------------
?>