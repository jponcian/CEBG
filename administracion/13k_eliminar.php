<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']); 
$_SESSION['conexionsql']->query("DELETE FROM orden WHERE id_contribuyente=$id AND tipo_orden='M' AND estatus=0;");	
?>