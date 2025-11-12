<?php
session_start();
include_once "../conexion.php";
//include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$codigo = $_GET['codigo']; 
//----------------
$consultx = "SELECT * FROM a_cuadro_islr WHERE id_codigo = '$codigo';";
$tablx = $_SESSION['conexionsql']->query($consultx);	
$registro = $tablx->fetch_object();
$porcentaje = abs($registro->porcentaje);
$sustraendo = abs($registro->sustraendo);
//-------------	
$info = array ("tipo"=>$tipo, "porcentaje"=>$porcentaje, "sustraendo"=>$sustraendo);
echo json_encode($info);
?>