<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$_POST["txt_actual"] = str_replace('.','',$_POST['txt_actual']); 
$_POST["txt_actual"] = str_replace(',','.',$_POST['txt_actual']);

//-------------	ODIS
$consultau = "UPDATE a_asignaciones SET monto = '".$_POST["txt_actual"]."' WHERE id = 2;"; 
$tablau = $_SESSION['conexionsql']->query($consultau);	
//-------------	

$mensaje = "Registro Actualizado Exitosamente!";
//-------------	

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultau );

echo json_encode($info);
?>