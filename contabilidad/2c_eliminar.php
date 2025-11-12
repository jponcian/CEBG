<?php
session_start();
include_once "../conexion.php";
//----------------
$id = $_POST['id']; 
$_SESSION['conexionsql']->query("DELETE FROM a_cuentas_chequera WHERE id=$id");	
$_SESSION['conexionsql']->query("DELETE FROM a_cuentas_cheques WHERE id_chequera=$id");	
?>