<?php
session_start();
include_once "conexion.php";
include_once "funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
$actual = encriptar(trim($_POST['txt_actual']));
$nueva = encriptar(trim($_POST['txt_nueva']));
//-------------

$consulta_x = "SELECT usuario FROM usuarios WHERE usuario = '" . $_SESSION['CEDULA_USUARIO'] . "' AND password = '$actual';";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
if ($tabla_x->num_rows > 0) {
	$consultax = "UPDATE usuarios SET password = '" . $nueva . "' WHERE usuario = '" . $_SESSION['CEDULA_USUARIO'] . "';";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	$mensaje = "Informacion Actualizada Exitosamente!";
} else {
	$mensaje = "La contraseña actual no coincide con la almacenada en el Sistema...";
	$tipo = 'alerta';
}
//-------------
$info = array("tipo" => $tipo, "msg" => $mensaje);
echo json_encode($info);
