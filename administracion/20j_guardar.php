<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$fecha = ($_POST['txt_fecha']);
$numero = ($_POST['txt_numero']);
$estatus = ($_POST['txt_estatus']);
$tipo_orden = ($_POST['txt_tipo']);
$id_contribuyente = ($_POST['txt_id_contribuyente']);
$id_solicitud = ($_POST['txt_id_sol']);
//-------------
$consultax = "SELECT id FROM orden WHERE id_solicitud = $id_solicitud ORDER BY id DESC;"; 
$tablax = $_SESSION['conexionsql']->query($consultax);
while ($registro = $tablax->fetch_object())
	{
	$consulta_x = "UPDATE orden SET numero = '$numero', anno = '".anno(voltea_fecha($_POST['txt_fecha']))."', fecha = '".voltea_fecha($_POST['txt_fecha'])."', concepto = '".strtoupper($_POST['txt_concepto'])."', categoria = '".strtoupper($_POST['txt_categoria'.$registro->id])."', partida = '".strtoupper($_POST['txt_partida'.$registro->id])."', exento = '".strtoupper($_POST['txt_exento'.$registro->id])."' WHERE id = ".$registro->id.";"; 
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
	}
//---------
if ($estatus>0)
	{
	$consulta_x = "UPDATE orden_solicitudes SET numero = '$numero', descripcion = '".strtoupper($_POST['txt_concepto'])."', anno = '".anno(voltea_fecha($_POST['txt_fecha']))."', fecha_sol = '".voltea_fecha($_POST['txt_fecha'])."', fecha = '".voltea_fecha($_POST['txt_fecha'])."' WHERE id = ".$id_solicitud.";"; 
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	}
//-------------	
$mensaje = "Orden Actualizada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consulta_x);
echo json_encode($info);
?>