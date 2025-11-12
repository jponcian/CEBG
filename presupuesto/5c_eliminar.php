<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$categoria = $_POST['id']; 
//-------------	
$consulta_x = "SELECT categoria FROM nomina WHERE categoria='$categoria' GROUP BY categoria;";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
if ($tabla_x->num_rows>0)
	{
	$tipo = 'alerta';
	$mensaje = 'La Actividad ya ha sido utilizada en el pago de Nomina por RRHH, no se puede eliminar...';
	}
else
	{
	$consulta_x = "SELECT categoria FROM orden WHERE categoria='$categoria' GROUP BY categoria;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	if ($tabla_x->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = 'La Actividad ya ha sido utilizada en una Orden de Pago, no se puede eliminar...';
		}
	else
		{
		//$_SESSION['conexionsql']->query("DELETE FROM a_presupuesto_".date('Y')." WHERE codigo='$categoria' AND codigo NOT IN (SELECT categoria FROM nomina WHERE categoria='$categoria' GROUP BY categoria) AND codigo NOT IN (SELECT categoria FROM orden WHERE categoria='$categoria' GROUP BY partida)");
		$_SESSION['conexionsql']->query("DELETE FROM a_presupuesto_".date('Y')." WHERE codigo='$categoria' AND codigo;");
		$_SESSION['conexionsql']->query("DELETE FROM a_presupuesto_".date('Y')." WHERE categoria='$categoria';");
		$_SESSION['conexionsql']->query("DELETE FROM a_presupuesto_original_".date('Y')." WHERE codigo='$categoria' AND codigo;");
		$_SESSION['conexionsql']->query("DELETE FROM a_presupuesto_original_".date('Y')." WHERE categoria='$categoria';");
		$mensaje = 'La Categoria y todas sus Partidas fueron eliminadas correctamente...';
		}
	}	
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>