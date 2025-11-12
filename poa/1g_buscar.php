<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id = $_POST['id']; 
//-------------	
$consulta_x = "SELECT * FROM poa_proyecto WHERE id='$id';";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
if ($tabla_x->num_rows>0)
	{
	$registro = $tabla_x->fetch_object();
	$descripcion = $registro->descripcion;
	$objetivo = $registro->objetivo;
	$supuestos = $registro->supuestos;
	$tipop = $registro->tipo;
	}
else
	{
	$tipo = 'alerta';
	$mensaje = 'Error al buscar la Información';
	}	
//-------------
$info = array ("id"=>$id, "tipo"=>$tipo, "descripcion"=>$descripcion, "objetivo"=>$objetivo, "supuestos"=>$supuestos, "tipop"=>$tipop, "msg"=>$mensaje);
echo json_encode($info);
?>