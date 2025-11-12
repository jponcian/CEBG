<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

//---------------
$consultx = "SELECT id FROM orden_solicitudes WHERE id_orden_pago = '".$_GET['id']."'"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$id_solicitud = $registro->id;

$consultx = "SELECT orden.id, orden.factura FROM orden, orden_solicitudes WHERE orden_solicitudes.id = orden.id_solicitud AND orden_solicitudes.id_orden_pago='".$_GET['id']."'"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	if ($_POST['txt_factura'.$registro->id]<>'')
	{$consultau = "UPDATE orden SET factura = '".$_POST['txt_factura'.$registro->id]."', control = '".$_POST['txt_control'.$registro->id]."', fecha_factura = '".voltea_fecha($_POST['txt_fecha'.$registro->id])."' WHERE id_solicitud = '$id_solicitud' AND factura='".$registro->factura."';";
	$tablau = $_SESSION['conexionsql']->query($consultau);	}
	
	}
//$consultau = "UPDATE orden , orden_solicitudes SET orden.factura= orden_solicitudes.factura, orden.control= orden_solicitudes.control, orden.fecha_factura= orden_solicitudes.fecha_factura WHERE orden_solicitudes.id = orden.id_solicitud;";
//$tablau = $_SESSION['conexionsql']->query($consultau);	
//-------------	
$mensaje = "Informacion Guardada Exitosamente!";

//$consultx = "SELECT id_contribuyente FROM orden_solicitudes WHERE id_orden_pago = '".$_GET['id']."'"; 
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
//$id_cont = $registro->id_contribuyente;

//$consultx = "SELECT * FROM ( 
//SELECT COUNT(factura) as cuenta, factura FROM orden_solicitudes WHERE id_contribuyente = $id_cont GROUP BY factura, tipo_orden) as Valida WHERE cuenta>1;"; 
//$tablx1 = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
//if ($tablx1->num_rows>0)
//	{
//	$mensaje = '';
//	while ($registro1 = $tablx1->fetch_object())
//		{
//		$factura = $registro1->factura;
//		//-------------	
//		$consultx = "SELECT numero FROM orden_solicitudes WHERE id_contribuyente = '$id_cont' AND factura = '$factura'"; 
//		$tablx = $_SESSION['conexionsql']->query($consultx);
//		while ($registro = $tablx->fetch_object())
//			{
//			$numero = $registro->numero;
//			$mensaje = $mensaje."La factura $factura en la Compra o Servicio n° $numero está duplicada. ";
//			}
//		//-------------	
//		}
//	$tipo = 'alerta';
//	}

$info = array ("tipo"=>$tipo, "msg"=>$mensaje);

echo json_encode($info);
?>