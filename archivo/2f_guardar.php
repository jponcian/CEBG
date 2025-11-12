<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
//$hasta = voltea_fecha(extrae_fecha($_POST['txt_hasta']));
//$hora1 = (extrae_hora_laboral($_POST['txt_hasta']));
//$horaa = (extrae_hora($_POST['txt_hasta']));
//-------------
//if ($_GET['id']==0)
//	{
	//----------------
//	$consultx = "INSERT INTO arc_prestamos (id_expendiente, fecha, descripcion, funcionario, hasta, hora1, horaa, usuario) VALUES ('".$_GET['id']."', '".date('Y/m/d')."', '".trim($_POST['txt_detalle'])."', '".($_POST['txt_cedula'])."', '$hasta', '$hora1', '$horaa', '".$_SESSION['CEDULA_USUARIO']."')";
//	$tablx = $_SESSION['conexionsql']->query($consultx);
	//------------
//	$id = 0;
	//-------------	
	$mensaje = "Información Registrada Exitosamente!";
//	}
//if ($_GET['id']>0)
//	{
//	//----------------
	$consultx = "UPDATE arc_prestamos SET observaciones = '".trim($_POST['txt_detalle'])."', fecha_dev = '".date('Y/m/d')."', estatus = 10, usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id = ".$_GET['id'].";"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);	
//	//------------
//	$id = 0;
//	//-------------	
//	$mensaje = "Información Actualizada Exitosamente!";
//	}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$id);

echo json_encode($info);
?>