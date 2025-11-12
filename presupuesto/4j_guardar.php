<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id_cont = decriptar($_POST['id']);
//-------------
$consultx = "SELECT estatus FROM traslados WHERE estatus=0;";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$consultx = "SELECT sum(monto1) AS monto1, sum(monto2) AS monto2, traslados.anno, traslados.fecha, 	traslados.concepto FROM	traslados WHERE estatus=0 GROUP BY traslados.anno, traslados.numero, traslados.fecha, traslados.concepto LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro = $tablx->fetch_object();
	//-------------	
	$anno = $registro->anno;
	$fecha = $registro->fecha;
	$concepto = $registro->concepto;
	$monto1 = $registro->monto1;
	$monto2 = $registro->monto2;
	$numero = numero_traspaso($anno);
	//-------------	
	$consultx = "INSERT INTO traspaso (estatus, numero, anno, fecha, descripcion, incrementa, disminuye, usuario) VALUES (5, '$numero', '$anno', '$fecha', '$concepto', '$monto1', '$monto2', '".$_SESSION['CEDULA_USUARIO']."');"; 
	//echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object();
		$id = $registrox->id;
	//-------------	
	$consultx = "UPDATE traslados SET estatus = 5, numero = '$numero', fecha = '$fecha', id_traspaso = $id, usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE estatus=0;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
//	echo $consultx ;
	//-------------	
	$mensaje = "Traspaso Generado Exitosamente!";
	}
else
	{
	$mensaje = "No Existe Información para Generar el Traspaso!";
	$tipo = 'alerta';
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>