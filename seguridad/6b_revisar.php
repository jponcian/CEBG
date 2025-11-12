<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$info = array();
$cedula = decriptar($_GET['tabla']);
$rac = ($_POST['oid']);
$nombres = strtoupper(trim($_POST['txt_nombres']));
$correo = trim($_POST['txt_correo']);
$telefono = trim($_POST['txt_telefono']);
$sexo = trim($_POST['txt_sexo']);
$organismo = strtoupper(trim($_POST['txt_organismo']));
$direccion = trim($_POST['txt_direccion']);
$carnet = trim($_POST['txt_carnet']);
$observacion = trim($_POST['txt_observacion']);
$hora = date('H:i:s');
$tipo='success';
//---------
$consultx = "SELECT cedula FROM rac_visita WHERE cedula='$cedula';"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{
	//--------
	$consultx = "UPDATE rac_visita SET nombre='$nombres', telefono='$telefono', correo='$correo', sexo='$sexo', organismo='$organismo', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE cedula = '$cedula';"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//--------
	}
else
	{
	//--------
	$consultx = "INSERT INTO rac_visita (cedula, nombre, telefono, correo, sexo, organismo, usuario) VALUES ('$cedula', '$nombres', '$telefono', '$correo', '$sexo', '$organismo', '".$_SESSION['CEDULA_USUARIO']."');"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//--------
	}
//---------
$consultx = "SELECT cedula FROM asistencia_diaria_visita WHERE cedula='$cedula' AND fecha='".date('Y/m/d')."' AND estatus=0;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{
	$mensaje = 'La Persona ya ingresó a las instalaciones!'; $tipo='error';
	}
else
	{ 
	//--------
	$consultx = "INSERT INTO asistencia_diaria_visita (carnet, organismo, cedula, id_direccion, tipo, fecha, ingreso, observacion, estatus, usuario_ingreso, usuario) VALUES ('$carnet', '$organismo', '$cedula', '$direccion', 'ENTRADA', '".date('Y/m/d')."', '$hora', '$observacion' , 0, '".$_SESSION['CEDULA_USUARIO']."', '".$_SESSION['CEDULA_USUARIO']."');"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//--------
	$consultx = "UPDATE asistencia_diaria_visita, a_direcciones SET asistencia_diaria_visita.direccion = a_direcciones.direccion WHERE asistencia_diaria_visita.id_direccion = a_direcciones.id AND asistencia_diaria_visita.direccion = '0';";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//--------
	$mensaje = 'Procesado!';
	}

//-------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>