<?php
session_start();
include_once "../conexion.php";
//----------------
$id = $_POST['id']; 
$_SESSION['conexionsql']->query("DELETE FROM a_cesta_tickets WHERE id=$id");	
?>