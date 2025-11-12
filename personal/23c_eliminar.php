<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id = $_POST['id']; 
//-------------	
//$consulta_x = "SELECT ubicacion FROM rac WHERE ubicacion='$ubicacion' GROUP BY ubicacion;";
//$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//if ($tabla_x->num_rows>0)
//	{
//	$tipo = 'alerta';
//	$mensaje = 'Existen Empleados con esa Dependencia, no se puede eliminar...';
//	}
//else
//	{
	//$_SESSION['conexionsql']->query("DELETE FROM a_presupuesto_".date('Y')." WHERE codigo='$categoria' AND codigo NOT IN (SELECT categoria FROM nomina WHERE categoria='$categoria' GROUP BY categoria) AND codigo NOT IN (SELECT categoria FROM orden WHERE categoria='$categoria' GROUP BY partida)");
	$_SESSION['conexionsql']->query("DELETE FROM a_cargo WHERE codigo='$id';");
	$mensaje = 'El Cargo fue Eliminado correctamente...';
//	}	
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>