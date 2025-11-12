<?php
session_start();
include_once "../conexion.php";
//----------------
$id = $_POST['id']; 
$_SESSION['conexionsql']->query("DELETE FROM a_sueldo_minimo WHERE id=$id");	
?>