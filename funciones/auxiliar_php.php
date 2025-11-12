<?php
//$html = file_get_contents('https://www.bcv.org.ve/');
//$posicion = strpos ( $html , 'USD' ) ;
//$dolar = substr($html, $posicion+3, 125);
//echo trim($dolar);
//--------------
//setlocale(LC_ALL, 'sp_ES','sp', 'es');
//$_SESSION['conexionsql']->query("SET NAMES 'latin1'");
date_default_timezone_set('America/Caracas');

//--------------
$_SESSION['meses_anno'] = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$_SESSION['letras_anno'] = array('', 'dos mil uno', 'dos mil dos', 'dos mil tres', 'dos mil cuatro', 'dos mil cinco', 'dos mil seis', 'dos mil siete', 'dos mil ocho', 'dos mil nueve', 'dos mil diez', 'dos mil once', 'dos mil doce', 'dos mil trece', 'dos mil catorce', 'dos mil quince', 'dos mil dieciseis', 'dos mil diecisiete', 'dos mil dieciocho', 'dos mil diecinueve', 'dos mil veinte', 'dos mil veintiuno', 'dos mil veintidos');
$_SESSION['dias_mes'] = array('', '31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');
$_SESSION['dias_semana'] = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$_SESSION['solvencia'] = array('Solicitada', 'Procesada', '', '', '', '', '', '', '', '', 'Culminada');

define('METHOD', 'AES-256-CBC');
define('SECRET_KEY', '$SIGEM@2021');
define('SECRET_IV', '101712');

//--------------
//$_SESSION['prima_hogar'] = 120;
$_SESSION['quinquenio'] = array("0", "15", "15", "15", "15", "15", "18", "18", "18", "18", "18", "21", "21", "21", "21", "21", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "25");
$_SESSION['prima_prof'] = array("0", "0", "20", "25", "30", "35", "40");
$_SESSION['prima_anno'] = array("0", "1", "2", "3", "4", "5", "6.2", "7.4", "8.6", "9.8", "11", "12.4", "13.8", "15.2", "16.6", "18", "19.6", "21.2", "22.8", "24.4", "26", "27.8", "29.6", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30", "30");
$_SESSION['profesion'] = array('Sin Profesion', 'Bachiller', 'TSU', 'Universitario', 'Especialista', 'Maestria');
//--------------
$_SESSION['almacen'] = array('SI', 'NO');
$_SESSION['archivo'] = array('Prestado', '', '', '', '', '', '', '', '', '', 'Devuelto');
//--------------
$_SESSION['color'] = array(0, 'blue', 'red', 'green', 'orange', 'gray', 'purple', 'brown', 'yellow');
//--------------
$_SESSION['asistencia'] = array('', '', '', '', '');
//$_SESSION['asistencia'] = array('CORRECTO', 'RETARDADO', 'ADELANTADO', 'RETARDADO', 'ADELANTADO');
//--------------
$_SESSION['tipo_nomina'] = array('', ' (Patria)');
//--------------
$_SESSION['estatus_poa'] = array('Registrado', 'En Ejecucion', 'Culminado');
$_SESSION['estatus_odi'] = array('Registrado', 'Registrado', 'Asignando Odi', 'Odis Asignados', 'Aceptando Odi', 'Odi Aceptados', 'Evaluacion en Proceso', 'Funcionario Evaluado', 'Aceptando Evaluaciones', 'Evaluacion Aceptada', 'Proceso Culminado', 'Proceso Culminado');
//--------------
$_SESSION['tipo_pago'] = array('', 'Ch', 'Tr');
$_SESSION['tipo_orden'] = array('', 'Compra', 'Servicio');
$_SESSION['tipo_orden2'] = array('', 'C', 'S');

//--------------
function proyecto_actual()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT id, estatus, descripcion FROM evaluaciones WHERE estatus<10 ORDER BY id DESC";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registro = $tablax->fetch_object();
		$id_proyecto = $registro->id;
		$estatus_proyecto = $registro->estatus;
		$descripcion_proyecto = $registro->descripcion;
	}
	//-----------
	return array($id_proyecto, $estatus_proyecto, $descripcion_proyecto);
}
//--------------
function dias_aguinaldos($ingreso)
{
	if (anno($ingreso) == date('Y')) {
		if (dia($ingreso) < 4 or dia($ingreso) > 18) {
			$ingreso = fecha_a_numero($ingreso);
			if ($ingreso < fecha_a_numero(date('Y') . '/12/04')) {
				$dias = 10;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/11/04')) {
				$dias = 20;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/10/04')) {
				$dias = 30;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/09/04')) {
				$dias = 40;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/08/04')) {
				$dias = 50;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/07/04')) {
				$dias = 60;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/06/04')) {
				$dias = 70;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/05/04')) {
				$dias = 80;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/04/04')) {
				$dias = 90;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/03/04')) {
				$dias = 100;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/02/04')) {
				$dias = 110;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/01/04')) {
				$dias = 120;
			}
		} else {
			$ingreso = fecha_a_numero($ingreso);
			if ($ingreso < fecha_a_numero(date('Y') . '/12/19')) {
				$dias = 5;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/11/19')) {
				$dias = 15;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/10/19')) {
				$dias = 25;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/09/19')) {
				$dias = 35;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/08/19')) {
				$dias = 45;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/07/19')) {
				$dias = 55;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/06/19')) {
				$dias = 65;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/05/19')) {
				$dias = 75;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/04/19')) {
				$dias = 85;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/03/19')) {
				$dias = 95;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/02/19')) {
				$dias = 105;
			}
			if ($ingreso < fecha_a_numero(date('Y') . '/01/19')) {
				$dias = 115;
			}
		}
	} else {
		$dias = 120;
	}
	return ($dias);
}
//--------------
function evaluacion($total)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT rango as txt FROM eval_ponderaciones WHERE $total>=minimo AND $total<=maximo;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$txt = $registrox->txt;
	}
	//-----------
	return $txt;
}
//--------------
function quinquenio($anno)
{
	if ($anno > 0 and $anno <= 5) {
		$dias = 15;
	}
	if ($anno > 5 and $anno <= 10) {
		$dias = 18;
	}
	if ($anno > 10 and $anno <= 15) {
		$dias = 21;
	}
	if ($anno > 15) {
		$dias = 25;
	}
	return ($dias);
}
//--------------
function dias_vacaciones($anno)
{
	if ($anno > 0 and $anno <= 5) {
		$dias = 50;
	}
	if ($anno > 5 and $anno <= 10) {
		$dias = 55;
	}
	if ($anno > 10 and $anno <= 15) {
		$dias = 55;
	}
	if ($anno > 15) {
		$dias = 60;
	}
	return ($dias);
}
//--------------
function nomina($id)
{
	// CONSULTA
	$consulta_x = "SELECT rac.nomina FROM rac WHERE rac.cedula='$id' LIMIT 1;"; //echo $consulta_x;
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return ($registro_x->nomina);
}
//--------------
function firma_op($id, $fecha)
{
	// CONSULTA
	$consulta_x = "SELECT rac_historial.cedula, nombre, rac_historial.cargo FROM a_firmas INNER JOIN rac_historial ON a_firmas.cedula = rac_historial.cedula WHERE a_firmas.id='$id' AND rac_historial.fecha<='$fecha' ORDER BY rac_historial.fecha DESC LIMIT 1;";
	//	echo $consulta_x;
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return array($registro_x->cedula, oraciones($registro_x->nombre . " " . $registro_x->nombre2 . " " . $registro_x->apellido . " " . $registro_x->apellido2), $registro_x->cargo);
}
//--------------
function firma($id)
{
	// CONSULTA
	$consulta_x = "SELECT rac.cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, rac.cargo FROM a_firmas INNER JOIN rac ON a_firmas.cedula = rac.cedula WHERE a_firmas.id='$id' LIMIT 1;"; //echo $consulta_x;
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return array($registro_x->cedula, oraciones($registro_x->nombre . " " . $registro_x->nombre2 . " " . $registro_x->apellido . " " . $registro_x->apellido2), $registro_x->cargo);
}
//--------------
function elimina_caracteres($valor)
{
	$valor = utf8_encode(str_replace('�', 'N', utf8_decode($valor)));
	$valor = utf8_encode(str_replace('�', 'A', utf8_decode($valor)));
	$valor = utf8_encode(str_replace('�', 'E', utf8_decode($valor)));
	$valor = utf8_encode(str_replace('�', 'I', utf8_decode($valor)));
	$valor = utf8_encode(str_replace('�', 'O', utf8_decode($valor)));
	$valor = (str_replace('�', 'U', utf8_decode($valor)));
	//-----------
	return ($valor);
}
//--------------
function personal_activo()
{
	$consulta_x = "SELECT COUNT(cedula) as valor FROM rac WHERE nomina <> 'PENSIONADO' AND nomina <> 'JUBILADOS' AND nomina <> 'EGRESADOS' AND suspendido = '0';";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return ($registro_x->valor);
}
//--------------
function valortickets()
{
	$consulta_x = "SELECT valor FROM a_cesta_tickets ORDER BY fecha DESC LIMIT 1;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return ($registro_x->valor);
}
//--------------
function sueldominimo()
{
	$consulta_x = "SELECT monto FROM a_sueldo_minimo ORDER BY fecha DESC LIMIT 1;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return ($registro_x->monto);
}
//--------------
function lunes($anno, $mes)
{
	$lunes = 0;
	$periodo = ($anno . '-' . $mes . '-01');
	$i = 1;
	//--------------
	$dias = sube_mes($periodo);
	$dias = baja_dia($dias);
	list($a, $b, $dias) = explode('-', $dias);
	$periodo = strtotime($anno . '-' . $mes . '-01');
	//--------------
	while ($i <= $dias) {
		$diasemana = date('N', $periodo);
		if ($diasemana == 1) {
			$lunes++;
		}
		$i++;
		$periodo = $periodo + 86400;
	}
	//-----------
	return ($lunes);
}
//--------------
function bono($nomina, $cargo)
{
	$consulta_x = "SELECT monto FROM a_bonos WHERE codigo='$nomina';";
	$tablax = $_SESSION['conexionsql']->query($consulta_x);
	if ($tablax->num_rows > 1) {
		$cargo = substr($cargo, 0, 3);
		$consulta_x = "SELECT monto FROM a_bonos WHERE codigo='$nomina' and cargo='$cargo';";
		$tablax = $_SESSION['conexionsql']->query($consulta_x);
		if ($tablax->num_rows > 0) {
		} else {
			$consulta_x = "SELECT monto FROM a_bonos WHERE codigo='$nomina' and cargo='Todos';";
			$tablax = $_SESSION['conexionsql']->query($consulta_x);
		}
	}
	//-----------
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
	}
	//-----------
	return ($registrox->monto);
}
//--------------
function prima_hijos()
{
	$consulta_x = "SELECT monto FROM a_asignaciones WHERE id=2;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return ($registro_x->monto);
}
//--------------
function prima_hogar()
{
	$consulta_x = "SELECT monto FROM a_asignaciones WHERE id=10;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return ($registro_x->monto);
}
//--------------
function prima_responsabilidad()
{
	$consulta_x = "SELECT monto FROM a_asignaciones WHERE id=9;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return ($registro_x->monto);
}
//--------------
function prima_representacion()
{
	$consulta_x = "SELECT monto FROM a_asignaciones WHERE id=8;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return ($registro_x->monto);
}
//--------------
function memo_dir_ext($direccion, $anno, $firma)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM cr_memos_dir_ext WHERE (direccion_origen)=$direccion AND (anno)=$anno AND (firma_contralor)=$firma;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function memo_dir($direccion, $anno)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM cr_memos_div WHERE direccion_origen=$direccion AND (anno)=$anno;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function numero_traspaso($anno)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM traslados WHERE anno='$anno';";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function sol_viatico()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM viaticos_solicitudes WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function memo_viatico($direccion)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(memo) as num FROM viaticos_solicitudes WHERE direccion = $direccion AND year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function memo_reasig($division)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(memo) as num FROM bn_reasignaciones WHERE division_actual = $division AND year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function memo_ing($division)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(memo) as num FROM bn_ingresos WHERE division = $division AND year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function memo_sol($division)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(memo) as num FROM bn_solicitudes WHERE division = $division AND year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function num_reasig()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM bn_reasignaciones WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function num_ing()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM bn_ingresos WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function num_sol()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM bn_solicitudes WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function tipo_compra($tipo)
{
	if ($tipo == 'CD') {
		$valor = 'CONTRATACION DIRECTA';
	}
	if ($tipo == 'CC') {
		$valor = 'CONCURSO CERRADO';
	}
	if ($tipo == 'CP') {
		$valor = 'CONSULTA DE PRECIOS';
	}
	//-----------
	return $valor;
}
//--------------
function sig_sol_cont()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(solicitud) as num FROM presupuesto WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num1 = $registrox->num + 1;
	} else {
		$num1 = 1;
	}
	//-----------
	$consultax = "SELECT max(solicitud2) as num FROM presupuesto WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num2 = $registrox->num + 1;
	} else {
		$num2 = 1;
	}
	//-----------
	$consultax = "SELECT max(solicitud3) as num FROM presupuesto WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num3 = $registrox->num + 1;
	} else {
		$num3 = 1;
	}
	//-----------
	$consultax = "SELECT max(solicitud4) as num FROM presupuesto WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num4 = $registrox->num + 1;
	} else {
		$num4 = 1;
	}
	//-----------
	$consultax = "SELECT max(solicitud5) as num FROM presupuesto WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num5 = $registrox->num + 1;
	} else {
		$num5 = 1;
	}
	//-----------
	if ($num1 > $num2) {
		$num = $num1;
	} elseif ($num2 > $num3) {
		$num = $num2;
	} elseif ($num3 > $num4) {
		$num = $num3;
	} elseif ($num4 > $num5) {
		$num = $num4;
	} else {
		$num = $num5;
	}
	return $num;
}
//--------------
function sig_memo_compra($tipo)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM presupuesto WHERE tipo_orden='$tipo' AND year(fecha)='" . date('Y') . "';";
	echo $consultax;
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function sig_punto_cuenta()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(num_punto_cuenta) as num FROM presupuesto WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function sig_memo_cat()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM solicitudes_memos WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function sig_sol_cat()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM solicitudes_catastro WHERE year(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function formato_categoriao($categoria)
{
	$txt = substr($categoria, 0, 2) . '.' . substr($categoria, 2, 2) . '.' . substr($categoria, 4, 7);
	return $txt;
}
//--------------
function formato_categoria($categoria)
{
	$txt = substr($categoria, 0, 4) . substr($categoria, 8, 2);
	return $txt;
}
//--------------
function formato_partida($partida)
{
	$txt = substr($partida, 0, 3) . '.' . substr($partida, 3, 2) . '.' . substr($partida, 5, 2) . '.' . substr($partida, 7, 2) . '.' . substr($partida, 9, 3);
	return $txt;
}
//--------------
function formato_partida2($partida)
{
	$txt = substr($partida, 0, 3) . '.' . substr($partida, 3, 2) . '.' . substr($partida, 5, 2) . '.' . substr($partida, 7, 2);
	return $txt;
}
//--------------
function reten_sig($id, $tipo)
{
	$consultax = "SELECT numero, fecha FROM ordenes_pago_retencion WHERE id_orden_descuento=$id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$numero = $registrox->numero;
		$fecha = ($registrox->fecha);
	} else {
		$fecha = date('Y/m/d');
		$consultax = "SELECT max(numero) AS num FROM ordenes_pago_retencion, ordenes_pago_descuentos WHERE YEAR(ordenes_pago_retencion.fecha) = YEAR (curdate()) AND ordenes_pago_descuentos.id_descuento = $tipo AND ordenes_pago_retencion.id_orden_descuento = ordenes_pago_descuentos.id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);
		if ($registrox = $tablax->fetch_object()) {
			$numero = $registrox->num + 1;
		} else {
			$numero = 1;
		}
		//-------------
		$consultax = "INSERT INTO ordenes_pago_retencion(id_orden_descuento, numero, fecha, usuario) VALUES ($id, $numero, '$fecha', '" . $_SESSION['CEDULA_USUARIO'] . "')";
		$tablax = $_SESSION['conexionsql']->query($consultax);
		//-------------
		$consulta = "UPDATE ordenes_pago_retencion, ordenes_pago_descuentos SET ordenes_pago_retencion.id_tipo=ordenes_pago_descuentos.id_descuento, ordenes_pago_retencion.id_op=ordenes_pago_descuentos.id_orden_pago WHERE ordenes_pago_retencion.id_orden_descuento=0$id AND ordenes_pago_retencion.id_orden_descuento=ordenes_pago_descuentos.id;";
		$tabla = $_SESSION['conexionsql']->query($consulta);
	}
	//-----------
	return ($numero . ' ' . $fecha);
}
//--------------
function estatus_pago($estatus, $numero)
{
	if ($estatus >= 0 and $estatus <= 10) {
		$mensaje = $numero;
	}
	if ($estatus == 99) {
		$mensaje = 'Anulado';
	}
	//--------------
	return $mensaje;
}
//--------------
function estatus_alm($estatus)
{
	if ($estatus == 0) {
		$mensaje = 'Por Solicitar';
	}
	if ($estatus == 3) {
		$mensaje = 'Solicitado';
	}
	if ($estatus == 5) {
		$mensaje = 'Aprobado';
	}
	if ($estatus == 6) {
		$mensaje = 'No Aprobado';
	}
	if ($estatus == 10) {
		$mensaje = 'Despachado';
	}
	if ($estatus == 99) {
		$mensaje = 'Anulado';
	}
	//--------------
	return $mensaje;
}
//--------------
function estatus_ing($estatus)
{
	if ($estatus == 0) {
		$mensaje = 'Por Aprobar';
	}
	//if ($estatus==3)	{	$mensaje = 'Solicitado';	}
	//if ($estatus==5)	{	$mensaje = 'Aprobado';	}
	if ($estatus == 10) {
		$mensaje = 'Ingresado';
	}
	//if ($estatus==99)	{	$mensaje = 'Anulado';	}
	//--------------
	return $mensaje;
}
//--------------
function estatus_memo_ext($estatus)
{
	if ($estatus == 0) {
		$mensaje = 'Por Revision';
	}
	if ($estatus == 5) {
		$mensaje = 'Aprobado';
	}
	if ($estatus == 7) {
		$mensaje = 'Enviado';
	}
	if ($estatus == 10) {
		$mensaje = 'Recibido';
	}
	//--------------
	return $mensaje;
}
//--------------
function estatus_memo_div($estatus)
{
	if ($estatus == 0) {
		$mensaje = 'Por Aprobar';
	}
	if ($estatus == 5) {
		$mensaje = 'Aprobado';
	}
	if ($estatus == 10) {
		$mensaje = 'Enviado';
	}
	//--------------
	return $mensaje;
}
//--------------
function estatus_bn($estatus)
{
	if ($estatus == 0) {
		$mensaje = 'Por Aprobar';
	}
	if ($estatus == 10) {
		$mensaje = 'Aprobado';
	}
	//--------------
	return $mensaje;
}
//--------------
function estatus($estatus, $numero)
{
	if ($estatus == 0) {
		$mensaje = 'Preliminar';
	}
	if ($estatus >= 5 and $estatus <= 10) {
		$mensaje = $numero;
	}
	if ($estatus == 99) {
		$mensaje = 'Anulado';
	}
	//--------------
	return $mensaje;
}
//--------------
function estatus_op($estatus)
{
	if ($estatus == 0) {
		$mensaje = 'Comprometida';
	}
	if ($estatus >= 5 and $estatus < 10) {
		$mensaje = 'Causada';
	}
	if ($estatus >= 10 and $estatus < 99) {
		$mensaje = 'Pagada';
	}
	if ($estatus == 99) {
		$mensaje = 'Anulada';
	}
	//--------------
	return $mensaje;
}
//--------------
function estatus_rrhh($estatus)
{
	if ($estatus == 0) {
		$mensaje = '<div class="badge badge-warning" ></i> PENDIENTE</div>';
	}
	if ($estatus > 0) {
		$mensaje = '<div class="badge badge-success" ><i class="far fa-check-circle"></i> APROBADO</div>';
	}
	//--------------
	return $mensaje;
}
//--------------
function fecha_larga2($fecha)
{
	$fecha = (dia($fecha) . ' dia(s) del mes de ' . $_SESSION['meses_anno'][abs(mes($fecha))] . ' del ' . anno($fecha));
	//--------------
	return $fecha;
}
//--------------
function partida($numero)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT * FROM a_partidas WHERE codigo='$numero';";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	$registrox = $tablax->fetch_object();
	//-----------
	return $registrox->descripcion;
}
//--------------
function num_credito($anno, $tipo)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM credito_adicional WHERE anno=$anno AND tipo_orden=$tipo AND estatus>0;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object()) {
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function num_orden_compra($anno, $tipo)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM orden_solicitudes WHERE anno=$anno AND tipo_orden='$tipo' AND estatus>0;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function orden_sig_patria()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM ordenes_pago WHERE tipo_solicitud='PATRIA' AND YEAR(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object()) {
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function orden_sig_nomina_m()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM ordenes_pago WHERE tipo_solicitud='NOMINA MANUAL' AND YEAR(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object()) {
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function orden_sig()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM ordenes_pago WHERE (tipo_solicitud='ORDEN' or tipo_solicitud='MANUAL' or tipo_solicitud='NOMINA') AND YEAR(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object()) {
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function compromiso_sig($tipo)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM orden_solicitudes WHERE tipo_orden='$tipo' AND YEAR(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object()) {
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function orden_fin()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM ordenes_pago WHERE tipo_solicitud='FINANCIERA' AND YEAR(fecha)=" . date('Y') . ";";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function comprobante_sig($tipo, $anno)
{
	if ($tipo == 'FINANCIERA') {
		$consultax = "SELECT max(num_comprobante) as num FROM ordenes_pago WHERE tipo_solicitud='FINANCIERA' AND num_comprobante>0 AND year(fecha)=$anno AND estatus>0;";
	} elseif ($tipo == 'NOMINA' or $tipo == 'NOMINA MANUAL') {
		$consultax = "SELECT max(num_comprobante) as num FROM ordenes_pago WHERE (tipo_solicitud='NOMINA' or tipo_solicitud='NOMINA MANUAL') AND num_comprobante>0 AND year(fecha)=$anno AND estatus>0;";
	} else {
		$consultax = "SELECT max(num_comprobante) as num FROM ordenes_pago WHERE (tipo_solicitud<>'FINANCIERA' and tipo_solicitud<>'NOMINA' and tipo_solicitud<>'NOMINA MANUAL') AND num_comprobante>0 AND year(fecha)=$anno AND estatus>0;";
	}
	// PARA BUSCAR EL MAXIMO
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function nomina_sig()
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(num_nomina) as num FROM nomina;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object()) {
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function num_sol_pago($anno)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(num_sol_pago) as num FROM nomina_solicitudes WHERE anno=$anno AND estatus>0;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function num_eventual($tipo_pago, $nomina, $anno)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM nomina_solicitudes WHERE anno=$anno AND nomina='$nomina' AND tipo_pago='$tipo_pago';";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registrox = $tablax->fetch_object();
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function num_nomina2($nomina, $anno)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM nomina_solicitudes WHERE tipo_pago = '013' AND anno=$anno AND nomina='$nomina' and estatus>0;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object()) {
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function num_nomina($descripcion, $nomina, $anno)
{
	// PARA BUSCAR EL MAXIMO
	$consultax = "SELECT max(numero) as num FROM nomina_solicitudes WHERE anno=$anno AND nomina='$nomina' AND descripcion='$descripcion' and estatus>0;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($registrox = $tablax->fetch_object()) {
		$num = $registrox->num + 1;
	} else {
		$num = 1;
	}
	//-----------
	return $num;
}
//--------------
function edad($fechanacimiento)
{
	list($anno, $mes, $dia) = explode("-", $fechanacimiento);

	$ano_actual  = date("Y");
	$mes_actual = date("m");
	$dia_actual   = date("d");

	$annos = $ano_actual - $anno;

	if ($mes > $mes_actual) {
		$annos--;
	} elseif ($mes == $mes_actual and $dia >= $dia_actual) {
		//$annos ++;
	} else {
		//$annos --;
	}

	return $annos;
}
//--------------
function annos_exacto($a, $m, $d, $aa, $mm, $dd)
{
	if ($aa > $a) {
		if ($mm <= $m) {
			if ($mm == $m) {
				if ($dd <= $d) {
					$annos = ($aa - $a) - 1;
				} else {
					$annos = ($aa - $a);
				}
			} else {
				$annos = ($aa - $a) - 1;
			}
		} else {
			$annos = ($aa - $a);
		}
	}
	//------------
	return ($annos);
}
//--------------
function annos($a, $m, $aa, $mm)
{
	if ($aa > $a) {
		if ($mm >= $m) {
			$annos = $aa - $a;
		} else {
			$annos = ($aa - $a) - 1;
		}
	}
	//------------
	return ($annos);
}
//--------------
function formato_periodo($forma, $inicio, $final)
{
	if ($forma == 'M') {
		$valor = periodo($inicio);
	}
	if ($forma == 'A') {
		$valor = anno($inicio);
	}
	if ($forma == 'D') {
		$valor = voltea_fecha($inicio) . ' al ' . voltea_fecha($final);
	}
	if ($forma == 'U') {
		$valor = voltea_fecha($inicio);
	}
	//-----------
	return $valor;
}
//--------------
function generarRuta($reporte, $constante, $id)
{
	if ($_SERVER['HTTP_HOST'] == 'localhost') {
		$ruta = "http://localhost/cebg/validarqr.php?qrv=";
	} else {
		$ruta = "http://app.cebg.com.ve/validarqr.php?qrv=";
	}

	$code = $ruta . encriptar($reporte . $constante . $id);
	return $code;
}
//--------------
function encriptar($string)
{
	$output = FALSE;
	$key = hash('sha256', SECRET_KEY);
	$iv = substr(hash('sha256', SECRET_IV), 0, 16);
	$output = openssl_encrypt($string, METHOD, $key, 0, $iv);
	$output = base64_encode($output);
	return $output;
}

function decriptar($string)
{
	$key = hash('sha256', SECRET_KEY);
	$iv = substr(hash('sha256', SECRET_IV), 0, 16);
	$output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
	return $output;
}
//--------------
function info_area_ci($id)
{
	// CONSULTA
	$consulta_x = "SELECT contralor, a_areas.id, a_areas.id_direccion, a_areas.area, a_direcciones.direccion FROM a_areas, rac, a_direcciones WHERE rac.id_div = a_direcciones.id AND a_areas.id = rac.id_area AND a_areas.id_direccion = a_direcciones.id AND rac.cedula='$id' LIMIT 1;"; //echo $consulta_x;
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return array($registro_x->contralor, $registro_x->id, $registro_x->area, $registro_x->id_direccion, $registro_x->direccion);
}
//--------------
function info_area($id)
{
	// CONSULTA
	$consulta_x = "SELECT contralor, a_areas.id, a_areas.id_direccion, a_areas.area, a_direcciones.direccion FROM a_areas, a_direcciones WHERE a_areas.id_direccion = a_direcciones.id AND a_areas.id = 0$id LIMIT 1;"; //echo $consulta_x;
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return array($registro_x->contralor, $registro_x->id, $registro_x->area, $registro_x->id_direccion, $registro_x->direccion);
}
//--------------
function jefe_direccion_x_area($id)
{
	// CONSULTA
	$consulta_x = "SELECT CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, a_direcciones.* FROM a_areas,	rac, a_direcciones WHERE a_areas.id_direccion = a_direcciones.id AND rac.cedula = a_direcciones.cedula AND a_areas.id = 0$id LIMIT 1;"; //echo $consulta_x;
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return array($registro_x->cedula, $registro_x->nombre . " " . $registro_x->nombre2 . " " . $registro_x->apellido . " " . $registro_x->apellido2, $registro_x->cargo);
}
//--------------
function division_bienes($id)
{
	// CONSULTA
	$consulta_x = "SELECT * FROM bn_dependencias WHERE	id = $id LIMIT 1;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return array($registro_x->division, $registro_x->codigo);
}
//--------------
function jefe_direccion($id)
{
	// CONSULTA
	$consulta_x = "SELECT rac.*, a_direcciones.* FROM rac,	a_direcciones WHERE	rac.cedula = a_direcciones.cedula AND a_direcciones.id = $id LIMIT 1;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return array($registro_x->cedula, $registro_x->nombre . " " . $registro_x->nombre2 . " " . $registro_x->apellido . " " . $registro_x->apellido2, $registro_x->cargo, $registro_x->providencia, $registro_x->fecha_prov, $registro_x->gaceta, $registro_x->fecha_gaceta);
}
//--------------
function extraer_iniciales($nombre)
{
	$name = '';
	$explode = explode(' ', $nombre);
	foreach ($explode as $x) {
		$name .=  $x[0];
	}
	return $name;
}
//--------------
function empleado($id)
{
	// CONSULTA
	$consulta_x = "SELECT * FROM rac WHERE cedula=$id LIMIT 1;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return array($registro_x->cedula, $registro_x->nombre . " " . $registro_x->nombre2 . " " . $registro_x->apellido . " " . $registro_x->apellido2, $registro_x->cargo);
}
//--------------
function contribuyente($id)
{
	// CONSULTA
	$consulta_x = "SELECT * FROM contribuyente WHERE id = '$id' or rif= '$id' LIMIT 1";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return array($registro_x->rif, $registro_x->nombre, $registro_x->direccion, $registro_x->email);
}

//--------------
function id_contribuyente($id)
{
	// CONSULTA
	$consulta_x = "SELECT id FROM contribuyente WHERE rif = '$id' LIMIT 1";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	$registro_x = $tabla_x->fetch_object();
	//-----------
	return ($registro_x->id);
}

//--------------
function fecha_larga($fecha)
{
	$fecha = (dia($fecha) . ' de ' . $_SESSION['meses_anno'][abs(mes($fecha))] . ' del ' . anno($fecha));
	//--------------
	return $fecha;
};

//--------------
function rellena_cero($id, $cant)
{
	//-----------------------
	$id =  sprintf("%00" . $cant . "s", $id);
	//-----------
	return $id;
}
//--------------
function formato_cel($a)
{
	return substr($a, 0, 4) . '-' . substr($a, 4, 7);
}
//--------------
function formato_ci($a)
{
	return (strtoupper(substr($a, 0, 1)) . '-' . formato_cedula(abs(substr($a, 1, 9))));
}

//--------------
function formato_rif($a)
{
	return (strtoupper(substr($a, 0, 1)) . '-' . substr($a, 1, 8) . '-' . substr($a, 9, 1));
}
//--------------
function formato_cuenta($a)
{
	return (substr($a, 0, 4)) . '-' . substr($a, 4, 4) . '-' . substr($a, 8, 2) . '-' . substr($a, 10, 10);
}
//--------------
function formato_ced($a)
{
	return (strtoupper(substr($a, 0, 1)) . '-' . substr($a, 1, 9));
}
//--------------
function palabras($a)
{
	return utf8_decode((ucwords(strtolower(trim($a)))));
}

//--------------
function dia($fecha)
{
	if (substr($fecha, 2, 1) == '-' or substr($fecha, 4, 1) == '-') {
		$caracter = '-';
	} else {
		$caracter = '/';
	}
	//--------------
	$vector_fecha = explode($caracter, $fecha);
	return substr($vector_fecha[2], 0, 2);
};

//--------------
function mes($fecha)
{
	if (substr($fecha, 2, 1) == '-' or substr($fecha, 4, 1) == '-') {
		$caracter = '-';
	} else {
		$caracter = '/';
	}
	//--------------
	$vector_fecha = explode($caracter, $fecha);
	return $vector_fecha[1];
};

//--------------
function anno($fecha)
{
	if (substr($fecha, 2, 1) == '-' or substr($fecha, 4, 1) == '-') {
		$caracter = '-';
	} else {
		$caracter = '/';
	}
	//--------------
	$vector_fecha = explode($caracter, $fecha);
	return $vector_fecha[0];
};
//--------------
function oraciones($cadena)
{
	$i = 0;
	$valor = explode(" ", $cadena);
	$cadena = '';
	while ($i < 10) {
		$cadena = $cadena . ' ' . ucfirst(strtolower(($valor[$i])));
		$i++;
	}
	//	$cadena = utf8_decode($cadena);

	return trim($cadena);
}
//--------------
function oraciones2($cadena, $posicion)
{
	$i = $posicion;
	$valor = explode(" ", $cadena);
	$cadena = '';
	while ($i < 10) {
		//$cadena = $cadena.' '.ucfirst(minuscula($valor[$i]));	
		$cadena = $cadena . ' ' . (($valor[$i]));
		$i++;
	}
	//$cadena = utf8_decode($cadena);
	return trim($cadena);
}

//--------------
function mantenimiento()
{
	if ($_SESSION['ADMINISTRADOR'] <> 1) {
		echo "<script type=\"text/javascript\">alert('Sistema en Mantenimiento!');</script>";
		exit();
	}
}

//--------------
function formato_natural($a)
{
	return (number_format($a, 2, '.', ''));
}

//--------------
function formato_cedula($a)
{
	return (number_format($a, 0, '', '.'));
}

//--------------
function formato_moneda($a)
{
	return (number_format($a, 2, ',', '.'));
}
//--------------
function formato_ret($a)
{
	return (number_format($a, 5, ',', '.'));
}
//--------------
function formato_petro($a)
{
	return (number_format($a, 15, ',', '.'));
}

//--------------
function minuscula($a)
{
	return (mb_strtolower(trim(($a))));
}

//--------------
function mayuscula($a)
{
	return strtoupper(trim(utf8_decode($a)));
}

//--------------
function redondea($a)
{
	return (number_format($a, 0, ',', '.'));
}

//--------------
function voltea_fecha($a)
{
	if ($a == '') {
		return '0000/00/00';
	} else {
		if (substr($a, 2, 1) == '-' or substr($a, 4, 1) == '-') {
			$caracter = '-';
		} else {
			$caracter = '/';
		}
		//-----------
		$a = explode($caracter, $a);
		$aux = $a[2];
		$a[2] = $a[0];
		$a[0] = $aux;
		$caracter = '/';
		return implode($caracter, $a);
	}
}
//--------------
function voltea_fecha2($a)
{
	if ($a == '') {
		if (substr($a, 2, 1) == '-' or substr($a, 2, 1) == '/') {
			return '00/00/0000';
		} else {
			if (substr($a, 4, 1) == '-' or substr($a, 4, 1) == '/') {
				return '00/00/0000';
			} else {
				return '0000/00/00';
			}
		}
		//-----------
	} else {
		if (substr($a, 2, 1) == '-' or substr($a, 4, 1) == '-') {
			$caracter = '-';
		} else {
			$caracter = '/';
		}
		//-----------
		$a = explode($caracter, $a);
		$aux = $a[2];
		$a[2] = $a[0];
		$a[0] = $aux;
		$caracter = '/';
		return implode($caracter, $a);
	}
}
//--------------
function extrae_fecha($a)
{
	return substr($a, 0, 10);
}

//--------------
function hora_militar($a)
{
	if ($a <> '') {
		$hora = substr($a, 0, 2);
		$minuto = substr($a, 3, 2);
		if ($hora < 13) {
			$tiempo = 'am';
			if ($hora == 12) {
				$tiempo = 'm';
			}
		} else {
			$hora = $hora - 12;
			$tiempo = 'pm';
		}
		return ($hora . ':' . $minuto . ' ' . $tiempo);
	} else {
		return ('');
	}
}

//--------------
function extrae_hora($a)
{
	$hora = substr($a, 11, 2);
	$minuto = substr($a, 14, 2);

	return ($hora . ':' . $minuto);
}

//--------------
function extrae_hora_laboral($a)
{
	$hora = substr($a, 11, 8);
	//	if ($hora<13)
	//		{
	//		$tiempo= 'am';
	//		}
	//	else
	//		{
	//		$hora = $hora - 12;
	//		$tiempo= 'pm';
	//		}
	//	$minuto = substr($a,14,2);

	return ($hora);
}
//--------------
function extrae_hora_laboral2($a)
{
	$hora = substr($a, 11, 2);
	if ($hora < 8) {
		$tiempo = 'pm';
	} else {
		//		$hora = $hora - 12;
		$tiempo = 'am';
	}
	$minuto = substr($a, 14, 2);

	return ($hora . ':' . $minuto . ' ' . $tiempo);
}
////--------------
//function extrae_hora_laboral($a)
//	{
//	$hora = substr($a,11,2);
//	if ($hora<13)
//		{
//		$tiempo= 'am';
//		}
//	else
//		{
//		$hora = $hora - 12;
//		$tiempo= 'pm';
//		}
//	$minuto = substr($a,14,2);
//	
//	return ($hora.':'.$minuto.' '.$tiempo);
//	}

//--------------
function fecha_a_numero($a)
{
	if (substr($a, 2, 1) == '-' or substr($a, 4, 1) == '-') {
		$caracter = '-';
	} else {
		$caracter = '/';
	}
	//------------
	list($anno, $mes, $dia) = explode($caracter, $a);
	$fecha = mktime(0, 0, 0, $mes, $dia, $anno);
	return ($fecha);
}
//--------------
function sube_dia($a)
{
	return (date('Y-m-d', strtotime($a . "+ 1 day")));
}
//--------------
function baja_dia($a)
{
	return (date('Y-m-d', strtotime($a . "- 1 day")));
}
//--------------
function sube_mes($a)
{
	return (date('Y-m-d', strtotime($a . "+ 1 month")));
}
//--------------
function baja_mes($a)
{
	return (date('Y-m-d', strtotime($a . "- 1 month")));
}
//--------------
function baja_anno($a)
{
	return (date('Y-m-d', strtotime($a . "- 1 year")));
}
//--------------
function periodo($a)
{
	return (date('m/Y', strtotime($a)));
}
//--------------
function periodo2($a)
{
	return ($_SESSION['meses_anno'][abs(date('m', strtotime($a)))] . ' ' . date('Y', strtotime($a)));
}
//--------------
function dia_vencimiento($fecha, $dias)
{
	$datestart = strtotime($fecha);
	$i = 1;
	while ($i <= $dias) {
		$datestart = $datestart + 86400;
		$diasemana = date('N', $datestart);
		if ($diasemana <> 6 and $diasemana <> 7) {
			//BUSCAMOS SI ES DIA FERIADO
			$consultax = "SELECT dia as fecha FROM dias_feriados WHERE dia='" . date("Y-m-d", $datestart) . "'";
			$tablax = $_SESSION['conexionsql']->query($consultax);
			if ($tablax->num_rows == 0) {
				$i++;
			}
		}
	}
	return date("Y-m-d", $datestart);
}

//--------------

function unidad($numuero)
{
	switch ($numuero) {
		case 9: {
				$numu = "NUEVE";
				break;
			}
		case 8: {
				$numu = "OCHO";
				break;
			}
		case 7: {
				$numu = "SIETE";
				break;
			}
		case 6: {
				$numu = "SEIS";
				break;
			}
		case 5: {
				$numu = "CINCO";
				break;
			}
		case 4: {
				$numu = "CUATRO";
				break;
			}
		case 3: {
				$numu = "TRES";
				break;
			}
		case 2: {
				$numu = "DOS";
				break;
			}
		case 1: {
				$numu = "UNO";
				break;
			}
		case 0: {
				$numu = "";
				break;
			}
	}
	return $numu;
}

function decena($numdero)
{

	if ($numdero >= 90 && $numdero <= 99) {
		$numd = "NOVENTA ";
		if ($numdero > 90)
			$numd = $numd . "Y " . (unidad($numdero - 90));
	} else if ($numdero >= 80 && $numdero <= 89) {
		$numd = "OCHENTA ";
		if ($numdero > 80)
			$numd = $numd . "Y " . (unidad($numdero - 80));
	} else if ($numdero >= 70 && $numdero <= 79) {
		$numd = "SETENTA ";
		if ($numdero > 70)
			$numd = $numd . "Y " . (unidad($numdero - 70));
	} else if ($numdero >= 60 && $numdero <= 69) {
		$numd = "SESENTA ";
		if ($numdero > 60)
			$numd = $numd . "Y " . (unidad($numdero - 60));
	} else if ($numdero >= 50 && $numdero <= 59) {
		$numd = "CINCUENTA ";
		if ($numdero > 50)
			$numd = $numd . "Y " . (unidad($numdero - 50));
	} else if ($numdero >= 40 && $numdero <= 49) {
		$numd = "CUARENTA ";
		if ($numdero > 40)
			$numd = $numd . "Y " . (unidad($numdero - 40));
	} else if ($numdero >= 30 && $numdero <= 39) {
		$numd = "TREINTA ";
		if ($numdero > 30)
			$numd = $numd . "Y " . (unidad($numdero - 30));
	} else if ($numdero >= 20 && $numdero <= 29) {
		if ($numdero == 20)
			$numd = "VEINTE ";
		else
			$numd = "VEINTI" . (unidad($numdero - 20));
	} else if ($numdero >= 10 && $numdero <= 19) {
		switch ($numdero) {
			case 10: {
					$numd = "DIEZ ";
					break;
				}
			case 11: {
					$numd = "ONCE ";
					break;
				}
			case 12: {
					$numd = "DOCE ";
					break;
				}
			case 13: {
					$numd = "TRECE ";
					break;
				}
			case 14: {
					$numd = "CATORCE ";
					break;
				}
			case 15: {
					$numd = "QUINCE ";
					break;
				}
			case 16: {
					$numd = "DIECISEIS ";
					break;
				}
			case 17: {
					$numd = "DIECISIETE ";
					break;
				}
			case 18: {
					$numd = "DIECIOCHO ";
					break;
				}
			case 19: {
					$numd = "DIECINUEVE ";
					break;
				}
		}
	} else
		$numd = unidad($numdero);
	return $numd;
}

function centena($numc)
{
	if ($numc >= 100) {
		if ($numc >= 900 && $numc <= 999) {
			$numce = "NOVECIENTOS ";
			if ($numc > 900)
				$numce = $numce . (decena($numc - 900));
		} else if ($numc >= 800 && $numc <= 899) {
			$numce = "OCHOCIENTOS ";
			if ($numc > 800)
				$numce = $numce . (decena($numc - 800));
		} else if ($numc >= 700 && $numc <= 799) {
			$numce = "SETECIENTOS ";
			if ($numc > 700)
				$numce = $numce . (decena($numc - 700));
		} else if ($numc >= 600 && $numc <= 699) {
			$numce = "SEISCIENTOS ";
			if ($numc > 600)
				$numce = $numce . (decena($numc - 600));
		} else if ($numc >= 500 && $numc <= 599) {
			$numce = "QUINIENTOS ";
			if ($numc > 500)
				$numce = $numce . (decena($numc - 500));
		} else if ($numc >= 400 && $numc <= 499) {
			$numce = "CUATROCIENTOS ";
			if ($numc > 400)
				$numce = $numce . (decena($numc - 400));
		} else if ($numc >= 300 && $numc <= 399) {
			$numce = "TRESCIENTOS ";
			if ($numc > 300)
				$numce = $numce . (decena($numc - 300));
		} else if ($numc >= 200 && $numc <= 299) {
			$numce = "DOSCIENTOS ";
			if ($numc > 200)
				$numce = $numce . (decena($numc - 200));
		} else if ($numc >= 100 && $numc <= 199) {
			if ($numc == 100)
				$numce = "CIEN ";
			else
				$numce = "CIENTO " . (decena($numc - 100));
		}
	} else
		$numce = decena($numc);

	return $numce;
}

function miles($nummero)
{
	if ($nummero >= 1000 && $nummero < 2000) {
		$numm = "MIL " . (centena($nummero % 1000));
	}
	if ($nummero >= 2000 && $nummero < 10000) {
		$numm = unidad(Floor($nummero / 1000)) . " MIL " . (centena($nummero % 1000));
	}
	if ($nummero < 1000)
		$numm = centena($nummero);

	return $numm;
}

function decmiles($numdmero)
{
	if ($numdmero == 10000)
		$numde = "DIEZ MIL";
	if ($numdmero > 10000 && $numdmero < 20000) {
		$numde = decena(Floor($numdmero / 1000)) . "MIL " . (centena($numdmero % 1000));
	}
	if ($numdmero >= 20000 && $numdmero < 100000) {
		$numde = decena(Floor($numdmero / 1000)) . " MIL " . (miles($numdmero % 1000));
	}
	if ($numdmero < 10000)
		$numde = miles($numdmero);

	return $numde;
}

function cienmiles($numcmero)
{
	if ($numcmero == 100000)
		$num_letracm = "CIEN MIL";
	if ($numcmero >= 100000 && $numcmero < 1000000) {
		$num_letracm = centena(Floor($numcmero / 1000)) . " MIL " . (centena($numcmero % 1000));
	}
	if ($numcmero < 100000)
		$num_letracm = decmiles($numcmero);
	return $num_letracm;
}

function millon($nummiero)
{
	if ($nummiero >= 1000000 && $nummiero < 2000000) {
		$num_letramm = "UN MILLON " . (cienmiles($nummiero % 1000000));
	}
	if ($nummiero >= 2000000 && $nummiero < 10000000) {
		$num_letramm = unidad(Floor($nummiero / 1000000)) . " MILLONES " . (cienmiles($nummiero % 1000000));
	}
	if ($nummiero < 1000000)
		$num_letramm = cienmiles($nummiero);

	return $num_letramm;
}

function decmillon($numerodm)
{
	if ($numerodm == 10000000)
		$num_letradmm = "DIEZ MILLONES";
	if ($numerodm > 10000000 && $numerodm < 20000000) {
		$num_letradmm = decena(Floor($numerodm / 1000000)) . "MILLONES " . (cienmiles($numerodm % 1000000));
	}
	if ($numerodm >= 20000000 && $numerodm < 100000000) {
		$num_letradmm = decena(Floor($numerodm / 1000000)) . " MILLONES " . (millon($numerodm % 1000000));
	}
	if ($numerodm < 10000000)
		$num_letradmm = millon($numerodm);

	return $num_letradmm;
}

function cienmillon($numcmeros)
{
	if ($numcmeros == 100000000)
		$num_letracms = "CIEN MILLONES";
	if ($numcmeros >= 100000000 && $numcmeros < 1000000000) {
		$num_letracms = centena(Floor($numcmeros / 1000000)) . " MILLONES " . (millon($numcmeros % 1000000));
	}
	if ($numcmeros < 100000000)
		$num_letracms = decmillon($numcmeros);
	return $num_letracms;
}

function baja_monto($valor, $monto)
{
	while ($valor > $monto) {
		$valor = $valor - $monto;
	}
	return $valor;
}

function milmillon($nummierod)
{
	if ($nummierod >= 1000000000 && $nummierod < 2000000000) {
		$num_letrammd = "MIL " . (cienmillon($nummierod % 1000000000));
	}
	if ($nummierod >= 2000000000 && $nummierod < 10000000000) {
		$num_letrammd = unidad(Floor($nummierod / 1000000000)) . " MIL " . (cienmillon(baja_monto($nummierod, 1000000000)));
	}
	if ($nummierod >= 10000000000 && $nummierod < 100000000000) {
		$num_letrammd = decena(Floor($nummierod / 1000000000)) . " MIL " . (cienmillon(baja_monto($nummierod, 1000000000)));
	}
	if ($nummierod >= 100000000000 && $nummierod < 1000000000000) {
		$num_letrammd = centena(Floor($nummierod / 1000000000)) . " MIL " . (cienmillon(baja_monto($nummierod, 1000000000)));
	}
	if ($nummierod < 1000000000)
		$num_letrammd = cienmillon($nummierod);

	return $num_letrammd;
}

function valorEnLetras($numero)
{
	$num = str_replace(",", "", $numero);
	$num = number_format($num, 2, '.', '');
	$cents = substr($num, strlen($num) - 2, strlen($num) - 1);
	//$num = (int)$num;

	$numf = milmillon($num);

	if ($cents == 0) {
		$cents = 'CERO' . " CENTIMOS";
	} elseif ($cents == 1) {
		$cents = 'UN CENTIMO';
	} else {
		$cents = decena($cents) . " CENTIMOS";
	}

	return $numf . " BOLIVARES CON " . ($cents);
}

// function enviar_correo( $email )
// 	{
// 			//************************* TEXTO DEL MENSAJE ****************************************
// 			// destinatarios
// 			$para  = $email;// . ', '; // atenci�n a la coma
// 			//$para .= 'wez@example.com';

// 			// t�tulo
// 			$t�tulo = 'SOLICITUD EN PROCESO Y LA MISMA TIENE DEUDAS PENDIENTES';

// 			// mensaje
// 			$mensaje = '
// 			<html>
// 			<head>
// 			  <title>SU SOLICITUD '.$numdeclaracion.' DE FECHA '.$fecha.' SE ENCUENTRA EN PROCESO</title>
// 			</head>
// 			<body>
// 			  <p>La Alcald�a Bolivariana Francisco de Miranda cumple con informar que se le ha registrado las deudas pendientes de pago, relacionadas con su solicitud n�mero '.$numerosolicitud.' de fecha '.$fechasolicitud.' para poder continuar con el proceso de la solicitud, debe proceder a la cancelaci�n de las mismas, a trav�s de nuestro sistema online o dirigiendo a las taquillas de pago ubicadas en nuestras instalaciones.</p>
// 			  <p>Le invitamos a continuar utilizando nuestro sistema ONLINE - www.alcaldiafranciscodemiranda.com, estamos a su servicio.</p>
// 			</body>
// 			</html>
// 			';

// 			// Para enviar un correo HTML, debe establecerse la cabecera Content-type
// 			$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
// 			$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// 			// Cabeceras adicionales
// 			$cabeceras .= 'To: '. $para . "\r\n";
// 			$cabeceras .= 'From: fundacion <notificaciones@alcaldiafranciscodemiranda.com>' . "\r\n";
// 			//$cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
// 			//$cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";

// 			// Enviarlo
// 			//$respuesta = enviar_email($para, $asunto, $mensaje, $cabeceras);
// 			//mail($para, $t�tulo, $mensaje, $cabeceras);
// 		//************************************************************************************
// 	}  
