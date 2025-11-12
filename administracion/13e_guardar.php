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
list($id_rif0,$rif0) = explode("/",$_POST['txt_id_rif']);
//----------------
if ($_POST['txt_partida']<>'999')
	{
	$consultx = "INSERT INTO orden(exento, porcentaje_iva, control, factura, fecha_factura, tipo_orden, id_contribuyente, rif, fecha, anno, concepto, numero, categoria, partida, cantidad, descripcion, precio_uni, total, estatus, usuario) VALUES ('".abs($_POST['txt_exento'])."', '".$_POST['txt_iva']."', '".$_POST['txt_control']."', '".$_POST['txt_factura']."', '".voltea_fecha($_POST['txt_fecha'])."', 'M', '$id_rif0', '".strtoupper($rif0)."', '".date('Y/m/d')."', ".anno(voltea_fecha($_POST['txt_fecha'])).", '".strtoupper($_POST['txt_concepto'])."', '0', '".$_POST['txt_categoria']."', '".$_POST['txt_partida']."', '".$_POST['txt_cantidad']."', '".strtoupper($_POST['txt_detalle'])."', '".$_POST['txt_precio']."', '".$_POST['txt_cantidad']*$_POST['txt_precio']."', '0', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	}
else
	{
	$consultax = "SELECT * FROM a_presupuesto_".anno(voltea_fecha($_POST['txt_fecha']))." WHERE categoria='".$_POST['txt_categoria']."' AND dozavo=1 ORDER BY id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	while ($registro = $tablax->fetch_object())
		{
		$consultx = "INSERT INTO orden(control, factura, fecha_factura, tipo_orden, id_contribuyente, rif, fecha, anno, concepto, numero, categoria, partida, cantidad, descripcion, precio_uni, total, estatus, usuario) VALUES ('".$_POST['txt_control']."', '".$_POST['txt_factura']."', '".voltea_fecha($_POST['txt_fecha'])."', 'M', '$id_rif0', '".strtoupper($rif0)."', '".date('Y/m/d')."', ".anno(voltea_fecha($_POST['txt_fecha'])).", '".strtoupper($_POST['txt_concepto'])."', '0', '".$registro->categoria."', '".$registro->codigo."', '1', '".$registro->descripcion."', '".(($registro->original/12)/1000000)."', '".(($registro->original/12)/1000000)."', '0', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		}
	}
//----------------
$consultx1 = "UPDATE orden SET numero=0, fecha_factura='".voltea_fecha($_POST['txt_fecha'])."', factura='".strtoupper($_POST['txt_factura'])."', control='".strtoupper($_POST['txt_control'])."', fecha='".date('Y/m/d')."', anno=".anno(voltea_fecha($_POST['txt_fecha'])).", concepto='".strtoupper($_POST['txt_concepto'])."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id_contribuyente='$id_rif0' AND estatus=0 AND tipo_orden='M';";
$tablx = $_SESSION['conexionsql']->query($consultx1);	
//-------------	
$mensaje = "Detalle Agregado Exitosamente!";

$info = array ("tipo"=>$tipo, "id"=>encriptar($id_rif0), "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>