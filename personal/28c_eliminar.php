<?php
session_start();
include_once "../conexion.php";
//----------------
$id = $_POST['id']; 
$_SESSION['conexionsql']->query("DELETE FROM rrhh_dias_feriados WHERE id=$id");	
?>