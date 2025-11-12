<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$rif = $_POST['txt_rif'];
$categoria = $_POST['txt_categoria'];
$anno = anno(voltea_fecha($_POST['txt_fecha']));
$total = 0 ;
//-------------
$consultx = "SELECT id, codigo, categoria, descripcion FROM a_presupuesto_$anno WHERE categoria = '$categoria' ORDER BY codigo;"; 
$tabla = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tabla->fetch_object())
	{
	$_POST[$registro->id] = str_replace('.','',$_POST[$registro->id]); 
	$_POST[$registro->id] = str_replace(',','.',$_POST[$registro->id]); 
	if ($_POST[$registro->id]>0)
		{
		$total = $total + $_POST[$registro->id] ;
		}
	}
//-------------
$info = array ("tipo"=>$tipo, "total"=>formato_moneda($total));
echo json_encode($info);
?>