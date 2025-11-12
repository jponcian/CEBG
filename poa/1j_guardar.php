<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id_poa = ($_GET['id_poa']);
$anno_poa = ($_GET['anno']);
$id_proyecto = ($_GET['id_proyecto']);
$direccion = ($_GET['direccion']);
$fecha = date('Y/m/d');
//-------------	
$consulta_x = "SELECT id_direccion FROM poa_proyecto_responsable WHERE id_proyecto = '$id_proyecto' AND id_direccion = '$direccion';"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
if ($tabla_x->num_rows>0)	{
	$consultax = "DELETE FROM poa_proyecto_responsable WHERE id_proyecto = '$id_proyecto' AND id_direccion = '$direccion';"; 
	$tablax = $_SESSION['conexionsql']->query($consultax);
	$mensaje = "Responsable Eliminado Exitosamente!";
	$tipo = 'info';
}
else{
	$consultx = "INSERT INTO poa_proyecto_responsable (id_proyecto, estatus, anno, numero, fecha, id_direccion, usuario) VALUES ($id_proyecto, 0, $anno_poa, 1, '$fecha', '$direccion', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Responsable Registrado Exitosamente!";
	$tipo = 'info';
}


//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>