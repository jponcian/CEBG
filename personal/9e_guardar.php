<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$cedula = ($_POST['txt_ci']);
$periodo = trim($_POST['txt_periodo']);
$desde = voltea_fecha(($_POST['txt_desde']));
$hasta = voltea_fecha(($_POST['txt_hasta']));
$incorporacion = voltea_fecha(($_POST['txt_incorporacion']));
//----------------
if ($desde<>'' and $hasta<>'' and $incorporacion<>'' and $cedula>0 and $periodo>0)
	{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM rrhh_permisos WHERE cedula='$cedula' AND tipo='VACACIONES' AND year(fecha)=".date('Y').";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object())
		{	$num = $registrox->num+1;	}
	else
		{	$num = 1;	}
	//-------------
	$consultx = "INSERT INTO rrhh_permisos(periodo, numero, cedula, fecha, tipo, desde, hasta, incorporacion, descripcion, habiles, calendario, usuario) VALUES ('$periodo', '$num', '$cedula', '".date('Y-m-d')."', 'VACACIONES', '$desde', '$hasta', '$incorporacion', '".$_POST['txt_observacion']."', '".$_POST['ohabiles2']."', '".$_POST['ocalendario2']."', '".$_SESSION['CEDULA_USUARIO']."')";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object();
		$id = $registrox->id;
	//-------------
	$consultx = "INSERT INTO rrhh_permisos_detalle(id_permiso, cedula, fecha, desde, hasta, incorporacion, descripcion, habiles, calendario, usuario) VALUES ('$id', '$cedula', '".date('Y-m-d')."', '$desde', '$hasta', '$incorporacion', '".$_POST['txt_observacion']."', '".$_POST['ohabiles2']."', '".$_POST['ocalendario2']."', '".$_SESSION['CEDULA_USUARIO']."')";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$consultx = "UPDATE rrhh_permisos, rac, a_direcciones, rac as empleados SET rrhh_permisos.area=rac.id_area, rrhh_permisos.direccion=rac.id_div, rrhh_permisos.cargo=rac.cargo, rrhh_permisos.ci_jefe=a_direcciones.cedula, rrhh_permisos.jefe_cargo=a_direcciones.cargo, rrhh_permisos.jefe=CONCAT(empleados.nombre,' ',empleados.nombre2,' ',empleados.apellido,' ',empleados.apellido2) WHERE rrhh_permisos.cedula = rac.cedula AND rac.id_div=a_direcciones.id AND a_direcciones.cedula=empleados.cedula AND rrhh_permisos.id='$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$consultx = "UPDATE rrhh_permisos, a_areas, rac SET rrhh_permisos.ci_jefe_area = a_areas.ci_jefe, rrhh_permisos.cargo_area = rac.cargo, rrhh_permisos.jefe_area = CONCAT( rac.nombre, ' ', rac.nombre2, ' ', rac.apellido, ' ', rac.apellido2 ) WHERE rrhh_permisos.area = a_areas.id AND a_areas.ci_jefe=rac.cedula;AND rrhh_permisos.id='$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$consultx = "UPDATE rac SET vacaciones = vacaciones-1 WHERE cedula = '$cedula';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$mensaje = "Vacaciones Registradas Exitosamente!";
	}
else
	{
	$tipo = "alerta";
	$mensaje = "Por favor indique toda la información...";
	}
//-------------

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));

echo json_encode($info);
?>