<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$cedula = $_SESSION['CEDULA_USUARIO'];
//-------------	
$consulta = "SELECT eval_asignacion.id FROM eval_asignacion WHERE estatus=3 AND cedula='$cedula'"; 
$tabla = $_SESSION['conexionsql']->query($consulta);
while ($registro = $tabla->fetch_object())	
	{
	$id = $registro->id;
	$observacion = ($_POST['txt_observacion'.$id]);
	if (trim($_POST['txt_observacion'.$id])=='')
		{	
		$observacion = "CONFORME";
		}	
		//-------------
		$consulta = "UPDATE eval_asignacion SET estatus = 5, fecha_asignados = CURDATE(), asignados = '$observacion' WHERE id=$id;";
		$tabla_x = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
	}
//-------------
$consultx = "UPDATE rac SET odis=5 WHERE cedula = '$cedula';";
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------
$mensaje = "Informacion Actualizada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consulta);
echo json_encode($info);
?>