<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id_cont = decriptar($_POST['id']);
//-------------
$consultx = "SELECT partida FROM orden WHERE id_contribuyente=".$id_cont." AND estatus=0  AND left(trim(partida),7)<>'4031801';";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$consultx = "SELECT tipo_orden, tipo_orden2, id_presupuesto, id_solicitud, id_contribuyente, fecha, concepto, sum(total) as tot, anno, factura, fecha_factura, control, numero FROM orden WHERE id_contribuyente=".$id_cont." AND estatus=0 GROUP BY id_contribuyente LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro = $tablx->fetch_object();
	//-------------	
	$anno = $registro->anno;
	$fecha = $registro->fecha;
	$tot = $registro->tot;
	$concepto = $registro->concepto;
	$id_contribuyente = $registro->id_contribuyente;
	$factura = $registro->factura;
	$fecha_factura = $registro->fecha_factura;
	$control = $registro->control;
	$tipo_orden = $registro->tipo_orden;
	$tipo_orden2 = $registro->tipo_orden2;
	$id_presupuesto = $registro->id_presupuesto;
	$numero = $registro->numero;
	//-------------	
	//if ($_GET['id']==0){	$numero=compromiso_sig(2); 	$fecha = date('Y/m/d');		}
//	else	
//		{	
//		$valor = explode("*",$_GET['id']);
//		$numero = $valor[0];
//		$fecha = $valor[1];
//		$id = $valor[2];
//		$consultb = "DELETE FROM orden_solicitudes WHERE id=$id;";
//		$_SESSION['conexionsql']->query($consultb);
//		}
	$consultx = "INSERT INTO orden_solicitudes(id_presupuesto, control, fecha_factura, factura, id_contribuyente, tipo_orden, tipo_orden2, numero, fecha_sol, anno, fecha, descripcion, asignaciones, total, estatus, usuario) VALUES ('$id_presupuesto', '$control', '$fecha_factura', '$factura', '$id_contribuyente', '$tipo_orden', '$tipo_orden2', $numero, '$fecha', '$anno', '$fecha', '$concepto', '$tot', '$tot', 5, '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
	//-------------	
	$consultx = "UPDATE orden SET estatus = 5, fecha = '$fecha', id_solicitud = $id, usuario_solicitud = '".$_SESSION['CEDULA_USUARIO']."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_contribuyente=".$id_cont." AND estatus=0;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultx = "UPDATE presupuesto_solicitudes SET estatus=5 WHERE id = $id_presupuesto;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultx = "UPDATE presupuesto SET estatus=5 WHERE id_solicitud = $id_presupuesto;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	

	$mensaje = "Solicitud de Pago Generada Exitosamente!";
	}
else
	{
	$mensaje = "No Existen Partidas para Generar la Orden!";
	$tipo = 'alerta';
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>