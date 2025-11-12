<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = $_POST['id']; 
$parentesco = decriptar($_POST['parentesco']); 
//-------------
$consultx = "SELECT rac_rep FROM rac_carga WHERE id=$id"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$rac = $registro->rac_rep;
//-------------
if ($parentesco=='Hijo(a)')
	{	$_SESSION['conexionsql']->query("UPDATE rac SET hijos = hijos-1 WHERE rac = '$rac'");	}
$_SESSION['conexionsql']->query("DELETE FROM rac_carga WHERE id=$id");	
//-------------
//$consultx = "CALL actualizar_hijos();"; //echo $consultx ;
//$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
?>