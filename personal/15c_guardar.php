<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$cedula = decriptar($_GET['cedula']);

//-------------	ODIS
$consultx = "SELECT * FROM eval_asignacion WHERE cedula = '$cedula' AND estatus=5;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$id = $registro->id;
	$id_evaluacion = $registro->id_evaluacion;
	$id_direccion = $registro->id_direccion;
	$id_area = $registro->id_area;
	$odi_peso = $_POST['txt_peso'.$registro->id];
	$odi_puntos = $_POST['txt_odi'.$registro->id];
	$odi_subtotal = $odi_peso * $odi_puntos;
	//-------------	
	$consultau = "UPDATE eval_asignacion SET peso = '$odi_peso', puntaje = '$odi_puntos', total = '$odi_subtotal', estatus = 7, fecha_evaluados = CURDATE(), usuario_evaluacion = '".$_SESSION['CEDULA_USUARIO']."'  WHERE id=$id;"; 
	$tablau = $_SESSION['conexionsql']->query($consultau);	 //echo $consultau;
	//-------------	
	} 	

//-------------	COMPETENCIAS
$consultx = "SELECT * FROM eval_competencias WHERE estatus = 0;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	if ($_POST['txt_comp_'.$registro->id]==1)
		{
		$id = $registro->id;
		$comp_peso = $_POST['txt_pesoa2'.$registro->id];
		$comp_puntos = $_POST['txt_comp'.$registro->id];
		$comp_subtotal = $comp_peso * $comp_puntos;
		//-------------	
		$consultai = "INSERT INTO eval_asignacion_comp (id_evaluacion, id_comp, id_direccion, id_area, cedula, fecha, estatus, fecha_evaluados, usuario_evaluacion, peso, puntaje, total, usuario) VALUES ('$id_evaluacion', '$id', '$id_direccion', '$id_area', '$cedula', CURDATE(), '7', CURDATE(), '".$_SESSION['CEDULA_USUARIO']."', '$comp_peso', '$comp_puntos', '$comp_subtotal', '".$_SESSION['CEDULA_USUARIO']."');"; 
		$tablai = $_SESSION['conexionsql']->query($consultai);	
		//-------------	
		}
	} 	

//-------------
$consultx = "UPDATE rac SET odis = 7 WHERE cedula = '$cedula';"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------

$mensaje = "Evaluación Registrada Exitosamente!";
//-------------	

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultau );

echo json_encode($info);
?>