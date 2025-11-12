<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$id = ($_POST['id']); 
$hora = date('H:i:s');
$_SESSION['conexionsql']->query("UPDATE asistencia_diaria_visita SET salida='$hora', estatus=4, usuario_salida='".$_SESSION['CEDULA_USUARIO']."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE carnet=$id and (estatus<4)");	
?>