<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$_POST["txt_monto"] = str_replace('.','',$_POST['txt_monto']); 
$_POST["txt_monto"] = str_replace(',','.',$_POST['txt_monto']);
//-------------	
if ($_POST['txt_banco']=='SELECCIONE')
	{
	$mensaje = "No ha seleccionado la Cuenta de Origen!"; $tipo = 'alerta';
	}
//-------------	
if ($_POST['txt_banco2']=='SELECCIONE')
	{
	$mensaje = "No ha seleccionado la Cuenta de Destino!"; $tipo = 'alerta';
	}
//-------------	
if (trim($_POST['txt_operacion'])=='' and $_POST['opcion']==2)
	{
	$mensaje = "No ha completado el Numero de Operacion!"; $tipo = 'alerta';
	}
//-------------	
if ($_POST['txt_chequera']=='0' and $_POST['opcion']==1)
	{
	$mensaje = "No ha seleccionado la Chequera!"; $tipo = 'alerta';
	}
//-------------	
if ($_POST['txt_cheque']=='0' and $_POST['opcion']==1)
	{
	$mensaje = "No ha seleccionado el Cheque Utilizado!"; $tipo = 'alerta';
	}
//-------------
if ($tipo=='info')
	{
	//list($banco,$cuenta)=explode(' ', $_POST['txt_banco']);
	//-------------	
	if ($_POST['opcion']==1)
		{ 	
		$fecha = $_POST['txt_fechac']; 
		//-------------	
		$consultx = "SELECT * FROM a_cuentas_cheques WHERE (id = '".$_POST['txt_cheque']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro_x = $tablx->fetch_object();
		$banco = $registro_x->banco;
		$cuenta = $registro_x->cuenta;
		$chequera = $registro_x->chequera;
		$operacion = $registro_x->cheque;	
		}
	else
		{ 	
		$consultx = "SELECT banco, cuenta FROM a_cuentas WHERE (id = '".$_POST['txt_banco']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro_x = $tablx->fetch_object();
		$banco = $registro_x->banco;
		$cuenta = $registro_x->cuenta;
		$consultx = "SELECT banco, cuenta FROM a_cuentas WHERE (id = '".$_POST['txt_banco2']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$registro_x = $tablx->fetch_object();
		$banco2 = $registro_x->banco;
		$cuenta2 = $registro_x->cuenta;
		$fecha = $_POST['txt_fechat']; 
		$operacion = $_POST['txt_operacion'];	
		}
//	//-------------	
//	$consultx = "UPDATE a_cuentas_cheques SET estatus = 99 WHERE id_orden_pago = '".$_POST['oid']."';";
//	$tablx = $_SESSION['conexionsql']->query($consultx);
//	//-------------	
//	$consultx = "UPDATE a_cuentas_cheques SET estatus = 10, id_orden_pago = ".$_POST['oid']." WHERE id = '".$_POST['txt_cheque']."';";
//	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	//$consultx = "UPDATE ordenes_pago SET id_chequera = '".$_POST['txt_chequera']."', id_cheque = '".$_POST['txt_cheque']."', tipo_pago = '".$_POST['opcion']."', banco = '$banco', cuenta = '$cuenta', banco2 = '$banco2', cuenta2 = '$cuenta2', chequera = '$chequera', num_pago = '".($operacion)."', fecha_pago = '".voltea_fecha($fecha)."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = ".$_POST['oid'].";";	
	//-------------	
	$consultx = "INSERT INTO ordenes_pago_pagos(estatus, id_orden, fecha, monto, tipo_pago, banco, banco2, cuenta, cuenta2, chequera, num_pago, fecha_pago, id_chequera, id_cheque, usuario) VALUES (10, ".$_POST['oid'].", '".date('Y/m/d')."', '".$_POST['txt_monto']."', 2, '$banco', '$banco2', '$cuenta', '$cuenta2', '$chequera', '".($operacion)."', '".voltea_fecha($fecha)."', '".$_POST['txt_chequera']."', '".$_POST['txt_cheque']."', '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//---------
	$consultx = "UPDATE ordenes_pago SET contabilidad=1, num_comprobante = '".comprobante_sig($_GET['tipo'], anno(voltea_fecha($fecha)))."', fecha_comprobante = '".voltea_fecha($fecha)."' WHERE num_comprobante=0 AND id = ".$_POST['oid'].";";
	$tablx = $_SESSION['conexionsql']->query($consultx);
////-------------	PARA AGREGAR EL MOVIMIENTO
$consultx = "INSERT INTO estado_cuenta(id_banco, fecha, referencia, concepto, debe, haber, estatus, usuario) VALUES (".trim($_POST['txt_banco']).", '".voltea_fecha($fecha)."', '".trim($operacion)."', 'PAGO OP', '0', '0".($_POST['txt_monto'])."', '0', '".($_SESSION['CEDULA_USUARIO'])."')";
$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object();
		$id = $registrox->id;
	//-------------	
$consultx = "UPDATE estado_cuenta SET id_orden = ".$_POST['oid'].", estatus_op=1, ordenado=id WHERE id = $id";
$tablx = $_SESSION['conexionsql']->query($consultx);
$consultx = "UPDATE estado_cuenta, ordenes_pago, contribuyente SET estado_cuenta.tipo_orden = ordenes_pago.tipo_solicitud, estado_cuenta.numero_orden = ordenes_pago.numero, estado_cuenta.rif_orden = contribuyente.rif, estado_cuenta.nombre_orden = contribuyente.nombre , estado_cuenta.concepto = ordenes_pago.descripcion WHERE ordenes_pago.id_contribuyente = contribuyente.id AND estado_cuenta.id_orden = ordenes_pago.id AND estado_cuenta.id_orden =".$_POST['oid']."";
$tablx = $_SESSION['conexionsql']->query($consultx);
////-----------------------------------------------	
	$consultax = "CALL actualizar_orden_pago_pagos('".$_POST['oid']."');";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	//-------------	
//	$consultax = "CALL actualizar_orden_pago_nomina();";
//	$tablax = $_SESSION['conexionsql']->query($consultax);
	//-------------	
	$mensaje = "Orden de Pago Actualizada Exitosamente!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>