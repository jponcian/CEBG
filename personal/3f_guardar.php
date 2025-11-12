<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = decriptar($_POST['oid']);
$estatus = $_POST['txt_estatus'];

//------------- RECUPERAR ESTATUS DEL PROCESO
$consultx = "SELECT estatus FROM evaluaciones WHERE estatus <= '10' ORDER BY id DESC LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);	
$registro = $tablx->fetch_object();
$actual = $registro->estatus;
//-------------
$consultx = "SELECT cedula FROM rac WHERE odis<$actual AND evaluar_odis=1 AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO' ;";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);	
if ($tablx->num_rows>0)
	{	$mensaje = "<strong>Existen Funcionarios SIN COMPLETAR el proceso!</strong>";	$tipo = 'alert';	}
else
	{	
	//-------------
	$consultx = "UPDATE evaluaciones SET estatus = $estatus WHERE id = '$id';"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$consultx = "UPDATE rac SET odis=$estatus WHERE evaluar_odis=1 AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO';"; //odis<$estatus AND 
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------

	$mensaje = "Proceso de Evaluación Actualizado Exitosamente!";
	//-------------
	}

$info = array ("tipo"=>$tipo, "msg"=>$mensaje);

echo json_encode($info);
?>