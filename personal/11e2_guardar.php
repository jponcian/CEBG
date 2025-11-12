<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

$id_cont = '1000';
$pago = $_POST["txt_pagos"];
$_SESSION['conexionsql']->query("DELETE FROM nomina WHERE tipo_pago='008' AND estatus=0;");
//-------------		
$consultx = "SELECT * FROM nomina WHERE nomina.id_solicitud = $pago;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
{
	
	//INSERT INTO javier_ponciano_1.nomina(estatus, tipo_pago, nomina, partida, cedula, cargo, categoria, ubicacion, anno, fecha, descripcion, desde, hasta, sueldo_mensual, sueldo, asignaciones, total, num_nomina, id_cont) VALUES (0, '008', 'NOMINA', 'PARTIDA', 'CEDULA', 'CARGO', 'CATEGORIA', 'UBICACION', ANNO, 'FECHA', 'DESCRIPCION', '20-12-17', '20-12-17', 1, 1, 1, 1, NOMINA, 1000)
		
	//-------------	
	$categoria = $registro->categoria;
	$partida = $registro->partida;
	$cedula = $registro->cedula;
	$cargo = $registro->cargo;
	$ubicacion = $registro->ubicacion;
	$nomina = $registro->nomina;
	$anno = $registro->anno;
	$hijos = $registro->hijos;
	//-------------
	$num_nomina = 0;
	$tipo_pago = '008';
	$concepto = $registro->descripcion;
	$fecha = date("Y/m/d");
	$desde = date("Y/m/d");
	$hasta = date("Y/m/d");
	$monto = $registro->total;
	//------- SUELDO
	$consultax = "INSERT INTO nomina (id_cont, sueldo_mensual, sueldo, num_nomina, tipo_pago, cargo, categoria, ubicacion, nomina, partida, cedula, anno, fecha, descripcion, desde, hasta, asignaciones, descuentos, total, estatus, usuario) VALUES ('$id_cont', '$monto', $monto, '$num_nomina', '$tipo_pago', '$cargo', '$categoria', '$ubicacion', '$nomina', '$partida', '$cedula', ".anno($fecha).", '$fecha', '$concepto', '$desde', '$hasta', $monto, 0, $monto, 0, '".$_SESSION['CEDULA_USUARIO']."')"; //echo $consultax;
	$tablax = $_SESSION['conexionsql']->query($consultax);	
		//-------------	id de la nomina
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object();
		$id_nomina = $registrox->id;
	//-------------	
	$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones, total_asignacion) VALUES ($id_nomina, '$categoria', '$nomina', '$partida', '$cedula', '8', $monto, $monto);";
	$tablax = $_SESSION['conexionsql']->query($consultax);	//echo $consultax;
	
}

//-------------	
//$consultax = "CALL actualizar_quincenas();"; //echo $consultx ;
//$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$mensaje = "Registro Agregado Exitosamente!";

$info = array ("tipo"=>$tipo, "msg"=>$mensaje);

echo json_encode($info);
?>