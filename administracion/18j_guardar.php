<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id_contribuyente = $_POST['txt_id_rif'];
$rif = $_POST['txt_rif'];
$categoria = $_POST['txt_categoria'];
$anno = anno(voltea_fecha($_POST['txt_fecha']));
$fecha = (voltea_fecha($_POST['txt_fecha']));
$concepto = $_POST['txt_concepto'];
$numero = num_orden_compra($anno,'M');
$id = 0;
$total = 0 ;
//-------------
$consultx = "DELETE FROM orden WHERE id_contribuyente = $id_contribuyente AND estatus = 0 AND tipo_orden = 3;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------
$consultx = "SELECT id, codigo, categoria, descripcion FROM a_presupuesto_$anno WHERE categoria='$categoria' ORDER BY codigo;"; 
$tabla = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tabla->fetch_object())
	{
	$_POST[$registro->id] = str_replace('.','',$_POST[$registro->id]); 
	$_POST[$registro->id] = str_replace(',','.',$_POST[$registro->id]); 
	if ($_POST[$registro->id]>0)
		{
		$consultx = "INSERT INTO orden(estatus, tipo_orden, id_contribuyente, rif, fecha, anno, concepto, numero, categoria, partida, cantidad, descripcion, precio_uni, total, fecha_factura, usuario) VALUES (0, '3', $id_contribuyente, '$rif', '".date('Y/m/d')."', $anno, '$concepto', $numero, '".$registro->categoria."', '".$registro->codigo."', 1, '".$registro->descripcion."', '".$_POST[$registro->id]."', '".$_POST[$registro->id]."', '$fecha', '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$total = $total + $_POST[$registro->id] ;
		}
	}
//-------------	
if ($total>0)
	{
//	$consultx = "INSERT INTO orden_solicitudes(fecha_factura, id_orden_pago, id_contribuyente, tipo_orden, numero, fecha_sol, anno, fecha, descripcion, asignaciones, total, estatus, usuario) VALUES ('$fecha', '0', '$id_contribuyente', '3', $numero, '".date('Y/m/d')."', '$anno', '$fecha', '$concepto', '$total', '$total', 5, '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
//	$tablx = $_SESSION['conexionsql']->query($consultx);
//	//-------------	
//		$consultax = "SELECT LAST_INSERT_ID() as id;";
//		$tablax = $_SESSION['conexionsql']->query($consultax);	
//		$registrox = $tablax->fetch_object();
//		$id = $registrox->id;
//	//-------------	
//	$consultx = "UPDATE orden SET estatus = 5, numero = $numero, id_solicitud = $id, usuario_solicitud = '".$_SESSION['CEDULA_USUARIO']."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_contribuyente=".$id_contribuyente." AND tipo_orden='M' AND estatus=0;";
//	$tablx = $_SESSION['conexionsql']->query($consultx);
//	//-------------	
//	$mensaje = "Solicitud de Pago Generada Exitosamente!";
	}
else
	{
	$tipo = 'alerta';
	$mensaje = "No hay informacion para Guardar!!!";
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>