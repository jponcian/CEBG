<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']);
$origen = ($_POST['origen']);
$destino = ($_POST['destino']);
$motivo = ($_POST['motivo']);
//-------
$_SESSION['conexionsql']->query("UPDATE bn_bienes SET por_reasignar=1 WHERE id_bien=$id");	
//-------
$consultx = "UPDATE bn_reasignaciones_detalle SET motivo = '$motivo' WHERE id_origen = '$origen' AND id_destino = '$destino' AND estatus=0;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------
$consultx = "INSERT INTO bn_reasignaciones_detalle (motivo, tipo, id_bien, fecha, id_origen, id_destino, usuario) VALUES ('$motivo', 'DIVISION', '$id', '".date('Y-m-d')."', '$origen', '$destino', '".$_SESSION['CEDULA_USUARIO']."');"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
?>