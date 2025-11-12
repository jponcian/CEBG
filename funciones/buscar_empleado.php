<?php
session_start();
include_once "../conexion.php";
//--------
$info = array();
//-------------
$cedula = trim($_GET['cedula']);
//-------------
$consulta_x = "SELECT * FROM rac WHERE cedula=$cedula"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
if ($tabla_x->num_rows>0)
	{
	$info = array ("tipo"=>"alerta", "msg"=>"El Empleado ya esta registrado en la base de datos...");
	}
else
	{
	$info = array ("tipo"=>"info", "msg"=>"Correcto...", "consulta"=>$consulta_x);
	}
//-------------
echo json_encode($info);
?>