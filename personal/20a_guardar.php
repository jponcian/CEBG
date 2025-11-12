<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//---------
$info = array();
$tipo = 'info';
//-------------
$_SESSION['prima_hijos'] = prima_hijos();
$fecha = date('Y/m/d');
$desde = $fecha;
$hasta = $fecha;
$anno = date('Y');
$consulta_m = "SELECT * FROM a_nomina WHERE  eventual=0 AND activa = 'SI';"; //`codigo` = '001' and 
$tabla_m = $_SESSION['conexionsql']->query($consulta_m);
while ($registro_m = $tabla_m->fetch_object()) {
	$tipo_pago = '013';
	//-----------
	if ($_POST['txt_dias'] == 1) {
		$concepto = "PAGO DE AGUINALDOS (" . $_POST['txt_dias'] . " MES)";
	} else {
		$concepto = "PAGO DE AGUINALDOS (" . $_POST['txt_dias'] . " MESES)";
	}
	//-----------
	$codigo_nomina = $registro_m->codigo;
	$nomina = $registro_m->nomina;
	$partida_aguinaldos = $registro_m->utilidades;
	//------------- SI LA NOMINA YA FUE SOLICITADA
	$consultx = "SELECT id FROM nomina WHERE 1=2 AND estatus>0 AND nomina='$nomina' AND tipo_pago='013';";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows > 0) {
		$msg_fideicomiso = "no";
	} else {
		//-------------
		$consultx = "DELETE FROM nomina_solicitudes WHERE estatus=0 AND nomina='$nomina' AND tipo_pago='013';";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$consultx = "DELETE FROM nomina WHERE estatus=0 AND nomina='$nomina' AND tipo_pago='013';";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$consultx = "DELETE FROM nomina_descuentos WHERE id_nomina NOT IN (SELECT id FROM nomina);";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$consultx = "DELETE FROM nomina_asignaciones WHERE id_nomina NOT IN (SELECT id FROM nomina);";
		$tablx = $_SESSION['conexionsql']->query($consultx);

		//-------------	
		$consultx = "SELECT * FROM rac WHERE nomina='$nomina' AND temporal=0 AND suspendido=0 ORDER BY nomina, ubicacion;";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		if ($tablx->num_rows > 0) {
			//------------- GUARDAR SOLICITUD
			$num_nomina = num_nomina2($nomina, $anno);
			//-------------
			$consultx1 = "INSERT INTO nomina_solicitudes(patria, tipo_pago, numero, nomina, anno, fecha, descripcion, desde, hasta, estatus, lote, usuario) VALUES (1, '013', $num_nomina, '$nomina', '$anno', '$fecha', '$concepto', '$desde', '$hasta', 0, 1, '" . $_SESSION['CEDULA_USUARIO'] . "');";
			$tablx1 = $_SESSION['conexionsql']->query($consultx1);
			//-------------	
			$consultax2 = "SELECT LAST_INSERT_ID() as id;";
			$tablax2 = $_SESSION['conexionsql']->query($consultax2);
			$registrox2 = $tablax2->fetch_object();
			$id_solicitud = $registrox2->id;
			//-------------	FIN SOLICITUD
		}

		while ($registro = $tablx->fetch_object()) {
			$dias_aguinaldo = dias_aguinaldos($registro->fecha_ingreso); //echo $dias_aguinaldo;
			$dias_aguinaldo = $dias_aguinaldo;
			//----------
			$cedula = $registro->cedula;
			$hijos = $registro->hijos;
			$profesion = $registro->profesion;
			$anno_ing = anno($registro->fecha_ingreso);
			$mes_ing = mes($registro->fecha_ingreso);
			$dia_ing = dia($registro->fecha_ingreso);
			$annos = annos_exacto($anno_ing, $mes_ing, $dia_ing, anno($fecha), 12, 31);
			$anos_servicio = intval($annos) + intval($registro->anos_servicio);
			//--------------
			$cargo = $registro->cargo;
			$ubicacion = $registro->ubicacion;
			$categoria = $registro->categoria;
			//-------------- SUELDO INTEGRAL MENSUAL
			$prof = ($registro->sueldo * $_SESSION['prima_prof'][$profesion]) / 100;
			$antiguedad = ($_SESSION['prima_anno'][$anos_servicio] * $registro->sueldo) / 100;
			$hijos = $hijos * $_SESSION['prima_hijos'];
			$integral = ($registro->sueldo + ($prof) + ($antiguedad) + ($hijos));
			//-------------- SEMANA ADICIONAL
			$semana_adicional = (($integral / 30 * 7) / 360) * $dias_aguinaldo;
			//-------------- VACACIONES
			$dias = dias_vacaciones($anos_servicio);
			$vacaciones = $integral / 30 * $dias; //echo $vacaciones;
			$vacaciones = $vacaciones / 360 * $dias_aguinaldo;
			//----------
			$sueldo_mensual = $registro->sueldo;
			$sueldo = $integral;
			//------------ 
			$alicuota_utilidades = 0;
			if ($codigo_nomina == '001' or $codigo_nomina == '002' or $codigo_nomina == '003' or $codigo_nomina == '004') {
				$alicuota_utilidades = ((($integral / 30) * 120) / 360);
				$aguinaldos = ($alicuota_utilidades + $integral * 4 + $semana_adicional + $vacaciones);
				$aguinaldos = ($aguinaldos / 4) * abs(trim($_POST['txt_dias']));
				// $aguinaldos =( (($integral + $integral * 4 + $semana_adicional + $vacaciones)/4) * abs(trim($_POST['txt_dias'])));
			}
			if ($codigo_nomina == '005' or $codigo_nomina == '006') {
				$aguinaldos = $sueldo_mensual * abs(trim($_POST['txt_dias']));
			}
			//-------------
			$consultax = "INSERT INTO nomina (prof, antiguedad, hijos, vacaciones, dias, id_solicitud, dias_trabajados, sueldo_mensual, num_nomina, sueldo, asignaciones, total, tipo_pago, cargo, categoria, ubicacion, nomina, partida, cedula, anno, fecha, descripcion, desde, hasta, descuentos, estatus, usuario, alicuota_utilidades) VALUES ('$prof', '$antiguedad', '$hijos', '$vacaciones', '$semana_adicional', '$id_solicitud', " . abs(trim($_POST['txt_dias'])) . ", '$sueldo_mensual', '$num_nomina', '$sueldo', '$aguinaldos', '$aguinaldos', '$tipo_pago', '$cargo', '$categoria', '$ubicacion', '$nomina', '$partida_aguinaldos', '" . $cedula . "', " . anno($fecha) . ", '$fecha', '$concepto', '$fecha', '$fecha', 0, 0, '" . $_SESSION['CEDULA_USUARIO'] . "', '$alicuota_utilidades')";
			//				echo $consultax;
			$tablax = $_SESSION['conexionsql']->query($consultax);
			//-------------	id de la nomina nueva
			$consultax = "SELECT LAST_INSERT_ID() as id;";
			$tablax = $_SESSION['conexionsql']->query($consultax);
			$registrox = $tablax->fetch_object();
			$id_nomina = $registrox->id;
			//------------ ASIGNACION
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida_aguinaldos', '$cedula', '13', '$aguinaldos');";
			//				echo $consultax;
			$tablax = $_SESSION['conexionsql']->query($consultax);
		}
	}
}

//-------------	
$consultax = "DELETE FROM nomina_asignaciones WHERE asignaciones = 0 or TRIM(partida)='';";
//echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$consultax = "CALL actualizar_quincenas();"; //echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$mensaje = "Aguinaldos Generados Exitosamente!";

//-------------
$info = array("tipo" => $tipo, "msg" => $mensaje);
echo json_encode($info);
?>