<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id_poa = ($_GET['id']);
$anno_poa = ($_GET['anno']);
$fecha = date('Y/m/d');
$id = trim($_POST['oid']);
$tipo_p = trim($_POST['txt_tipo']);
$descripcion = trim($_POST['txt_proyecto']);
$objetivo = trim($_POST['txt_objetivo']);
$supuesto = trim($_POST['txt_supuesto']);
//-------------
if ($id>0)
	{
	$consultx = "UPDATE poa_proyecto SET descripcion = '$descripcion', objetivo = '$objetivo', supuestos = '$supuesto', tipo = '$tipo_p' WHERE id = $id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	$mensaje = "Proyecto Actualizado Exitosamente!";
	//-------------
	}
else
	{
	$consultx = "INSERT INTO poa_proyecto (id_poa, estatus, anno, numero, fecha, descripcion, objetivo, supuestos, tipo, usuario) VALUES ('$id_poa', 0, '$anno_poa', 1, '$fecha', '$descripcion', '$objetivo', '$supuesto', '$tipo_p', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$mensaje = "Proyecto Registrado Exitosamente!";
	//-------------
	}
//-------------	

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>