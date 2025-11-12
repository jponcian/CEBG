<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tip = 'info';
$id_cont = decriptar($_POST['id']);
//-------------
$consultx = "SELECT partida FROM presupuesto WHERE id_contribuyente=".$id_cont." AND estatus=0  AND left(trim(partida),7)<>'4031801';";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{
	$consultx = "SELECT fecha_orden, rif, tipo_orden, id_solicitud, id_contribuyente, fecha_presupuesto, concepto, sum(total) as tot, anno, numero FROM presupuesto WHERE id_contribuyente=".$id_cont." AND estatus=0 GROUP BY numero LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro = $tablx->fetch_object();
	//-------------	
	$rif = $registro->rif;
	$anno = $registro->anno;
	$fecha = $registro->fecha_presupuesto;
	$fecha_compra = $registro->fecha_orden;
	$numero = $registro->numero;
	$tipo = $registro->tipo_orden;
	$tot = $registro->tot;
	$concepto = $registro->concepto;
	$id_contribuyente = $registro->id_contribuyente;
	//-------------
	if ($_GET['id']==0){	$numero=compromiso_sig(1); 	}//$fecha = date('Y/m/d');		}
	//else	
//		{
//		$valor = explode("*",$_GET['id']);
//		$numero = $valor[0];
//		$fecha = $valor[1];
//		$id = $valor[2];
//		$consultb = "DELETE FROM presupuesto_solicitudes WHERE id=$id;";
//		$_SESSION['conexionsql']->query($consultb);
//		}
	$consultx = "INSERT INTO presupuesto_solicitudes(rif, id_contribuyente, tipo_orden, numero, fecha_sol, anno, fecha, fecha_compra, descripcion, asignaciones, total, estatus, usuario) VALUES ('$rif', '$id_contribuyente', '$tipo', $numero, '$fecha', '$anno', '$fecha', '$fecha_compra', '$concepto', '$tot', '$tot', 3, '".$_SESSION['CEDULA_USUARIO']."');"; 
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
	$id = $registrox->id;
	//-------------	
	$consultx = "UPDATE presupuesto SET estatus = 3, id_solicitud = $id, usuario_solicitud = '".$_SESSION['CEDULA_USUARIO']."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_contribuyente=".$id_cont." AND estatus=0;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	//$consultx = "UPDATE presupuesto_solicitudes, presupuesto SET presupuesto.fecha=presupuesto_solicitudes.fecha_sol WHERE presupuesto.id_solicitud=presupuesto_solicitudes.id;";
	//$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$mensaje = "Presupuesto Generado Exitosamente!";
	}
else
	{
	$mensaje = "No Existen Partidas para Generar el Presupuesto!";
	$tip = 'alerta';
	}
//-------------
$info = array ("tipo"=>$tip, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>