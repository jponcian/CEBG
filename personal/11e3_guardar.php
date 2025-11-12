<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

$id_cont = '1000';
$concepto = strtoupper($_POST["txt_concepto"]);
$fecha = voltea_fecha($_POST["txt_desde"]);
	
$consultax = "UPDATE nomina SET fecha = '$fecha', descripcion = '$concepto', desde = '$fecha', hasta = '$fecha' WHERE tipo_pago='008' AND estatus=0;"; //echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$mensaje = "Registro Actualizado Exitosamente!";

$info = array ("tipo"=>$tipo, "msg"=>$mensaje);

echo json_encode($info);
?>