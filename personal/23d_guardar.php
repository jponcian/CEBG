<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$_POST['txt_descripcion'] = strtoupper(trim($_POST['txt_descripcion']));
//-------------	
if (trim($_POST['txt_descripcion'])=='')
	{
	$mensaje = "No ha rellenado todos los campos!"; $tipo = 'alerta';
	}
//-------------
if ($tipo=='info')
	{
	$consulta_x = "SELECT cargo FROM a_cargo WHERE cargo='".$_POST['txt_descripcion']."' LIMIT 1;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	if ($tabla_x->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = 'La Dependencia '.$_POST['txt_descripcion'].' ya existe...';
		}
	else	
		{
		//-------------	
		$consultx = "INSERT INTO a_cargo (cargo) VALUES ('".$_POST['txt_descripcion']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$mensaje = "Cargo Registrado Exitosamente!";
		}
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>