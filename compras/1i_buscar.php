<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
list($id_cont,$rif0) = explode("/",$_POST['txt_rif'][0]);

//----------------
$consultx = "SELECT * FROM presupuesto WHERE id_contribuyente = $id_cont AND estatus=0;";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$registro = $tablx->fetch_object();
	$fecha_factura = voltea_fecha($registro->fecha_presupuesto);
	$memo = ($registro->memo);
	$fecha_memo = voltea_fecha($registro->fecha_memo);
	$concepto = ($registro->concepto);
	$oficina = ($registro->oficina);
	}
else
	{
	$fecha = date('d/m/Y');
	$concepto = '';
	}
//-------------	

$info = array ("tipo"=>$tipo, "id_rif"=>encriptar($id_cont), "oficina"=>$oficina, "fecha_factura"=>$fecha_factura, "concepto"=>$concepto, "fecha_memo"=>$fecha_memo, "memo"=>$memo, "consulta"=>$consultx);

echo json_encode($info);
?>