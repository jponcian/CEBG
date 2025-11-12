<?php
session_start();
include_once "../conexion.php";
//----------------
$id = $_POST['id']; 
//-------------
$_SESSION['conexionsql']->query("DELETE FROM cr_memos_div_destino WHERE id=$id");	
//-------------
//$consultx = "CALL actualizar_hijos();"; //echo $consultx ;
//$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
?>