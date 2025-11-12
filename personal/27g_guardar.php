<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = $_GET['id']; 
//-------------	
if (trim($_POST['txt_cedula'])=='0')
	{
	$mensaje = "No ha seleccionado a nadie...!"; $tipo = 'alerta';
	}
else
	{	
	//-------------
	$consultx = "UPDATE a_areas SET ci_jefe = '".$_POST["txt_cedula"]."' WHERE id = $id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Registro Actualizado Exitosamente!";
	//------------- CONSULTA TEMPORAL PARA CUANDO NO HAY JEFE
	$consultx = "UPDATE eval_asignacion, a_areas SET eval_asignacion.ci_jefe_area = a_areas.ci_jefe WHERE eval_asignacion.id_area = a_areas.id AND eval_asignacion.ci_jefe_area < 500000;"; //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//------------- CONSULTA TEMPORAL PARA CUANDO NO HAY JEFE
	$consultx = "UPDATE a_areas, a_direcciones SET a_areas.ci_jefe = a_direcciones.cedula WHERE	a_areas.id_direccion = a_direcciones.id AND a_areas.jefatura = 1"; //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);	

	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>