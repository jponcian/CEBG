<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
//-------------
$consultx = "SELECT a_areas.id_direccion as origen, a_areas2.id_direccion as destino, bn_reasignaciones_detalle.id_area_origen, bn_reasignaciones_detalle.id_area_destino FROM a_areas, bn_reasignaciones_detalle,	a_areas AS a_areas2 WHERE bn_reasignaciones_detalle.id_bien = '$id' AND a_areas.id = bn_reasignaciones_detalle.id_area_destino AND a_areas2.id = bn_reasignaciones_detalle.id_area_origen AND estatus = 0 GROUP BY bn_reasignaciones_detalle.id_area_origen, bn_reasignaciones_detalle.id_area_destino LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$registrox = $tablx->fetch_object();
	$origen = $registrox->origen;
	$destino = $registrox->destino;
	$id_area_origen = $registrox->id_area_origen;
	$id_area_destino = $registrox->id_area_destino;
	//-------------	
	$consultx = "INSERT INTO bn_reasignaciones(anno, numero, fecha, division_actual, division_destino, usuario) VALUES ('".date(Y)."', '".num_mov_interno()."', '".date('Y-m-d')."', '$origen', '$destino', '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
	//-------------	
	$consultx = "UPDATE bn_reasignaciones_detalle SET id_reasignacion = $id, estatus = 10, fecha = '".date('Y-m-d')."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_reasignacion = 0 AND estatus = 0 AND id_area_origen = $id_area_origen AND id_area_destino = $id_area_destino ;";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//-------------	
	$consultx = "UPDATE bn_bienes, bn_reasignaciones_detalle SET bn_bienes.por_reasignar = 0, bn_bienes.id_area = bn_reasignaciones_detalle.id_area_destino WHERE	bn_bienes.id_bien = bn_reasignaciones_detalle.id_bien AND bn_reasignaciones_detalle.id_reasignacion = $id ;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Movimiento Generado Exitosamente!";
	}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>