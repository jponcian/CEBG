<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
//-------------	
$fecha = voltea_fecha($_POST['txt_fecha']);
$anno = (($_POST['txt_anno']));
$descripcion = (($_POST['txt_descripcion']));
$semestre = (($_POST['txt_semestre']));
//-------------	
$consultx = "INSERT INTO evaluaciones (descripcion, semestre, fecha, anno, estatus, usuario) VALUES ('$descripcion', '$semestre', '$fecha', '$anno', '0', '".$_SESSION['CEDULA_USUARIO']."');";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------
//$consultx = "UPDATE rac SET odis = 0 WHERE evaluar_odis = '1'"; 
$consultx = "UPDATE rac SET odis = 0;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);	
//-------------	
$mensaje = "Registro Creado Exitosamente!";

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>