<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$cedula = ($_POST['txt_ci']);
//$periodo = trim($_POST['txt_periodo']);
$desde = voltea_fecha(extrae_fecha($_POST['txt_desde']));
$hasta = voltea_fecha(extrae_fecha($_POST['txt_hasta']));
$incorporacion = voltea_fecha(extrae_fecha($_POST['txt_incorporacion']));
$hora_desde = mayuscula(extrae_hora_laboral($_POST['txt_desde']));
$hora_hasta = mayuscula(extrae_hora_laboral($_POST['txt_hasta']));
$hora_incorporacion = mayuscula(extrae_hora_laboral2($_POST['txt_incorporacion']));
$formato = ($_POST['txt_opcion']);
//----------------
if ($desde==$hasta and $hasta==$incorporacion)
	{
	$_POST['ohabiles2']=0;
	$_POST['ocalendario2']=0;
	//------------
//	$horas = $hora_hasta-$hora_desde;
	
	$horaInicio = new DateTime($hora_desde);
	$horaTermino = new DateTime($hora_hasta);

	$interval = $horaInicio->diff($horaTermino);
	$horas = $interval->format('%H horas %i minutos');

//	if (substr($hora_hasta,0,2) < substr($hora_desde,0,2))
//		{
//		
//	}
}
//----------------
if ($desde<>'' and $hasta<>'' and $incorporacion<>'' and $cedula>0)
	{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM rrhh_permisos WHERE cedula='$cedula' AND tipo='$formato' AND year(fecha)=".date('Y').";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object())
		{	$num = $registrox->num+1;	}
	else
		{	$num = 1;	}
	//-------------
	$consultx = "INSERT INTO rrhh_permisos(horas, hora1, hora2, hora3, periodo, numero, cedula, fecha, tipo, desde, hasta, incorporacion, descripcion, soporte, habiles, calendario, usuario) VALUES ('$horas', '$hora_desde', '$hora_hasta', '$hora_incorporacion', '$periodo', '$num', '$cedula', '".date('Y-m-d')."', '$formato', '$desde', '$hasta', '$incorporacion', '".$_POST['txt_observacion']."', '".$_POST['txt_anexos']."', '".$_POST['ohabiles2']."', '".$_POST['ocalendario2']."', '".$_SESSION['CEDULA_USUARIO']."')";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
//	echo $consultx ;
	//-------------
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object();
		$id = $registrox->id;
	//-------------
	$consultx = "INSERT INTO rrhh_permisos_detalle(horas, hora1, hora2, hora3, id_permiso, cedula, fecha, desde, hasta, incorporacion, descripcion, habiles, calendario, usuario) VALUES ('$horas', '$hora_desde', '$hora_hasta', '$hora_incorporacion', '$id', '$cedula', '".date('Y-m-d')."', '$desde', '$hasta', '$incorporacion', '".$_POST['txt_observacion']."', '".$_POST['ohabiles2']."', '".$_POST['ocalendario2']."', '".$_SESSION['CEDULA_USUARIO']."')";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
//	echo $consultx ;
	//-------------
	$consultx = "UPDATE rrhh_permisos, rac, a_direcciones, rac as empleados SET rrhh_permisos.area=rac.id_area, rrhh_permisos.direccion=rac.id_div, rrhh_permisos.cargo=rac.cargo, rrhh_permisos.ci_jefe=a_direcciones.cedula, rrhh_permisos.jefe_cargo=a_direcciones.cargo, rrhh_permisos.jefe=CONCAT(empleados.nombre,' ',empleados.nombre2,' ',empleados.apellido,' ',empleados.apellido2) WHERE rrhh_permisos.cedula = rac.cedula AND rac.id_div=a_direcciones.id AND a_direcciones.cedula=empleados.cedula AND rrhh_permisos.id='$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
//	$consultx = "UPDATE rac SET vacaciones = vacaciones-1 WHERE cedula = '$cedula';";
//	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$mensaje = "Permiso Registrado Exitosamente!";
	}
else
	{
	$tipo = "alerta";
	$mensaje = "Por favor indique toda la información...";
	}
//-------------

$info = array ("tipo"=>$tipo, "msg"=>$horas, "formato"=>$formato, "id"=>encriptar($id));

echo json_encode($info);
?>