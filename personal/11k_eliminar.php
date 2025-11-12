<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']); 
$_SESSION['conexionsql']->query("DELETE FROM nomina WHERE tipo_pago='008' AND estatus=0;");	
?>