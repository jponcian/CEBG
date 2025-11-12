<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$consultx = "SELECT cedula FROM asistencia_diaria WHERE fecha='" . date('Y/m/d') . "' AND tipo='ENTRADA';";
$tablx = $_SESSION['conexionsql']->query($consultx);
$ingresado = abs($tablx->num_rows);
$consultx = "SELECT cedula FROM asistencia_diaria WHERE fecha='" . date('Y/m/d') . "' AND tipo='SALIDA';";
$tablx = $_SESSION['conexionsql']->query($consultx);
$salida = abs($tablx->num_rows);
if (($ingresado - $salida) > 0) {
	if ($ingresado - $salida == 1) {
		echo '<div class="alert alert-danger" role="alert" data-toggle="modal" data-target="#modal_largo" onclick="ingresados()">ACTUALMENTE HAY 1 FUNCIONARIO EN LA INSTITUCIÓN</div>';
	} else {
		echo '<div data-toggle="modal" data-target="#modal_largo" onclick="ingresados()">ACTUALMENTE HAY ' . ($ingresado - $salida) . ' FUNCIONARIOS EN LA INSTITUCIÓN</div>';
	}
} else {
	echo 'ACTUALMENTE NO HAY FUNCIONARIOS EN LA INSTITUCIÓN';
}
