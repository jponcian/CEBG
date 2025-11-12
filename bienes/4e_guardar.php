<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$_POST["txt_valor"] = str_replace('.','',$_POST['txt_valor']); 
$_POST["txt_valor"] = str_replace(',','.',$_POST['txt_valor']); 

if ($_GET['id']==0)
	{
	$consultx = "SELECT numero_bien FROM bn_bienes WHERE numero_bien=0".$_POST['txt_numero']." LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = "Bien Nacional ya Registrado!";
		}
	else
		{
		//----------------
		$consultx = "INSERT INTO bn_bienes (grupo, subgrupo, seccion, subseccion, cuenta, fecha_adquisicion, marca, fabricante, modelo, serial, proveedor, orden_compra, factura, numero_bien, descripcion_bien, conservacion, valor, id_dependencia, id_categoria, usuario ) VALUES ('".($_POST['txt_grupo'])."', '".($_POST['txt_subgrupo'])."', '".($_POST['txt_seccion'])."', '".($_POST['txt_subseccion'])."', '".($_POST['txt_cuenta'])."', '".voltea_fecha($_POST['txt_fecha'])."', '".($_POST['txt_marca'])."', '".($_POST['txt_fabricante'])."', '".($_POST['txt_modelo'])."', '".($_POST['txt_serial'])."', '".($_POST['txt_rif'])."', '".($_POST['txt_oc'])."', '".($_POST['txt_factura'])."', '".($_POST['txt_numero'])."', '".strtoupper($_POST['txt_bien'])."', '".$_POST['txt_conservacion']."', '".$_POST["txt_valor"]."', '".$_POST['txt_area']."', '".$_POST['txt_categoria']."', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//------------
		$id = 0;
		//-------------	
		$mensaje = "Bien Nacional Registrado Exitosamente!";
		}
	}
if ($_GET['id']>0)
	{
	//----------------
	$consultx = "UPDATE bn_bienes SET grupo='".($_POST['txt_grupo'])."', subgrupo='".($_POST['txt_subgrupo'])."', seccion='".($_POST['txt_seccion'])."', subseccion='".($_POST['txt_subseccion'])."', cuenta='".($_POST['txt_cuenta'])."', fecha_adquisicion='".voltea_fecha($_POST['txt_fecha'])."', marca='".($_POST['txt_marca'])."', fabricante='".($_POST['txt_fabricante'])."', modelo='".($_POST['txt_modelo'])."', serial='".($_POST['txt_serial'])."', proveedor='".($_POST['txt_rif'])."', orden_compra='".($_POST['txt_oc'])."', factura='".($_POST['txt_factura'])."', numero_bien='".($_POST['txt_numero'])."', descripcion_bien='".strtoupper($_POST['txt_bien'])."', conservacion='".$_POST['txt_conservacion']."', valor='".$_POST["txt_valor"]."', id_dependencia='".$_POST['txt_area']."', id_categoria='".$_POST['txt_categoria']."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id_bien=".$_GET['id'].";";
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