<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$info = array();
$id = decriptar($_GET['id']);
$bien = decriptar($_GET['bien']);
$id_bien = decriptar($_GET['idbien']);
$cedula = ($_GET['ci']);
$tipo='info';

//------- 
$consultx = "SELECT	bn_bienes.descripcion_bien FROM bn_bienes WHERE numero_bien = '$bien' LIMIT 1";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$mensaje = $registro->descripcion_bien;

//------- 
$hora = date('H:i:s');
//-------
$consultx = "INSERT INTO asistencia_diaria (funcionarios, estatus, horario, cedula, tipo, fecha, hora, usuario) VALUES ('".personal_activo()."', '5', '', '$cedula', 'ENTREGA', '".date('Y/m/d')."', '$hora', '".$_SESSION['CEDULA_USUARIO']."');";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
//-------
$consultx = "INSERT INTO bn_prestamos (fecha, tipo, id_asistencia, id_bien, descripcion_bien, usuario) VALUES ('".date('Y/m/d')."', '1', '$id', '$id_bien', '$mensaje', '".$_SESSION['CEDULA_USUARIO']."');"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------
$consultx = "UPDATE bn_bienes SET id_prestamo = 0, prestamo = 0 WHERE numero_bien = $bien;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);

//--------
$consultx = "UPDATE asistencia_diaria, rac SET asistencia_diaria.id_direccion = rac.id_div, asistencia_diaria.cargo = rac.cargo WHERE asistencia_diaria.cedula = rac.cedula AND asistencia_diaria.id_direccion =0;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
//--------
$consultx = "UPDATE asistencia_diaria, a_direcciones SET asistencia_diaria.direccion = a_direcciones.direccion WHERE asistencia_diaria.id_direccion = a_direcciones.id AND asistencia_diaria.direccion = '0';";
$tablx = $_SESSION['conexionsql']->query($consultx);
	
//-------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?> 