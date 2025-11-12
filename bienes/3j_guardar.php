<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
//-------------
$consultx = "SELECT bn_reasignaciones_detalle.motivo, bn_reasignaciones_detalle.id_origen, bn_reasignaciones_detalle.id_destino FROM bn_reasignaciones_detalle WHERE bn_reasignaciones_detalle.id_bien = '$id' AND estatus = 0 GROUP BY bn_reasignaciones_detalle.id_origen, bn_reasignaciones_detalle.id_destino LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx); 
if ($tablx->num_rows>0)
	{
	$registrox = $tablx->fetch_object();
	$id_origen = $registrox->id_origen;
	$id_destino = $registrox->id_destino;
	$motivo = $registrox->motivo;
	//-------------	
	$consultx = "INSERT INTO bn_reasignaciones(motivo, memo, anno, numero, fecha, division_actual, division_destino, usuario) VALUES ('".($motivo)."', '".memo_reasig($id_origen)."', '".date('Y')."', '".num_reasig()."', '".date('Y-m-d')."', '$id_origen', '$id_destino', '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
	//-------------	
	$consultx = "UPDATE bn_reasignaciones_detalle SET id_reasignacion = $id, estatus = 10, fecha = '".date('Y-m-d')."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_reasignacion = 0 AND estatus = 0 AND id_origen = $id_origen AND id_destino = $id_destino ;";
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//-------------	
	$consultx = "UPDATE bn_bienes, bn_reasignaciones_detalle SET bn_bienes.por_reasignar = 0, bn_bienes.id_dependencia = bn_reasignaciones_detalle.id_destino WHERE	bn_bienes.id_bien = bn_reasignaciones_detalle.id_bien AND bn_reasignaciones_detalle.id_reasignacion = $id ;";
	$tablx = $_SESSION['conexionsql']->query($consultx);//echo $consultx ;
	//-------------	
	$consultx = "UPDATE a_direcciones, rac, bn_dependencias, bn_reasignaciones SET bn_reasignaciones.jefe_actual =  CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2),  bn_reasignaciones.direccion_actual = a_direcciones.direccion , bn_reasignaciones.cedula_actual = a_direcciones.cedula , bn_reasignaciones.cargo_actual = a_direcciones.cargo  , bn_reasignaciones.providencia_actual = a_direcciones.providencia , bn_reasignaciones.fecha_prov_actual = a_direcciones.fecha_prov , bn_reasignaciones.fecha_not_actual = a_direcciones.fecha_not , bn_reasignaciones.gaceta_actual = a_direcciones.gaceta , bn_reasignaciones.fecha_gaceta_actual = a_direcciones.fecha_gaceta WHERE a_direcciones.id = bn_dependencias.id_direccion AND bn_reasignaciones.division_actual = bn_dependencias.id AND a_direcciones.cedula = rac.cedula AND bn_reasignaciones.id = $id ;";
	$tablx = $_SESSION['conexionsql']->query($consultx);//echo $consultx ;
	//-------------	
	$consultx = "UPDATE a_direcciones, rac, bn_dependencias, bn_reasignaciones SET bn_reasignaciones.jefe_destino =  CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2),  bn_reasignaciones.direccion_destino = a_direcciones.direccion , bn_reasignaciones.cedula_destino = a_direcciones.cedula , bn_reasignaciones.cargo_destino = a_direcciones.cargo  , bn_reasignaciones.providencia_destino = a_direcciones.providencia , bn_reasignaciones.fecha_prov_destino = a_direcciones.fecha_prov , bn_reasignaciones.fecha_not_destino = a_direcciones.fecha_not , bn_reasignaciones.gaceta_destino = a_direcciones.gaceta , bn_reasignaciones.fecha_gaceta_destino = a_direcciones.fecha_gaceta WHERE a_direcciones.id = bn_dependencias.id_direccion AND bn_reasignaciones.division_destino = bn_dependencias.id AND a_direcciones.cedula = rac.cedula AND bn_reasignaciones.id = $id ;";
	$tablx = $_SESSION['conexionsql']->query($consultx);//echo $consultx ;
	//-------------	
	$mensaje = "Movimiento Generado Exitosamente!";
	}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>