<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$partida = $_POST['partida']; 
$categoria = $_POST['categoria']; 
//-------------	
$consulta_x = "SELECT id FROM nomina WHERE partida='$partida' AND categoria='$categoria' LIMIT 1;";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
if ($tabla_x->num_rows>0)
	{
	$tipo = 'alerta';
	$mensaje = 'La Partida ya ha sido utilizada en el pago de Nomina por RRHH, no se puede eliminar...';
	}
else
	{
	$consulta_x = "SELECT id FROM orden WHERE partida='$partida' AND categoria='$categoria' LIMIT 1;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	if ($tabla_x->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = 'La Partida ya ha sido utilizada en una Orden de Pago, no se puede eliminar...';
		}
	else
		{
		//$_SESSION['conexionsql']->query("DELETE FROM a_presupuesto_".date('Y')." WHERE codigo='$partida' AND categoria='$categoria' AND codigo NOT IN (SELECT partida FROM nomina WHERE partida='$partida' AND categoria='$categoria' GROUP BY partida) AND codigo NOT IN (SELECT partida FROM orden WHERE partida='$partida' AND categoria='$categoria' GROUP BY partida);");
		$_SESSION['conexionsql']->query("DELETE FROM a_presupuesto_".date('Y')." WHERE codigo='$partida' AND categoria='$categoria';");
		$_SESSION['conexionsql']->query("DELETE FROM a_presupuesto_original_".date('Y')." WHERE codigo='$partida' AND categoria='$categoria';");
		$mensaje = 'La Partida fue eliminada correctamente...';
		}
	}	
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>