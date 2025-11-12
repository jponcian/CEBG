<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
if (trim($_POST['txt_partida'])=='' or trim($_POST['txt_descripcion'])=='')
	{
	$mensaje = "No ha rellenado todos los campos!"; $tipo = 'alerta';
	}
//-------------
if ($tipo=='info')
	{
	$consulta_x = "SELECT id FROM a_presupuesto_".date('Y')." WHERE codigo='".$_POST['txt_partida']."' or categoria='".$_POST['txt_partida']."' LIMIT 1;";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	if ($tabla_x->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = 'La Actividad '.$_POST['txt_partida'].' ya existe...';
		}
	else	
		{
		//-------------	
		$consultx = "INSERT INTO a_presupuesto_".date('Y')." (codigo, descripcion) VALUES ('".$_POST['txt_partida']."', '".$_POST['txt_descripcion']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$consultx = "INSERT INTO a_presupuesto_original_".date('Y')." (codigo, descripcion) VALUES ('".$_POST['txt_partida']."', '".$_POST['txt_descripcion']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		//$consultx = "INSERT INTO a_areas(categoria, descripcion) (SELECT codigo, descripcion from a_presupuesto_".date('Y')." WHERE categoria is null and codigo not in (select categoria from a_areas) GROUP BY codigo);";
		//$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$consultx = "INSERT INTO a_categoria(codigo, descripcion) (SELECT codigo, descripcion from a_presupuesto_".date('Y')." WHERE categoria is null and codigo not in (select codigo from a_categoria) GROUP BY codigo);";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$mensaje = "Actividad Registrada Exitosamente!";
		}
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>