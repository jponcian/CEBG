<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$tipo='info';
$id = decriptar($_POST['oid']);
$origen = ($_POST['txt_origen']);
$destino = ($_POST['txt_destino']);
$asunto = trim($_POST['txt_asunto']);
$cuerpo = trim($_POST['txt_concepto']);
$fecha = voltea_fecha($_POST['txt_fecha']);
//-------
if ($asunto<>'' and $cuerpo<>'' and $origen>0 and $destino>0)
	{
	if ($id==0) 
		{		 
		$consultx = "INSERT INTO cr_memos_div(asunto, estatus, direccion_origen, direccion_destino, anno, numero, fecha, cuerpo, usuario) VALUES ('$asunto', 0, '$origen', '$destino', '".date('Y')."', 0, '".date('Y-m-d')."', '$cuerpo', '".$_SESSION['CEDULA_USUARIO']."');"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
			$consultax = "SELECT LAST_INSERT_ID() as id;";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			$registrox = $tablax->fetch_object();
			$id = $registrox->id;		
		//-------------	
		$consultx = "INSERT INTO cr_memos_div_destino (id_correspondencia, direccion_destino, usuario) VALUES ('$id', '$destino', '".$_SESSION['CEDULA_USUARIO']."');"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		}
	else
		{
		$consulta_x = "UPDATE cr_memos_div SET direccion_destino='$destino', cuerpo='$cuerpo', direccion_origen='$origen', fecha='$fecha', asunto='$asunto', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id=$id;"; //echo $consulta_x;
		$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
		//-------------	
		$_SESSION['conexionsql']->query("DELETE FROM cr_memos_div_destino WHERE id_correspondencia=$id");	
		//-------------	
		if ($destino<>99)
			{	$consultx = "INSERT INTO cr_memos_div_destino (id_correspondencia, direccion_destino, usuario) VALUES ('$id', '$destino', '".$_SESSION['CEDULA_USUARIO']."');"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);	}
//		$consulta_x = "UPDATE cr_memos_div_destino SET direccion_destino='$destino', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id_correspondencia=$id;"; //echo $consulta_x;
//		$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//		if ($destino==99)
//			{	$_SESSION['conexionsql']->query("DELETE FROM cr_memos_div_destino WHERE id_correspondencia=$id");		}
		}
	
	//-------------	
	$consultx = "UPDATE cr_memos_div, a_direcciones SET cr_memos_div.ci_jefe_origen = a_direcciones.cedula WHERE cr_memos_div.direccion_origen = a_direcciones.id AND cr_memos_div.id=$id;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$consultx = "UPDATE cr_memos_div, a_direcciones SET cr_memos_div.ci_jefe_destino = a_direcciones.cedula WHERE cr_memos_div.direccion_destino = a_direcciones.id AND cr_memos_div.id=$id;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$consultx = "UPDATE cr_memos_div_destino, a_direcciones SET cr_memos_div_destino.ci_jefe_destino = a_direcciones.cedula WHERE cr_memos_div_destino.direccion_destino = a_direcciones.id AND cr_memos_div_destino.id_correspondencia=$id;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------
	$mensaje = "Memorando Generado Exitosamente!";
	}
else
	{
	$tipo='alerta';
	$mensaje = "Existen Campos Vacios!";
	}
//-------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>