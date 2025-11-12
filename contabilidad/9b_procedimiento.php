<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-------------	
$info = array();
$tipo = 'info';
//-------------	
if (trim($_POST['OFECHA'])<>'' and trim($_POST['OFECHA2'])<>'')
	{
	$cta = $_POST['op_tipo1'] ;
	$fecha1 = voltea_fecha(trim($_POST['OFECHA']));
	$fecha2 = voltea_fecha(trim($_POST['OFECHA2']));
	//------------
	$consultx = "CALL actualizar_estado_cuenta_op('$cta','$fecha1','$fecha2');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$msg = 'Estado de Cuenta Actualizado Exitosamente!';
	}
else
	{	$msg = 'Debe Indicar un Rango de Fechas!';}
//-------------	
$info = array ("msg"=>$msg, "tipo"=>$tipo);
echo json_encode($info);
?>