<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$orden = $_GET['tipo'];
//-------------	
$consultx = "INSERT INTO orden_solicitudes(anno, id_contribuyente, estatus, tipo_orden, descripcion, numero, fecha, total, fecha_sol, usuario) VALUES ('" . date('Y') . "', 1000, 99, '$orden', 'A.P.A.R.T.A.D.A', '" . compromiso_sig($orden) . "', '" . date('Y/m/d') . "', 0, '" . date('Y/m/d') . "', '" . $_SESSION['CEDULA_USUARIO'] . "')";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$consultax = "SELECT LAST_INSERT_ID() as id;";
$tablax = $_SESSION['conexionsql']->query($consultax);
$registrox = $tablax->fetch_object();
$id = $registrox->id;
//-------------	
$mensaje = "Orden Generada Exitosamente!";

//-------------
$info = array("tipo" => $tipo, "msg" => $mensaje, "id" => encriptar($id));
echo json_encode($info);
