<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//------------- INFORMACION BASICA
$consultx = "SELECT anno, fecha, descripcion, desde, hasta, tipo_pago, id_cont, nomina, patria FROM nomina WHERE id = ".decriptar($_POST['id'])." LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------	
$tipo_pago = $registro->tipo_pago;
$anno = $registro->anno;
$fecha = $registro->fecha;
$descripcion = $registro->descripcion;
$desde = $registro->desde;
$hasta = $registro->hasta;
$id_cont = $registro->id_cont;
$nomina = $registro->nomina;
$patria = $registro->patria;
//-------------
$consulta_nom = "SELECT nomina FROM a_nomina WHERE codigo <> 'EGRESADOS' AND nomina='$nomina' order by codigo;"; //echo $consultam;
$tabla_nom = $_SESSION['conexionsql']->query($consulta_nom);
while ($registro_nom = $tabla_nom->fetch_object())
	{
	//-------------
	$nomina = $registro_nom->nomina;
	$num_sol_pago = num_sol_pago($anno);
	$numero = num_eventual($tipo_pago, $nomina, $anno);
	//-------------
	$consultx = "SELECT id_cont, anno, fecha, descripcion, desde, hasta, SUM(asignaciones) as asi, SUM(descuentos) as des, SUM(total) as tot FROM nomina WHERE nomina='$nomina' AND id_cont='$id_cont' AND tipo_pago='$tipo_pago' AND fecha='$fecha' AND desde='$desde' AND hasta='$hasta' AND estatus = 0 LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows>0)
		{
		$registro = $tablx->fetch_object();
		//-------------	
		$asi = $registro->asi;
		$des = $registro->des;
		$tot = $registro->tot;
		//-------------	
		$consultx = "INSERT INTO nomina_solicitudes(patria, id_cont, tipo_pago, num_sol_pago, numero, fecha_sol, nomina, anno, fecha, descripcion, desde, hasta, asignaciones, descuentos, total, estatus, usuario) VALUES ('$patria', '$id_cont', '$tipo_pago', $num_sol_pago, $numero, CURDATE(), '$nomina', '$anno', '$fecha', '$descripcion', '$desde', '$hasta', '$asi', '$des', '$tot', 5, '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
			$consultax = "SELECT LAST_INSERT_ID() as id;";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			$registrox = $tablax->fetch_object();
			$id = $registrox->id;
		//-------------	
		$num_nomina = nomina_sig();
		$consultx = "UPDATE nomina SET descripcion='$descripcion', desde='$desde', hasta='$hasta', num_nomina = $num_nomina, estatus = 5, id_solicitud = $id, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE nomina='$nomina' AND id_cont='$id_cont' AND tipo_pago='$tipo_pago' AND fecha='$fecha' AND desde='$desde' AND hasta='$hasta' AND estatus = 0;";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		}
	}
//-------------	
$mensaje = "Solicitud de Pago Generada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>