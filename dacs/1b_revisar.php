<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$info = array();
$cedula = decriptar($_GET['tabla']);
$atencion = ($_GET['atencion']);
$rac = ($_POST['oid']);
$nombres = strtoupper(trim($_POST['txt_nombres']));
$edad = trim($_POST['txt_edad']);
$correo = trim($_POST['txt_correo']);
$telefono = trim($_POST['txt_telefono']);
$sexo = trim($_POST['txt_sexo']);
$cargo = strtoupper(trim($_POST['txt_cargo']));
$organismo = strtoupper(trim($_POST['txt_organismo']));
$direccion = trim($_POST['txt_direccion']);
$observacion = trim($_POST['txt_observacion']);
$hora = date('H:i:s');
$tipo='success';
//---------
$consultx = "SELECT cedula FROM rac_visita WHERE cedula='$cedula';"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{
	//--------
	$consultx = "UPDATE rac_visita SET nombre='$nombres', cargo='$cargo', edad='$edad', telefono='$telefono', correo='$correo', sexo='$sexo', organismo='$organismo', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE cedula = '$cedula';"; 
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

if ($atencion==1)
	{
	//---------
	$consultx = "UPDATE asistencia_diaria_visita SET estatus=1 WHERE cedula='$cedula' AND estatus=0;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//---------
	}
$consultx = "INSERT INTO dacs_atencion (telefono, cargo, organismo, cedula, edad, tipo, fecha, comienzo, observacion, estatus, usuario_comienzo, usuario) VALUES ('$telefono', '$cargo', '$organismo', '$cedula', '$edad', '$atencion', '".date('Y/m/d')."', '$hora', '$observacion' , 0, '".$_SESSION['CEDULA_USUARIO']."', '".$_SESSION['CEDULA_USUARIO']."');"; 
$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
//--------
$mensaje = 'Procesado!';

//-------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>