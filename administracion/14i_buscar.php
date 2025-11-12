<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
list($id_cont,$rif0) = explode("/",$_POST['txt_id_rif']);

//----------------
$consultx = "SELECT control, fecha, concepto, factura, fecha_factura FROM orden WHERE id_contribuyente = $id_cont AND tipo_orden='F' AND estatus=0;";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$registro = $tablx->fetch_object();
	$fecha = voltea_fecha($registro->fecha);
	$fecha_factura = voltea_fecha($registro->fecha_factura);
	$factura = ($registro->factura);
	$concepto = ($registro->concepto);
	$control = ($registro->control);
	}
else
	{
	$fecha = date('d/m/Y');
	$concepto = '';
	}
//-------------	

$info = array ("tipo"=>$tipo, "id_rif"=>encriptar($id_cont), "control"=>$control, "fecha_factura"=>$fecha_factura, "factura"=>$factura, "fecha"=>$fecha, "concepto"=>$concepto, "consulta"=>$consultx);

echo json_encode($info);
?>