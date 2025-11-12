<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$total=0;
$consultx = "SELECT id, total FROM nomina_solicitudes WHERE estatus=5;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
{
if ($_POST['osel'.$registro->id]==$registro->id)
	{
	$total = $total + $registro->total;
	}
}
//-------------	
if ($total>0)
{
	//-------------	
	$consultx = "INSERT INTO ordenes_pago(tipo_solicitud, numero, fecha, total, estatus, usuario) VALUES ('NOMINA', ".orden_sig().", '".date('Y/m/d')."', 0, 0, '".$_SESSION['CEDULA_USUARIO']."')";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
	//-------------	
	$consultax = "SELECT id, total, descripcion, nomina FROM nomina_solicitudes WHERE estatus=5 ORDER BY tipo_pago, nomina, desde;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	while ($registro = $tablax->fetch_object())
	{
	if ($_POST['osel'.$registro->id]==$registro->id)
		{
		//-------------	
		$consultx = "UPDATE nomina_solicitudes SET estatus = 10, id_orden_pago=$id WHERE id = ".$registro->id.";"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------
		$consultx = "UPDATE ordenes_pago SET descripcion=CONCAT(ordenes_pago.descripcion,' ','".$registro->descripcion.' '.$registro->nomina."') WHERE id=$id;";
		$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
		//-------------	
		}
	}
	//-------------	
	$consultx = "UPDATE ordenes_pago SET total=(SELECT sum(total) from nomina_solicitudes WHERE id_orden_pago=$id), asignaciones=(SELECT sum(asignaciones) from nomina_solicitudes WHERE id_orden_pago=$id), descuentos=(SELECT sum(descuentos) from nomina_solicitudes WHERE id_orden_pago=$id) WHERE id=$id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje = "Orden de Pago Generada Exitosamente!";
}
else
{
//-------------	
$mensaje = "No ha seleccionado ninguna nomina!"; $tipo = 'alerta';
}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>