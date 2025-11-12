<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id_cont = decriptar($_POST['id']);
//-------------
$consultx = "SELECT id_contribuyente, fecha, concepto, sum(total) as tot, anno, factura, fecha_factura, control FROM orden WHERE id_contribuyente=".$id_cont." AND tipo_orden='M' AND estatus=0 GROUP BY numero LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------	
$anno = $registro->anno;
$fecha = $registro->fecha;
$tot = $registro->tot;
$concepto = $registro->concepto;
$id_contribuyente = $registro->id_contribuyente;
$numero = num_orden_compra($anno,'M');
$factura = $registro->factura;
$control = $registro->control;
$fecha_factura = $registro->fecha_factura;
//-------------	
$consultx = "INSERT INTO orden_solicitudes(control, fecha_factura, factura, id_orden_pago, id_contribuyente, tipo_orden, numero, fecha_sol, anno, fecha, descripcion, asignaciones, total, estatus, usuario) VALUES ('$control', '$fecha_factura', '$factura', '0', '$id_contribuyente', 'M', $numero, '".date('Y/m/d')."', '$anno', '$fecha', '$concepto', '$tot', '$tot', 5, '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
//-------------	
$consultx = "UPDATE orden SET estatus = 5, numero = $numero, id_solicitud = $id, usuario_solicitud = '".$_SESSION['CEDULA_USUARIO']."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_contribuyente=".$id_cont." AND tipo_orden='M' AND estatus=0;";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
//CONSULTA TEMPORAL LESBIMARY
//$consultx = "UPDATE orden, orden_solicitudes, ordenes_pago SET ordenes_pago.numero=orden.control, ordenes_pago.fecha=orden.fecha_factura WHERE ordenes_pago.id=$id AND orden.control<>'' AND NOT orden.control IS NULL AND ordenes_pago.id=orden_solicitudes.id_orden_pago and orden_solicitudes.id=orden.id_solicitud;";
//$tablx = $_SESSION['conexionsql']->query($consultx);
$mensaje = "Solicitud de Pago Generada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>