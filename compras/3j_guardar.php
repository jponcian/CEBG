<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id_solicitud = ($_POST['oid']);
$fecha_presupuesto = voltea_fecha($_POST['txt_fechaP']); 
$fecha_solicitud = voltea_fecha($_POST['txt_fechaS']); 
$fecha_recibido = voltea_fecha($_POST['txt_fechaR']); 
$fecha_ofertas = voltea_fecha($_POST['txt_fechaO']); 
$fecha_examen = voltea_fecha($_POST['txt_fechaE']); 
$fecha_adjudicacion = voltea_fecha($_POST['txt_fechaA']); 
$fecha_notificacion = voltea_fecha($_POST['txt_fechaN']); 
$fecha_recepcion = voltea_fecha($_POST['txt_fechaRE']); 
$fecha_memo = voltea_fecha($_POST['txt_fechaM']);
$fecha_compra = voltea_fecha($_POST['txt_fecha']); 
$numero = ($_POST['txt_numero']); 
//-------------
$consultax = "SELECT id, id_presupuesto FROM orden WHERE id_solicitud='$id_solicitud' ORDER BY id DESC;"; 
$tablax = $_SESSION['conexionsql']->query($consultax);
while ($registro = $tablax->fetch_object())
	{
	$consulta_x = "UPDATE orden SET categoria = '".strtoupper($_POST['txt_categoria'.$registro->id])."', partida = '".strtoupper($_POST['txt_partida'.$registro->id])."', exento = '".strtoupper($_POST['txt_exento'.$registro->id])."' WHERE id = ".$registro->id.";"; 
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	//-------
	$id_presupuesto = $registro->id_presupuesto;
	}
//---------
$consulta_x = "UPDATE orden SET numero = '$numero', fecha = '$fecha_compra', concepto = '".strtoupper($_POST['txt_concepto'])."' WHERE id_solicitud = ".$id_solicitud.";"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//---------
$consulta_x = "UPDATE orden_solicitudes SET numero = '$numero', fecha_sol = '$fecha_compra', fecha = '$fecha_compra', descripcion = '".strtoupper($_POST['txt_concepto'])."' WHERE id = ".$id_solicitud.";"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//---------
$consulta_p = "UPDATE presupuesto SET fecha_orden = '$fecha_compra', fecha = '$fecha_presupuesto', fecha_presupuesto = '$fecha_presupuesto', fecha_solicitud = '$fecha_solicitud', fecha_recibido = '$fecha_recibido', fecha_ofertas = '$fecha_ofertas', fecha_examen = '$fecha_examen', fecha_adjudicacion = '$fecha_adjudicacion', fecha_notificacion = '$fecha_notificacion', fecha_recepcion = '$fecha_recepcion', fecha_memo = '$fecha_memo' WHERE id_solicitud = $id_presupuesto"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_p);
//---------
$consulta_x = "UPDATE presupuesto_solicitudes SET fecha_compra = '$fecha_compra', fecha_sol = '$fecha_presupuesto', fecha = '$fecha_presupuesto' WHERE id = $id_presupuesto"; 
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//-------------	
$mensaje = "Orden Actualizada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consulta_p);
echo json_encode($info);
?>