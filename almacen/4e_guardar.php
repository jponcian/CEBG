<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
//$_POST["txt_valor"] = str_replace('.','',$_POST['txt_valor']); 
//$_POST["txt_valor"] = str_replace(',','.',$_POST['txt_valor']); 

if ($_GET['id']==0)
	{
	$consultx = "SELECT descripcion_bien FROM bn_materiales WHERE descripcion_bien = '".trim($_POST['txt_bien'])."' LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = "Item ya Registrado!";
		}
	else
		{
		//----------------
		$consultx = "INSERT INTO bn_materiales ( unidad, descripcion_bien, inventario, valor, id_categoria, usuario ) VALUES ( '".$_POST['txt_unidad']."', '".strtoupper(trim($_POST['txt_bien']))."', '0".$_POST['txt_existencia']."', '0', '1', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//------------
		$id = 0;
		//-------------	
		$mensaje = "Item Registrado Exitosamente!";
		}
	}
if ($_GET['id']>0)
	{
	//----------------
	$consultx = "UPDATE bn_materiales SET inventario='".($_POST['txt_existencia'])."', bien='".($_POST['txt_material'])."', numero_bien='".($_POST['txt_numero'])."', descripcion_bien='".strtoupper($_POST['txt_bien'])."', conservacion='".$_POST['txt_conservacion']."', valor='".$_POST["txt_valor"]."',  id_categoria='1', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id_bien=".$_GET['id'].";";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//------------
	$id = 0;
	//-------------	
	$mensaje = "Bien Nacional Actualizado Exitosamente!";
	}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx, "id"=>$id);

echo json_encode($info);
?>