<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
//------------- INFORMACION BASICA
$id = decriptar($_POST['id']);
$cedula = decriptar($_POST['cedula']);
$numero = sol_viatico();
//$memo = memo_viatico($oficina);
//-------------
$consultx1 = "SELECT id, rif FROM contribuyente WHERE rif like '%$cedula%' limit 1;"; 
$tablx = $_SESSION['conexionsql']->query($consultx1);
if ($tablx->num_rows>0)	
	{
	$registro_x = $tablx->fetch_object();
	$id_contribuyente = $registro_x->id;
	$rif = $registro_x->rif;
	//-------------
	$consultx = "UPDATE viaticos_solicitudes SET numero='$numero', fecha = '".date('Y-m-d')."', estatus = 7, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id='$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------
	$consultAx = "UPDATE viaticos_solicitudes, vista_viat_sum_detalle SET viaticos_solicitudes.total=vista_viat_sum_detalle.total WHERE viaticos_solicitudes.id=vista_viat_sum_detalle.id_solicitud AND viaticos_solicitudes.id = $id;";
	$tablx = $_SESSION['conexionsql']->query($consultAx);	
	//------------- MONTO DEL VIATICO
	$consultx = "SELECT viaticos_solicitudes.total, viaticos_solicitudes.concepto FROM viaticos_solicitudes WHERE id='$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro = $tablx->fetch_object();
	$total = $registro->total;
	$concepto = $registro->concepto;
	$numero = num_orden_compra(date('Y'),'V');
	//-------------	
	$consultx = "INSERT INTO orden_solicitudes(control, fecha_factura, factura, id_orden_pago, id_contribuyente, tipo_orden, numero, fecha_sol, anno, fecha, descripcion, asignaciones, total, estatus, usuario) VALUES ('999', '".date('Y-m-d')."', '999', '0', '$id_contribuyente', 'V', $numero, '".date('Y/m/d')."', '".date('Y')."', '".date('Y/m/d')."', '$concepto', '$total', '$total', 5, '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object();
		$id_solicitud = $registrox->id;
	//-------------
	$consultx = "INSERT INTO orden(id_solicitud, exento, porcentaje_iva, control, factura, fecha_factura, tipo_orden, id_contribuyente, rif, fecha, anno, concepto, numero, categoria, partida, cantidad, descripcion, precio_uni, total, estatus, usuario) VALUES ('$id_solicitud', '0', '0', '999', '999', '".date('Y-m-d')."', 'V', '$id_contribuyente', '$rif', '".date('Y-m-d')."', '".date('Y')."', '$concepto', '$numero', '0101020051', '403090100', '1', 'VIÁTICOS Y PASAJES DENTRO DEL PAÍS', '$total', '$total', '0', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------
	$mensaje = "Solicitud Generada Exitosamente!";
	}
else
	{
	$tipo = 'alerta';
	$mensaje = "Por favor registre al(los) funcionario(s) en el modulo de contribuyente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx, "id"=>encriptar($id));
echo json_encode($info);
?>