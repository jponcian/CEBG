<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$categoria = $_POST['categoria'];
$partida = $_POST['partida'];
$anno = $_POST['anno'];
//-------------
$consultx = "SELECT original, modificado, disponible FROM a_presupuesto_$anno WHERE categoria='$categoria' and codigo='$partida' LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();
//-------------
$info = array ("tipo"=>$tipo, "original"=>formato_moneda($registro_x->original), "modificado"=>formato_moneda($registro_x->modificado), "disponible"=>formato_moneda($registro_x->disponible));
echo json_encode($info);
?>