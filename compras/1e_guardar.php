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

list($id_rif0,$rif0) = explode("/",$_POST['txt_rif'][0]);
list($id_rif1,$rif1) = explode("/",$_POST['txt_rif'][1]);
list($id_rif2,$rif2) = explode("/",$_POST['txt_rif'][2]);
list($id_rif3,$rif3) = explode("/",$_POST['txt_rif'][3]);
list($id_rif4,$rif4) = explode("/",$_POST['txt_rif'][4]);

if ($id_rif1=='') { $id_rif1 = 0; }
if ($id_rif2=='') { $id_rif2 = 0; }
if ($id_rif3=='') { $id_rif3 = 0; }
if ($id_rif4=='') { $id_rif4 = 0; }

//if ($_POST["txt_iva1"]>0 and substr($_POST['txt_partida'],0,7)=='4031801') 
if (1==2) 
	{ 
	$mensaje = "Ya fue cargada la Partida del IVA!";
	$tipo = 'alerta';	
	}
else
	{	
	if ($_POST["txt_precio"]>0)
		{
		//----------------
		$consultx = "INSERT INTO presupuesto(medida, oficina, exento, porcentaje_iva, fecha, fecha_presupuesto, tipo_orden, id_contribuyente, rif, id_contribuyente2, rif2, id_contribuyente3,rif3,  id_contribuyente4, rif4, id_contribuyente5, rif5, anno, concepto, memo, fecha_memo, categoria, partida, cantidad, descripcion, precio_uni, total, estatus, usuario) VALUES ('".($_POST['txt_medida'])."', '".($_POST['txt_area'])."', '".abs($_POST['txt_exento'])."', '".$_POST['txt_iva']."', '".voltea_fecha($_POST['txt_fecha'])."', '".voltea_fecha($_POST['txt_fecha'])."', 0, '".$id_rif0."', '".$rif0."', '".$id_rif1."', '".$rif1."', '".$id_rif2."', '".$rif2."', '".$id_rif3."', '".$rif3."', '".$id_rif4."', '".$rif4."', ".date('Y').", '".strtoupper($_POST['txt_concepto'])."', '".$_POST['txt_memo']."', '".voltea_fecha($_POST['txt_fechas'])."', '".$_POST['txt_categoria']."', '".$_POST['txt_partida']."', '".$_POST['txt_cantidad']."', '".strtoupper($_POST['txt_detalle'])."', '".$_POST['txt_precio']."', '".$_POST['txt_cantidad']*$_POST['txt_precio']."', '0', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		//----------------
		
	// CONSULTA
	$consult = "SELECT anno, numero, num_punto_cuenta, solicitud, solicitud2, solicitud3, solicitud4, solicitud5 FROM presupuesto WHERE id_contribuyente='".$id_rif0."' AND estatus=0 ORDER BY solicitud DESC LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consult);	
	$registrox = $tablx->fetch_object();
	$anno = $registrox->anno;
	$numero = $registrox->numero;
	$punto = $registrox->num_punto_cuenta;
	$solicitud = $registrox->solicitud;
	$solicitud2 = $registrox->solicitud2;
	$solicitud3 = $registrox->solicitud3;
	$solicitud4 = $registrox->solicitud4;
	$solicitud5 = $registrox->solicitud5;

	//TIPO DE COMPRA SEGUN LA CANTIDAD DE CONTRIBUYENTES
if ($id_rif0>0 and $id_rif1>0 and $id_rif2>0 and $id_rif3>0 and $id_rif4>0)
	{	
	$tipo_compra='CC';
	$consult1 = "UPDATE presupuesto SET tipo_orden='CC' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";	
		//-------
		if ($solicitud>0)	
		{		
		$consult = "UPDATE presupuesto SET solicitud='$solicitud' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		else
		{
		$consult = "UPDATE presupuesto SET solicitud='".sig_sol_cont()."' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		//-------
		if ($solicitud2>0)	
		{		
		$consult = "UPDATE presupuesto SET solicitud2='$solicitud2' WHERE id_contribuyente='".$id_rif1."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		else
		{
		$correlativo = sig_sol_cont();
		if ($correlativo==1) {$correlativo=$correlativo+1;}
		$consult = "UPDATE presupuesto SET solicitud2='".$correlativo."' WHERE id_contribuyente='".$id_rif1."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		//-------
		if ($solicitud3>0)	
		{		
		$consult = "UPDATE presupuesto SET solicitud3='$solicitud3' WHERE id_contribuyente='".$id_rif2."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		else
		{
		$correlativo = sig_sol_cont();
		if ($correlativo==1) {$correlativo=$correlativo+2;}
		$consult = "UPDATE presupuesto SET solicitud3='".$correlativo."' WHERE id_contribuyente='".$id_rif2."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}		
		//-------
		if ($solicitud4>0)	
		{		
		$consult = "UPDATE presupuesto SET solicitud4='$solicitud4' WHERE id_contribuyente='".$id_rif3."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		else
		{
		$correlativo = sig_sol_cont();
		if ($correlativo==1) {$correlativo=$correlativo+3;}
		$consult = "UPDATE presupuesto SET solicitud4='".$correlativo."' WHERE id_contribuyente='".$id_rif3."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}		
		//-------
		if ($solicitud5>0)	
		{		
		$consult = "UPDATE presupuesto SET solicitud5='$solicitud5' WHERE id_contribuyente='".$id_rif4."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		else
		{
		$correlativo = sig_sol_cont();
		if ($correlativo==1) {$correlativo=$correlativo+4;}
		$consult = "UPDATE presupuesto SET solicitud5='".$correlativo."' WHERE id_contribuyente='".$id_rif4."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}		
	} 
elseif ($id_rif0>0 and $id_rif1>0 and $id_rif2>0)
	{	
	$tipo_compra='CP';
	$consult1 = "UPDATE presupuesto SET tipo_orden='CP' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
	$tabl1 = $_SESSION['conexionsql']->query($consult1);	
		//-------
		if ($solicitud>0)	
		{		
		$consult = "UPDATE presupuesto SET solicitud='$solicitud' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);
		}	
		else
		{
		$consult = "UPDATE presupuesto SET solicitud='".sig_sol_cont()."' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}
		//-------
		if ($solicitud2>0)	
		{		
		$consult = "UPDATE presupuesto SET solicitud2='$solicitud2' WHERE id_contribuyente='".$id_rif1."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		else
		{
		$correlativo = sig_sol_cont();
		if ($correlativo==1) {$correlativo=$correlativo+1;}
		$consult = "UPDATE presupuesto SET solicitud2='".$correlativo."' WHERE id_contribuyente='".$id_rif1."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		//-------
		if ($solicitud3>0)	
		{		
		$consult = "UPDATE presupuesto SET solicitud3='$solicitud3' WHERE id_contribuyente='".$id_rif2."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		else
		{
		$correlativo = sig_sol_cont();
		if ($correlativo==1) {$correlativo=$correlativo+2;}
		$consult = "UPDATE presupuesto SET solicitud3='".$correlativo."' WHERE id_contribuyente='".$id_rif2."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
	} 
elseif ($id_rif0>0)
	{	
	$tipo_compra='CD';
	$consult1 = "UPDATE presupuesto SET tipo_orden='CD' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";	
		//-------
		if ($solicitud>0)	
		{		
		$consult = "UPDATE presupuesto SET solicitud='$solicitud' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
		else
		{
		$consult = "UPDATE presupuesto SET solicitud='".sig_sol_cont()."' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}
	} 
		
	$tablx = $_SESSION['conexionsql']->query($consult1);	
		
	// PUNTO CUENTA
	if ($punto>0)	
		{		
		$consult = "UPDATE presupuesto SET num_punto_cuenta='$punto' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
	else
		{
		$consult = "UPDATE presupuesto SET num_punto_cuenta='".sig_punto_cuenta()."' WHERE id_contribuyente='".$id_rif0."' AND estatus=0 AND num_punto_cuenta=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}
	
	// NUMERO COMPRA
	if ($numero>0)	
		{		
		$consult = "UPDATE presupuesto SET numero='".$numero."' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}	
	else
		{
		$consult = "UPDATE presupuesto SET numero='".sig_memo_compra($tipo_compra)."' WHERE id_contribuyente='".$id_rif0."' AND estatus=0 AND numero=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	
		}

		$consult = "UPDATE presupuesto SET oficina='".$_POST['txt_area']."', id_contribuyente2='".$id_rif1."', id_contribuyente3='".$id_rif2."', id_contribuyente4='".$id_rif3."', id_contribuyente5='".$id_rif4."', fecha_memo='".voltea_fecha($_POST['txt_fechas'])."', fecha='".voltea_fecha($_POST['txt_fecha'])."', fecha_presupuesto='".voltea_fecha($_POST['txt_fecha'])."', anno=".anno(voltea_fecha($_POST['txt_fecha'])).", memo='".strtoupper($_POST['txt_memo'])."', concepto='".strtoupper($_POST['txt_concepto'])."', usuario='".$_SESSION['CEDULA_USUARIO']."', punto_cuenta=CONCAT('07-',lpad(num_punto_cuenta,3,'0'),'-',anno) WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	

		// FECHAS DE LA TABLA
		$fecha = voltea_fecha($_POST['txt_fecha']);
		$fecha_solicitud = dia_vencimiento($fecha,1);
		$fecha_recibido = dia_vencimiento($fecha,2);
		$fecha_ofertas = dia_vencimiento($fecha,4);
		$fecha_examen = dia_vencimiento($fecha,5);
		$fecha_adjudicacion = dia_vencimiento($fecha,6);
		$fecha_notificacion = dia_vencimiento($fecha,7);
		$fecha_recepcion = dia_vencimiento($fecha,8);
		$fecha_orden = dia_vencimiento($fecha,9);
		//----------
		$consult = "UPDATE presupuesto SET fecha_solicitud='$fecha_solicitud', fecha_recibido='$fecha_recibido', fecha_ofertas='$fecha_ofertas', fecha_examen='$fecha_examen', fecha_adjudicacion='$fecha_adjudicacion', fecha_notificacion='$fecha_notificacion', fecha_recepcion='$fecha_recepcion', fecha_orden='$fecha_orden' WHERE id_contribuyente='".$id_rif0."' AND estatus=0;";
		$tablx = $_SESSION['conexionsql']->query($consult);	

		//-------------	
		$mensaje = "Detalle Agregado Exitosamente!";
		}
	else
		{
		$mensaje = "El precio debe ser mayor a 0!";
		$tipo = 'alerta';
		}
	}

$info = array ("tipo"=>$tipo, "id"=>encriptar($id_rif0), "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>