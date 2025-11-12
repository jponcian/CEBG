<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_GET['id']);
$boton = ($_GET['boton']);
$observacion = trim($_POST['txt_observacion']);
//-------------
if ($boton==1) { $estatus = 0; } else { $estatus = 1; }
//-------------
$consultx = "UPDATE asistencia_diaria SET estatus = $estatus, observacion='$observacion' WHERE id = $id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje = "Registro Actualizado Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$_POST['id']);
echo json_encode($info);
?>