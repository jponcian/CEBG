<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = decriptar($_POST['id']);
$origen = ($_POST['origen']);
$destino = ($_POST['destino']);
//-------
$_SESSION['conexionsql']->query("UPDATE bn_bienes SET por_reasignar=1 WHERE id_bien=$id");	
//-------
$consultx = "INSERT INTO bn_reasignaciones_detalle (id_bien, fecha, id_area_origen, id_area_destino, usuario) VALUES ('$id', '".date('Y-m-d')."', '$origen', '$destino', '".$_SESSION['CEDULA_USUARIO']."');"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
?>