<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = ($_POST['id']);
if ($id>0)
	{$filtro = ' WHERE id_dependencia='.$id;}
else
	{$filtro = '';}
//-------
$consultx = "UPDATE bn_bienes SET revisado = 0 $filtro;"; // echo $id;
$tablx = $_SESSION['conexionsql']->query($consultx);
?>