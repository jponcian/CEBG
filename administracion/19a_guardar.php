<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

//-------------	
$consultx = "INSERT INTO ordenes_pago(estatus, tipo_solicitud, descripcion, numero, fecha, total, num_comprobante, fecha_comprobante, usuario) VALUES (99, 'MANUAL', 'A.P.A.R.T.A.D.A', '".orden_sig()."', '".date('Y/m/d')."', 0, '".comprobante_sig('MANUAL', date('Y'))."', '".date('Y/m/d')."', '".$_SESSION['CEDULA_USUARIO']."')";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$consultax = "SELECT LAST_INSERT_ID() as id;";
$tablax = $_SESSION['conexionsql']->query($consultax);	
$registrox = $tablax->fetch_object();
$id = $registrox->id;
//-------------	
$mensaje = "Orden de Pago Generada Exitosamente!";

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>