<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$info = array();
$id = ($_POST['id']);
$dir = ($_GET['dir']);
$tipo='info';
//-------
$consultx = "SELECT descripcion_bien, id_direccion, division, revisado FROM bn_bienes, bn_dependencias WHERE bn_bienes.id_dependencia=bn_dependencias.id AND numero_bien = $id;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
//--------
if ($tablx->num_rows>0)	{
	$registro = $tablx->fetch_object();
	$mensaje = $registro->descripcion_bien;
	if ($dir<>$registro->id_direccion)
		{$mensaje = 'El Bien pertenece a: '.$registro->division; $tipo='alerta';}
	if ($registro->revisado==1)
		{$mensaje = 'El Bien ya ha sido Procesado!'; $tipo='alerta';}
	}
else
	{$mensaje = 'No existe el Numero de Bien!'; $tipo='alerta';}
//-------
$consultx = "UPDATE bn_bienes SET revisado = 1 WHERE numero_bien = $id;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>