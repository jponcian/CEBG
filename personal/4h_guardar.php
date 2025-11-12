<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//----------------
$sexo = isset($_POST['txt_sexo']) ? $_POST['txt_sexo'] : '';
$consultx = "INSERT INTO rac_carga(parentesco, rac_rep, cedula, nombres, fecha_nac, sexo, usuario) VALUES ('" . $_POST['txt_parentesco'] . "', '" . $_POST['oid'] . "', '" . $_POST['txt_cedula'] . "', '" . strtoupper($_POST['txt_nombres']) . "', '" . voltea_fecha($_POST['txt_fecha']) . "', '" . $sexo . "', '" . $_SESSION['CEDULA_USUARIO'] . "');";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------
if ($_POST['txt_parentesco'] == 'Hijo(a)') {
	$_SESSION['conexionsql']->query("UPDATE rac SET hijos = hijos+1 WHERE rac = '" . $_POST['oid'] . "'");
}
//-------------
$mensaje = "Carga Registrada Exitosamente!";

$info = array("tipo" => $tipo, "msg" => $mensaje, "consulta" => $consultx);

echo json_encode($info);
