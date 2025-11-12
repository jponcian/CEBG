<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id = $_POST['id']; 
$anno = $_POST['anno']; 
//-------------	
$consulta_x = "SELECT * FROM a_presupuesto_$anno WHERE id = '$id';";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
if ($tabla_x->num_rows>0)
	{
	$registro = $tabla_x->fetch_object();
	$codigo = $registro->codigo;
	$descripcion = $registro->descripcion;
	$original = $registro->original;
	}
else
	{
	$tipo = 'alerta';
	$mensaje = 'Error al buscar la Información';
	}	
//-------------
$info = array ("tipo"=>$tipo, "codigo"=>$codigo, "descripcion"=>$descripcion, "original"=>formato_moneda($original), "msg"=>$mensaje);
echo json_encode($info);
?>