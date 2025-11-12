<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

if ($_POST['oid']==0)
	{
	$consultx = "SELECT usuario FROM usuarios WHERE usuario='".$_POST['txt_cedula']."0' LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = "Empleado ya Registrado!";
		}
	else
		{
		//-------------
		$consultx = "INSERT INTO usuarios (user, password, email, acceso, usuario) VALUES ('".trim($_POST['user'])."', '".encriptar(trim($_POST['password']))."', '".trim($_POST['correo'])."', '".trim($_POST['tipo_acceso'])."', '".trim($_POST['txt_cedula'])."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		//------------
		$id = 0;
		//-------------	
		$mensaje = "Usuario Registrado Exitosamente!";
		}
	}
else
	{
	//-------------
	$consultx = "UPDATE usuarios SET user = '".trim($_POST['user'])."', password = '".encriptar(trim($_POST['password']))."', email = '".trim($_POST['correo'])."', acceso = ".trim($_POST['tipo_acceso'])." WHERE id = ".$_POST['oid'].";";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//------------
	$id = $_POST['oid'];
	//-------------	
	$mensaje = "Usuario Registrado Exitosamente!";
	}
//-------------
$consulta = "UPDATE usuarios, rac, bn_dependencias SET usuarios.nombre_usuario = CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) , usuarios.id_direccion = rac.id_div, usuarios.id_area = rac.id_area, usuarios.id_division = bn_dependencias.id WHERE usuarios.usuario = rac.cedula AND bn_dependencias.id_area_dependencia = rac.id_area;";
$tabla = $_SESSION['conexionsql']->query($consulta);

$consulta = "SELECT usuario, acceso FROM usuarios WHERE acceso>0 AND acceso<999  AND acceso<>99;";
$tabla = $_SESSION['conexionsql']->query($consulta);
while ($registro = $tabla->fetch_object())
	{
	//---------------
	$consultax = "SELECT ".$registro->usuario.", id from accesos_individual WHERE tipo LIKE '%" .$registro->acceso. "%';";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	while ($registrox = $tablax->fetch_object())
		{
		$consultai = "INSERT INTO usuarios_accesos(usuario, acceso) VALUES ('".$registro->usuario."', ".$registrox->id.")"; 
		$tablai = $_SESSION['conexionsql']->query($consultai);
		}
	}
//-------------
$consulta = "UPDATE rac SET rac.bienes = 0;";
$tabla = $_SESSION['conexionsql']->query($consulta);
//-------------
$consulta = "UPDATE rac, usuarios_accesos SET rac.bienes = 1 WHERE rac.cedula = usuarios_accesos.usuario AND usuarios_accesos.acceso = 95;";
$tabla = $_SESSION['conexionsql']->query($consulta);
//-------------
$consulta = "UPDATE usuarios SET usuarios.bienes = 0;";
$tabla = $_SESSION['conexionsql']->query($consulta);
//-------------
$consulta = "UPDATE usuarios, usuarios_accesos SET usuarios.bienes = 1 WHERE usuarios.usuario = usuarios_accesos.usuario AND usuarios_accesos.acceso = 95;";
$tabla = $_SESSION['conexionsql']->query($consulta);
//-------------

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx, "id"=>$id);

echo json_encode($info);
?>