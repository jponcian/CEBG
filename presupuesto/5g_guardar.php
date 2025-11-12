<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
if (trim($_POST['txt_partida'])=='' or trim($_POST['txt_descripcion'])=='' or trim($_POST['txt_original'])=='')
	{
	$mensaje = "No ha rellenado todos los campos!"; $tipo = 'alerta';
	}
//-------------
$id = $_POST['oidP']; 
$catergoria = $_GET['id']; 
$anno = $_GET['anno']; 
//-------------
$_POST["txt_original"] = str_replace('.','',$_POST['txt_original']); 
$_POST["txt_original"] = str_replace(',','.',$_POST['txt_original']); 
//-------------	
if ($tipo=='info')
	{
	if ($id>0)
		{
		$tipo = 'alerta';
		$mensaje = 'La Partida '.$_POST['txt_partida'].' fue Actualizada...';
		//----------------
		$consultx = "UPDATE a_presupuesto_".date('Y')." SET descripcion = '".$_POST['txt_descripcion']."', original = '".$_POST['txt_original']."' WHERE id='$id';";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//----------------
		$consultx = "UPDATE a_presupuesto_original_".date('Y')." SET descripcion = '".$_POST['txt_descripcion']."', original = '".$_POST['txt_original']."' WHERE id='$id';";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//----------------
		$consultx = "UPDATE a_partidas SET descripcion = '".$_POST['txt_descripcion']."' WHERE codigo='".$_POST['txt_partida']."';";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		}
	else	
		{
		$consultx = "INSERT INTO a_presupuesto_".date('Y')."(codigo, categoria, descripcion, original) VALUES ('".$_POST['txt_partida']."', '".$_GET['id']."', '".$_POST['txt_descripcion']."', '".$_POST['txt_original']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$consultx = "INSERT INTO a_presupuesto_original_".date('Y')."(codigo, categoria, descripcion, original) VALUES ('".$_POST['txt_partida']."', '".$_GET['id']."', '".$_POST['txt_descripcion']."', '".$_POST['txt_original']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$consultx = "INSERT INTO a_partidas(codigo, descripcion) (SELECT codigo, descripcion from a_presupuesto_".date('Y')." WHERE categoria is not null and codigo not in (select codigo from a_partidas) GROUP BY codigo);";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$mensaje = "Partida Registrada Exitosamente!";
		}
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>