<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$total=0;
$concepto='';

$consultx = "SELECT id, sum(total) as total, descripcion, id_contribuyente, tipo_orden FROM orden_solicitudes WHERE estatus=5 GROUP BY id;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
{
if ($_GET['id']==0){	$numero=orden_sig(); 	$fecha = date('Y/m/d');		}
	else	
		{	
		$valor = explode("*",$_GET['id']);
		$numero = $valor[0];
		$fecha = $valor[1];
		$id = $valor[2];
		$consultb = "DELETE FROM ordenes_pago WHERE id=$id;";
		$_SESSION['conexionsql']->query($consultb);
		}

if ($_POST['osel'.$registro->id]==$registro->id)
	{
	$total = $total + $registro->total;
	$concepto = $concepto.' * '.$registro->descripcion;
	$id_cont = $registro->id_contribuyente;
	//------------
	if ($registro->tipo_orden=='M')
		{ 	$orden = 'MANUAL';	$estatus=7;  }
		elseif($registro->tipo_orden=='F') 
			{ 	$orden = 'FINANCIERA';  $estatus = 7;
				if ($_GET['id']==0)	{	$numero=orden_fin(); }	}
			else
				{ 	$orden = 'ORDEN'; 	$estatus = 0;	 }
	}
}
//-------------	
if ($total>0)
{
	//-------------	
	$consultx = "INSERT INTO ordenes_pago(id_contribuyente, tipo_solicitud, numero, fecha, descripcion, asignaciones, total, estatus, usuario) VALUES ('$id_cont', '$orden', '$numero', '$fecha', '$concepto', '$total', '$total', '$estatus', '".$_SESSION['CEDULA_USUARIO']."')";//echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
	//-------------	
	$consultax = "SELECT id FROM orden_solicitudes WHERE estatus=5 ORDER BY id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	while ($registro = $tablax->fetch_object())
	{
	if ($_POST['osel'.$registro->id]==$registro->id)
		{
		//-------------
		$consultx = "UPDATE orden SET estatus = 7, usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id_solicitud = ".$registro->id.";"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$consultx = "UPDATE orden_solicitudes SET estatus = 7, id_orden_pago=$id WHERE id = ".$registro->id.";"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		}
	}
	//-------------	
	//$consultx = "UPDATE ordenes_pago SET total=(SELECT sum(total) from orden_solicitudes WHERE id_orden_pago=$id), asignaciones=(SELECT sum(asignaciones) from orden_solicitudes WHERE id_orden_pago=$id), descuentos=(SELECT sum(descuentos) from orden_solicitudes WHERE id_orden_pago=$id) WHERE id=$id;";
	//$tablx = $_SESSION['conexionsql']->query($consultx);
	//CONSULTA TEMPORAL
	//$consultx = "UPDATE orden, orden_solicitudes, ordenes_pago SET ordenes_pago.numero=orden.control, ordenes_pago.fecha=orden.fecha_factura WHERE ordenes_pago.usuario='21280988' AND ordenes_pago.id=$id AND orden.control<>'' AND NOT orden.control IS NULL AND ordenes_pago.id=orden_solicitudes.id_orden_pago and orden_solicitudes.id=orden.id_solicitud;";//
	//$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje = "Orden de Pago Generada Exitosamente!";
}
else
{
//-------------	
$mensaje = "No ha seleccionado ninguna Orden!"; $tipo = 'alerta';
}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>orden_fin(), "orden"=>$orden, "id"=>encriptar($id));
echo json_encode($info);
?>