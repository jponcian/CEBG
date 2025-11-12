<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$info = array();
$cedula = trim($_POST['id']);
$horario_manual = ($_GET['tipo']);
$sweet = 'success';
$estatus = 0;
//-------
$ci = explode("¿", $cedula);
$cedula = $ci[2];
if ($cedula == '') {
	$cedula = trim($_POST['id']);
}
//-------
// Validación temprana de cédula
if ($cedula === '' || !ctype_digit($cedula)) {
	$info = array(
		"tipo" => 'danger',
		"msg"  => 'Cédula vacía o con formato inválido. Verifique e intente nuevamente.',
		"pos"  => 'bottom-end',
		"timer" => 5000
	);
	echo json_encode($info);
	exit;
}
//-------
if ($cedula <> '') {
	//-------
	$consultx = "SELECT id, tipo, horario FROM asistencia_diaria WHERE cedula='$cedula' AND fecha='" . date('Y/m/d') . "' ORDER BY id DESC LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows > 0) {
		$registro = $tablx->fetch_object();
		if ($registro->tipo == 'ENTRADA') {
			$tipo = 2;
			$id = $registro->id;
			$horario = $registro->horario;
		} elseif ($registro->tipo == 'SALIDA') {
			$tipo = 1;
			$horario = $registro->horario;
		}
	} else {
		$tipo = 1;
	}
}
//-------
if ($tipo == 1) {
	$tipo = 'ENTRADA';
}
if ($tipo == 2) {
	$tipo = 'SALIDA';
}

//---------
$hora = date('H'); //echo $hora;
//$hora = 12 ; //echo $hora;
if ($horario_manual == '1' or $horario_manual == '2') {
	if ($horario_manual == 2) {
		$consultx = "SELECT ingreso, horario FROM a_horario WHERE tipo = '$tipo' AND horario > '12:00:00' ORDER BY id DESC;";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		$horario = $registro->horario;
		$ingreso = $registro->ingreso;
	} else {
		$consultx = "SELECT ingreso, horario FROM a_horario WHERE tipo = '$tipo' AND horario <= '12:00:00' ORDER BY id DESC;";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		$horario = $registro->horario;
		$ingreso = $registro->ingreso;
	}
} else {
	if ($tipo == 'SALIDA') {
		$consultx = "SELECT ingreso, horario FROM a_horario WHERE tipo = '$tipo' AND horario > '$horario';"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro = $tablx->fetch_object();
		$horario = $registro->horario;
		$ingreso = $registro->ingreso;
	} else {
		if ($hora >= 12) {
			$consultx = "SELECT ingreso, horario FROM a_horario WHERE tipo = '$tipo' AND horario > '12:00:00' ORDER BY id DESC;";
			$tablx = $_SESSION['conexionsql']->query($consultx);
			$registro = $tablx->fetch_object();
			$horario = $registro->horario;
			$ingreso = $registro->ingreso;
		} else {
			$consultx = "SELECT ingreso, horario FROM a_horario WHERE tipo = '$tipo' AND horario <= '12:00:00' ORDER BY id DESC;";
			$tablx = $_SESSION['conexionsql']->query($consultx);
			$registro = $tablx->fetch_object();
			$horario = $registro->horario;
			$ingreso = $registro->ingreso;
		}
	}
}
//---------
$hora = date('H:i:s');
//$hora = '12:30:00';
if ($hora > $ingreso and $tipo == 'ENTRADA') {
	if ($hora >= 12) {
		$estatus = 0;
	} else {
		$estatus = 1;
	}
}
if ($hora < $ingreso and $tipo == 'SALIDA') {
	if ($hora >= 12) {
		$estatus = 0;
	} else {
		$estatus = 0;
	}
}
//---------
if ($estatus == 1) {
	$consultx = "SELECT cedula FROM asistencia_diaria WHERE cedula='$cedula' AND fecha='" . date('Y/m/d') . "' AND tipo = '$tipo' AND estatus=0;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows > 0) {
		$estatus = 0;
	}
}
//---------
$mensaje = 'Procesado!';

if ($mensaje == 'Procesado!') {
	//-------
	$consultx = "SELECT rac.cargo, id_area, a_direcciones.id, a_direcciones.direccion FROM rac, a_direcciones WHERE rac.id_div = a_direcciones.id AND rac.cedula = '$cedula';";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	// Si la cédula no existe en RAC, no permitir el registro
	if (!$tablx || $tablx->num_rows == 0) {
		$sweet = 'danger';
		$mensaje = 'La cédula ingresada no existe. No se puede cargar el registro.';
		$info = array(
			"tipo" => $sweet,
			"msg" => $mensaje,
			// Pistas para el frontend (si las usa): toast en esquina inferior derecha por 5s
			"pos" => "bottom-end",
			"timer" => 5000
		);
		echo json_encode($info);
		exit;
	}
	$registro = $tablx->fetch_object();
	$id_div = $registro->id;
	$direccion = $registro->direccion;
	$id_area = $registro->id_area;
	$cargo = $registro->cargo;
	//-------
	$consultx = "SELECT tipo, descripcion FROM rrhh_permisos WHERE cedula = '$cedula' AND desde >= '" . date('Y/m/d') . "' AND hasta <= '" . date('Y/m/d') . "';";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows > 0) {
		$registro = $tablx->fetch_object();
		$tipop = $registro->tipo . '(' . $registro->descripcion . ')';
	} else {
		$tipop = '';
	}
	//-------
	$consultx = "INSERT INTO asistencia_diaria (observacion, cargo, id_area, id_direccion, direccion, funcionarios, estatus, horario, cedula, tipo, fecha, hora, usuario) VALUES ('$tipop', '$cargo', '$id_area', '$id_div', '$direccion', '" . personal_activo() . "', '$estatus', '$horario', '$cedula', '$tipo', '" . date('Y/m/d') . "', '$hora', '" . $_SESSION['CEDULA_USUARIO'] . "');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//--------
	$consultx = "UPDATE asistencia_diaria, rac SET asistencia_diaria.id_direccion = rac.id_div, asistencia_diaria.cargo = rac.cargo WHERE asistencia_diaria.cedula = rac.cedula AND asistencia_diaria.id_direccion =0;";
	//	$tablx = $_SESSION['conexionsql']->query($consultx);
	//--------
	$consultx = "UPDATE asistencia_diaria, a_direcciones SET asistencia_diaria.direccion = a_direcciones.direccion WHERE asistencia_diaria.id_direccion = a_direcciones.id AND asistencia_diaria.direccion = '0';";
	//	$tablx = $_SESSION['conexionsql']->query($consultx);
	//--------
	if ($tipo == 'SALIDA') {
		$consultx = "UPDATE asistencia_diaria SET salio=1 WHERE id = '$id';";
		$tablx = $_SESSION['conexionsql']->query($consultx);
	}
}

//-------
$info = array("tipo" => $sweet, "msg" => $mensaje);
echo json_encode($info);
