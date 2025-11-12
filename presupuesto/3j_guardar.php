<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$numero = $_POST['numero'];
$fecha = anno($_POST['fecha']);
//-----------
$consultx = "SELECT fecha, concepto, sum(total) as tot, anno FROM credito_adicional_detalle WHERE year(fecha)='$fecha' AND numero=$numero AND estatus=0 GROUP BY numero LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------	
$anno = $registro->anno;
$fecha = $registro->fecha;
$tot = $registro->tot;
$concepto = $registro->concepto;
$tipo_orden = $registro->tipo_orden;
//$numero = $registro->numero; //num_credito($anno,1);
//-------------	
$consultx = "INSERT INTO credito_adicional(tipo_orden, numero, fecha_sol, anno, fecha, descripcion, asignaciones, total, estatus, usuario) VALUES ('$tipo_orden', $numero, '$fecha', '$anno', '$fecha', '$concepto', '$tot', '$tot', 10, '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
//-------------	
$consultx = "UPDATE credito_adicional_detalle SET estatus = 10, numero = $numero, id_credito = $id, usuario_credito = '".$_SESSION['CEDULA_USUARIO']."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE year(fecha)='$fecha' AND numero=$numero AND estatus=0;";
$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
//-------------	
$mensaje = "Credito Adicional Guardado Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>