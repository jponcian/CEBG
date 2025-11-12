<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

$_POST["txt_precio"] = str_replace('.','',$_POST['txt_precio']); 
$_POST["txt_precio"] = str_replace(',','.',$_POST['txt_precio']);
//if ($_POST["txt_iva1"]>0 and substr($_POST['txt_partida'],0,7)=='4031801') 
//	{ $mensaje = "Ya fue cargada la Partida del IVA!";
//	$tipo = 'alerta';	}
//else
//	{	
	if ($_POST["txt_precio"]>0)
		{
		//----------------
		$consultx = "INSERT INTO orden(id_presupuesto, exento, porcentaje_iva, control, factura, fecha_factura, tipo_orden, tipo_orden2, id_contribuyente, rif, fecha, anno, concepto, numero, categoria, partida, cantidad, descripcion, precio_uni, total, estatus, usuario) VALUES ('".$_POST['txt_id_presupuesto']."', '".abs($_POST['txt_exento'])."', '".$_POST['txt_iva']."', '".$_POST['txt_control']."', '".$_POST['txt_factura']."', '".voltea_fecha($_POST['txt_fecha'])."', '".$_POST['txt_tipo']."', '".$_POST['txt_tipo2']."', '".$_POST['txt_id_rif']."', '".strtoupper($_POST['txt_rif'])."', '".date('Y/m/d')."', ".date('Y').", '".strtoupper($_POST['txt_concepto'])."', '".$_POST['txt_numero']."', '".$_POST['txt_categoria']."', '".$_POST['txt_partida']."', '".$_POST['txt_cantidad']."', '".strtoupper($_POST['txt_detalle'])."', '".$_POST['txt_precio']."', '".$_POST['txt_cantidad']*$_POST['txt_precio']."', '0', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		//----------------
		$consult = "UPDATE orden SET fecha_factura='".voltea_fecha($_POST['txt_fecha'])."', factura='".strtoupper($_POST['txt_factura'])."', control='".strtoupper($_POST['txt_control'])."', fecha='".date('Y/m/d')."', anno=".anno(voltea_fecha($_POST['txt_fecha'])).", concepto='".strtoupper($_POST['txt_concepto'])."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id_contribuyente='".$_POST['txt_id_rif']."' AND estatus=0 AND tipo_orden=1;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		//-------------	
		$mensaje = "Detalle Agregado Exitosamente!";
		}
	else
		{
		$mensaje = "El precio debe ser mayor a 0!";
		$tipo = 'alerta';
		}
//	}

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>