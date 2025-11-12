<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

$id_cont = '1000';
$rac = $_GET["rac"];

//-------------		
if ($rac == '123456789') {
	$consultx = $_SESSION['consulta'];
} else {
	$consultx = "SELECT * FROM rac WHERE rac=$rac;";
}
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object()) {
	//-------------	
	$categoria_empleado = $registro->categoria;
	$partida_empleado = $registro->partida;
	$cedula = $registro->cedula;
	$cargo = $registro->cargo;
	$ubicacion = $registro->ubicacion;
	$nomina = $registro->nomina;
	$hijos = $registro->hijos;
	//-------------
	if (substr($_POST["txt_partida"],0,8) == '00000000') {
		$patria = 1;
	} else {
		$patria = 0;
	}
	//-------------
	if ($_POST["txt_categoria"] == 1) {
		$categoria = $categoria_empleado;
		$partida = $partida_empleado;
		$categoria = '0101020051';
		$partida = '401019900000';
	} else {
		$categoria = $_POST["txt_categoria"];
		$partida = $_POST["txt_partida"];
	}
	$num_nomina = 0;
	$tipo_pago = '008';
	$concepto = strtoupper($_POST["txt_concepto"]);
	$fecha = voltea_fecha($_POST["txt_desde"]);
	$desde = voltea_fecha($_POST["txt_desde"]);
	$hasta = voltea_fecha($_POST["txt_desde"]);
	$monto = str_replace('.', '', $_POST['txt_monto']);
	$monto = str_replace(',', '.', $monto);
	if ($_POST["check_hijos"] == 1) {
		$monto = $monto * $hijos;
	}
	//------- SUELDO
	$consultax = "INSERT INTO nomina (patria, id_cont, sueldo_mensual, sueldo, num_nomina, tipo_pago, cargo, categoria, ubicacion, nomina, partida, cedula, anno, fecha, descripcion, desde, hasta, asignaciones, descuentos, total, estatus, usuario) VALUES ('$patria', '$id_cont', '$monto', $monto, '$num_nomina', '$tipo_pago', '$cargo', '$categoria', '$ubicacion', '$nomina', '$partida', '$cedula', " . anno($fecha) . ", '$fecha', '$concepto', '$desde', '$hasta', $monto, 0, $monto, 0, '" . $_SESSION['CEDULA_USUARIO'] . "')"; //echo $consultax;
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

$info = array("tipo" => $tipo, "msg" => $mensaje);

echo json_encode($info);
