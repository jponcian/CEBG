<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = $_POST['id'];
//-------------
$consultx = "SELECT anno, fecha, descripcion, nomina FROM nomina_solicitudes WHERE id = $id  LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------	
$anno = $registro->anno;
$descripcion = $registro->descripcion;
$nomina = $registro->nomina;
$num_sol_pago = num_sol_pago($anno);
$numero = num_nomina($descripcion, $nomina, $anno);
//-------------
$consultx = "UPDATE nomina_solicitudes SET num_sol_pago='$num_sol_pago', numero='$numero', fecha_sol=CURDATE(),  estatus=5, usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id=$id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$consultx = "UPDATE nomina SET num_nomina='$numero', estatus = 5, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_solicitud=$id;";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
if ($tipo_pago=='001')
	{
	$consultx = "UPDATE rac SET des_sueldo = 0, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE nomina='$nomina';";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	}
if ($tipo_pago=='002')
	{
	$consultx = "UPDATE rac SET des_tickets = 0, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE nomina='$nomina';";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	}
//-------------	
$mensaje = "Solicitud de Pago Generada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>