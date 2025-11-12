<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = $_POST['id'];
$tipo2 = $_POST['tipo'];

//----------------
$consultx = "SELECT * FROM orden WHERE id_presupuesto = $id AND estatus=0;";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$registro = $tablx->fetch_object();
	$rif = ($registro->rif);
	$fecha = voltea_fecha($registro->fecha_factura);
	$fecha_factura = voltea_fecha($registro->fecha_factura);
	$factura = ($registro->factura);
	$concepto = ($registro->concepto);
	$control = ($registro->control);
	$id_contribuyente = ($registro->id_contribuyente);
	$numero = $registro->numero;		
	$id = ($registro->id_presupuesto);		
	$tipo = ($registro->tipo_orden);
	$tipo2 = ($registro->tipo_orden2);
	}
else
	{
	$consultx = "SELECT * FROM presupuesto_solicitudes WHERE id = $id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows>0)
		{
		$registro = $tablx->fetch_object();
		$fecha = voltea_fecha($registro->fecha_compra);
		$fecha_factura = voltea_fecha($registro->fecha_compra);
		$concepto = ($registro->descripcion);
		$rif = ($registro->rif);		
		$id_contribuyente = ($registro->id_contribuyente);
		$numero = $registro->numero;		
		$id = ($registro->id);		
		$tipo = ($registro->tipo_orden);
		//--------------
		$consultx = "INSERT INTO orden (medida, numero, estatus, tipo_orden, tipo_orden2, id_presupuesto, id_contribuyente, rif, fecha, anno, concepto, categoria, partida, porcentaje_iva, cantidad, descripcion, precio_uni, asignaciones, total, exento, usuario) (SELECT medida, presupuesto.numero, '0', presupuesto.tipo_orden, '$tipo2', '$id', presupuesto.id_contribuyente, presupuesto.rif, 	presupuesto.fecha, 	presupuesto.anno, presupuesto.concepto, presupuesto.categoria, presupuesto.partida, presupuesto.porcentaje_iva, presupuesto.cantidad, presupuesto.descripcion, presupuesto.precio_uni, presupuesto.asignaciones, presupuesto.total, presupuesto.exento, presupuesto.usuario FROM presupuesto WHERE id_solicitud=$id);";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//--------------
		}
	else
		{
		$concepto = 'DEBE GENERAR EL PRESUPUESTO PARA GENERAR LA ORDEN DE COMPRA';
		}
	}
//-------------	

$info = array ("tipo2"=>$tipo2, "tipo"=>$tipo, "id_contribuyente"=>$id_contribuyente, "numero"=>$numero, "id"=>$id, "tipo"=>$tipo, "rif"=>$rif, "control"=>$control, "fecha_factura"=>$fecha_factura, "factura"=>$factura, "fecha"=>$fecha, "concepto"=>$concepto, "consulta"=>$consultx);

echo json_encode($info);
?>