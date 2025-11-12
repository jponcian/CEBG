<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$consultx = "UPDATE a_direcciones SET ci_coordinador = '".trim($_POST['txt_empleado'])."' WHERE id = ".$_POST['oid'].";";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//------------
//$consultx = "UPDATE a_areas, a_direcciones SET a_areas.ci_jefe = a_direcciones.cedula WHERE	a_areas.id_direccion = a_direcciones.id AND a_areas.jefatura = 1;";
//$tablx = $_SESSION['conexionsql']->query($consultx);	
//------------
$id = $_POST['oid'];
//-------------	
$mensaje = "Direccion Actualizada Exitosamente!";
//-------------

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx, "id"=>$id);

echo json_encode($info);
?>