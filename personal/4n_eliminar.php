<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = $_POST['id']; 
//----------------
if ($_GET['tipo']==1)
	{
	$_SESSION['conexionsql']->query("DELETE FROM rac_titulo WHERE id=$id");	
	}
if ($_GET['tipo']==2)
	{
	$_SESSION['conexionsql']->query("DELETE FROM rac_capacitacion WHERE id=$id");	
	}
if ($_GET['tipo']==3)
	{
	$_SESSION['conexionsql']->query("DELETE FROM rac_experiencia WHERE id=$id");	
	}
//-------------
?>