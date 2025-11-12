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
if (abs(trim($_POST['txt_debe']))<=0 and abs(trim($_POST['txt_haber']))<=0)
	{
	$mensaje = "No ha indicado el monto de la operacion!"; $tipo = 'alerta';
	}
//-------------
if (trim($_POST['txt_banco2'])>0)
	{
	$rif_orden = 'G200012870';
	$nombre_orden = 'CONTRALORIA DEL ESTADO BOLIVARIANO DE GUARICO';
	}
else
	{
//	$rif_orden = 'G200012870';
	$rif_orden = '';
	$rif_orden = trim($_POST['txt_rif']);
	$nombre_orden = trim($_POST['txt_beneficiario']);
	}
//-------------
if ($tipo=='info')
	{
	//-------------	
	$consultx = "INSERT INTO estado_cuenta(estatus_op, rif_orden, nombre_orden, id_banco, id_banco_destino, fecha, referencia, concepto, debe, haber, estatus, usuario) VALUES (1, '$rif_orden', '$nombre_orden', ".trim($_POST['txt_banco']).", ".trim($_POST['txt_banco2']).", '".voltea_fecha(trim($_POST['txt_fecha']))."', '".trim($_POST['txt_referencia'])."', '".trim($_POST['txt_descripcion'])."', '0".($_POST['txt_debe'])."', '0".($_POST['txt_haber'])."', 0, '".($_SESSION['CEDULA_USUARIO'])."')";
	$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
			$consultax = "SELECT LAST_INSERT_ID() as id;";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			$registrox = $tablax->fetch_object();
			$id = $registrox->id;
		//-------------	
	if (trim($_POST['txt_banco2'])>0)
		{
//		$rif_orden = 'G200012870';
//		$nombre_orden = 'CONTRALORIA DEL ESTADO BOLIVARIANO DE GUARICO';
		$consultx = "INSERT INTO estado_cuenta(estatus_op, mov_emisor, rif_orden, nombre_orden, id_banco, fecha, referencia, concepto, debe, haber, estatus, usuario) VALUES (1, '$id', '$rif_orden', '$nombre_orden', ".trim($_POST['txt_banco2']).", '".voltea_fecha(trim($_POST['txt_fecha']))."', '".trim($_POST['txt_referencia'])."', '".trim($_POST['txt_descripcion2'])."', '0".($_POST['txt_haber'])."', '0".($_POST['txt_debe'])."', 0, '".($_SESSION['CEDULA_USUARIO'])."')";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		}
	//-------------	
	$consultx = "UPDATE estado_cuenta SET ordenado=id WHERE id=$id";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
//	$consultx = "UPDATE estado_cuenta SET estatus_op=1 WHERE (estatus_op=0 and haber>0) or (mov_emisor>0);";
//	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Movimiento Registrado Exitosamente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>