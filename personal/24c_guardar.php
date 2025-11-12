<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = $_POST['id'];
$cedula = $_POST['cedula'];
$id_direccion = $_POST['id_direccion'];
$id_area = $_POST['id_area'];

if ($_POST['tipo']=='si')
	{
	//------------
	$consultx = "INSERT INTO eval_asignacion (id_evaluacion, id_odi, id_direccion, id_area, cedula, fecha, estatus, usuario) VALUES ('0', '$id', '$id_direccion', '$id_area', '$cedula', curdate(), '3', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	$mensaje = "ODI Asignado Exitosamente!";
	//-------------
	$consultx = "UPDATE rac SET odis=3 WHERE cedula = '$cedula';";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	}
else
	{
	//-------------
	$consultx = "DELETE FROM eval_asignacion WHERE id_odi = '$id' AND cedula = '$cedula';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$consultx = "SELECT * FROM eval_asignacion WHERE id_evaluacion > 0 AND estatus < 10 AND cedula = $cedula;"; //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	if ($tablx->num_rows>0)
		{		}
	else
		{	
		$consultx = "UPDATE rac SET odis=2 WHERE cedula = '$cedula';";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		}
	//-------------
	$mensaje = "ODI Eliminado Exitosamente!";
	}
//-------------
$consultx = "UPDATE evaluaciones, eval_asignacion SET eval_asignacion.id_evaluacion = evaluaciones.id WHERE	evaluaciones.estatus = 2 AND (eval_asignacion.estatus = 2 OR eval_asignacion.estatus = 3) ;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
$consultx = "DELETE FROM eval_asignacion WHERE eval_asignacion.id_evaluacion = 0;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);	
//------------- DIRECTOR
$consultx = "UPDATE eval_asignacion, a_direcciones SET eval_asignacion.ci_director = a_direcciones.cedula WHERE	eval_asignacion.id_direccion = a_direcciones.id AND eval_asignacion.ci_director = 0 AND (eval_asignacion.estatus = 2 OR eval_asignacion.estatus = 3);"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);	
//------------- COORDINADOR GENERAL
$consultx = "UPDATE eval_asignacion, a_direcciones SET eval_asignacion.ci_coordinador = a_direcciones.ci_coordinador WHERE	eval_asignacion.id_direccion = a_direcciones.id AND eval_asignacion.ci_coordinador = 0 AND a_direcciones.ci_coordinador > 0 AND (eval_asignacion.estatus = 2 OR eval_asignacion.estatus = 3);"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
$consultx = "UPDATE eval_asignacion, a_areas SET eval_asignacion.ci_jefe_area = a_areas.ci_jefe WHERE eval_asignacion.id_area = a_areas.id AND eval_asignacion.ci_jefe_area < 500000 AND (eval_asignacion.estatus = 2 OR eval_asignacion.estatus = 3);"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------

$info = array ("tipo"=>$tipo, "msg"=>$mensaje);

echo json_encode($info);
?>