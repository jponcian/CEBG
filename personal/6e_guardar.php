<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

if ($_GET['tipo']=='1')
	{
	$cedula = decriptar($_GET['id']);
	$desde = voltea_fecha(extrae_fecha($_POST['OINICIO']));
	$hasta = voltea_fecha(extrae_fecha($_POST['OFIN']));
	$hora1 = (extrae_hora_laboral($_POST['OINICIO']));
	$hora2 = (extrae_hora_laboral($_POST['OFIN']));
	//----------------
	if (trim($_POST['opermiso'])<>'')
		{
		$consultx = "INSERT INTO rrhh_permisos(descripcion, cedula, fecha, tipo, desde, hora1, hasta, hora2, habiles, calendario, usuario) VALUES ('".trim($_POST['opermiso'])."', '$cedula', '".date('Y-m-d')."', 'PERMISO', '$desde', '$hora1', '$hasta', '$hora2', '".$_POST['ohabiles']."', '".$_POST['ocalendario']."', '".$_SESSION['CEDULA_USUARIO']."')";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		$mensaje = "Permiso Registrado Exitosamente!";
		}
	else
		{
		$tipo = "alerta";
		$mensaje = "Por favor indique toda la información...";
		}
	//-------------	
	}
if ($_GET['tipo']=='2')
	{
	$cedula = decriptar($_GET['id']);
	$desde = voltea_fecha(($_POST['txt_desde']));
	$hasta = voltea_fecha(($_POST['txt_hasta']));
	$incorporacion = voltea_fecha(($_POST['txt_incorporacion']));
	//----------------
	if ($desde<>'' and $hasta<>'' and $incorporacion<>'')
		{
		// PARA BUSCAR EL MAXIMO
		$consultax = "SELECT max(anno) as num FROM rrhh_permisos WHERE cedula='$cedula';";
		$tablax = $_SESSION['conexionsql']->query($consultax);
		if ($registrox = $tablax->fetch_object())
			{	$num = $registrox->num+1;	}
		else
			{	$num = 1;	}
		//-------------
		$consultx = "INSERT INTO rrhh_permisos(anno, cedula, fecha, tipo, desde, hasta, incorporacion, habiles, calendario, usuario) VALUES ('$num', '$cedula', '".date('Y-m-d')."', 'VACACIONES', '$desde', '$hasta', '$incorporacion', '".$_POST['ohabiles2']."', '".$_POST['ocalendario2']."', '".$_SESSION['CEDULA_USUARIO']."')";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		$mensaje = "Vacaciones Registradas Exitosamente!";
		}
	else
		{
		$tipo = "alerta";
		$mensaje = "Por favor indique toda la información...";
		}
	//-------------	
	}
//-------------

$info = array ("tipo"=>$tipo, "msg"=>$mensaje);

echo json_encode($info);
?>