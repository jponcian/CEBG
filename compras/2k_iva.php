<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id_cont = ($_POST['txt_id_rif']);
$fact = ($_POST['txt_factura']);
if (trim($fact) == '') {
	$fact = '';
} else {
	$fact = "factura='$fact' AND";
}
//-------------
$consultx = "SELECT SUM(total) as tot FROM orden WHERE $fact id_contribuyente=" . $id_cont . " AND estatus=0 AND left(trim(partida),7)<>'4031801';";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows > 0) {
	$registro = $tablx->fetch_object();
	//-------------	
	$monto = $registro->tot;
	//-------------	
} else {
	$monto = '0';
}
//-------------
$info = array("monto" => ($monto), "consultx" => ($_POST['txt_factura']));
echo json_encode($info);
