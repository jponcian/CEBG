<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$_POST["txt_debe"] = str_replace('.','',$_POST['txt_debe']); 
$_POST["txt_debe"] = str_replace(',','.',$_POST['txt_debe']);
//-------------	
$_POST["txt_haber"] = str_replace('.','',$_POST['txt_haber']); 
$_POST["txt_haber"] = str_replace(',','.',$_POST['txt_haber']);
//-------------	
if (trim($_POST['txt_banco'])<1)
	{
	$mensaje = "No ha seleccionado el banco!"; $tipo = 'alerta';
	}
//-------------	
if (trim($_POST['txt_fecha'])=='')
	{
	$mensaje = "No ha indicado la fecha!"; $tipo = 'alerta';
	}
//-------------	
if (trim($_POST['txt_referencia'])=='')
	{
	$mensaje = "No ha indicado la referencia!"; $tipo = 'alerta';
	}
//-------------	
if (($_POST['txt_debe'])<0 and ($_POST['txt_haber'])<0)
	{
	$mensaje = "No ha indicado el monto de la operacion!"; $tipo = 'alerta';
	}
//-------------
if ($tipo=='info')
	{
	//-------------	
	$consultx = "INSERT INTO estado_cuenta(id_banco, fecha, referencia, concepto, debe, monto, estatus, usuario) VALUES (".trim($_POST['txt_banco']).", '".voltea_fecha(trim($_POST['txt_fecha']))."', '".trim($_POST['txt_referencia'])."', '".trim($_POST['txt_descripcion'])."', '0".($_POST['txt_haber'])."', '0".($_POST['txt_debe'])."', 0, '".($_SESSION['CEDULA_USUARIO'])."')";
	$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
			$consultax = "SELECT LAST_INSERT_ID() as id;";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			$registrox = $tablax->fetch_object();
			$id = $registrox->id;
		//-------------	
	$consultx = "UPDATE estado_cuenta SET ordenado=id WHERE id=$id";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Movimiento Registrado Exitosamente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>