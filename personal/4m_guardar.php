<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//----------------
if ($_GET['tipo']==1)
	{
	$consultx = "INSERT INTO rac_titulo (rac_rep, profesion, especialidad, colegio, numero, tomo, fecha, usuario) VALUES ('".$_POST['oid']."', '".$_POST['txt_prof']."', '".$_POST['txt_especialidad']."', '".$_POST['txt_colegio']."', '".$_POST['txt_numero']."', '".$_POST['txt_tomo']."', '".voltea_fecha($_POST['txt_fechar'])."', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$mensaje = "Titulo Registrado Exitosamente!";
	}
//----------------
if ($_GET['tipo']==2)
	{
	$consultx = "INSERT INTO rac_capacitacion (rac_rep, curso, instituto, duracion, desde, hasta, observacion, usuario) VALUES ('".$_POST['oid']."', '".$_POST['txt_curso']."', '".$_POST['txt_instituto']."', '".$_POST['txt_duracion']."', '".voltea_fecha($_POST['txt_desde'])."', '".voltea_fecha($_POST['txt_hasta'])."', '".$_POST['txt_observacion']."', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$mensaje = "Curso Registrado Exitosamente!";
	}
//----------------
if ($_GET['tipo']==3)
	{
	$consultx = "INSERT INTO rac_experiencia (rac_rep, institucion, cargo, desde, hasta, motivo, usuario) VALUES ('".$_POST['oid']."', '".$_POST['txt_institucion']."', '".$_POST['txt_cargo']."', '".voltea_fecha($_POST['txt_desde1'])."', '".voltea_fecha($_POST['txt_hasta1'])."', '".$_POST['txt_motivo']."', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$mensaje = "Experiencia Registrada Exitosamente!";
	}

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>