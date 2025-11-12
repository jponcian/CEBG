<?php
session_start();
setlocale(LC_TIME, 'sp_ES', 'sp', 'es');
//--------------
include_once "conexion.php";
include_once "funciones/auxiliar_php.php";
//-----
$consultx = "SELECT cedula, tipo, descripcion FROM rrhh_permisos WHERE desde <= '".date('Y/m/d')."' AND hasta >= '".date('Y/m/d')."';"; 
$tablx1 = $_SESSION['conexionsql']->query($consultx);
if ($tablx1->num_rows>0)	
	{
	while ($registro1 = $tablx1->fetch_object())
		{
		$cedula = $registro1->cedula;
		$tipop = $registro1->tipo ;//. ' ('.$registro1->descripcion.')';	
		// --------------------
		//-------
		$consultx = "SELECT rac.cargo, id_area, a_direcciones.id, a_direcciones.direccion FROM rac, a_direcciones WHERE rac.id_div = a_direcciones.id AND rac.cedula = '$cedula';"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		$id_div = $registro->id;	
		$direccion = $registro->direccion;	
		$id_area = $registro->id_area;	
		$cargo = $registro->cargo;	
		//-------
		$consultx = "INSERT INTO asistencia_diaria (observacion, cargo, id_area, id_direccion, direccion, funcionarios, estatus, horario, cedula, tipo, fecha, hora) VALUES ('$tipop', '$cargo', '$id_area', '$id_div', '$direccion', '".personal_activo()."', '0', '08:00:00', '$cedula', 'ENTRADA', '".date('Y/m/d')."', '08:00:00');"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//--------
		}
	}