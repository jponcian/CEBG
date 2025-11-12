<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo1 = 'info';
$id = decriptar($_GET['id']);
$destino = decriptar($_GET['destino']);
$observacion = ($_POST['txt_concepto']);
//-------------
$consultx = "UPDATE cr_memos_ext SET estatus = 7, observacion='$observacion', usuario_aprobador='".$_SESSION['CEDULA_USUARIO']."', fecha_aprobacion='".date('Y/m/d')."' WHERE id = $id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------
if ($destino<>99 and $destino<>'99')
	{
	$consultx = "DELETE FROM cr_memos_ext_destino WHERE id_correspondencia='".$id."';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$consultx = "INSERT INTO cr_memos_ext_destino(id_correspondencia, direccion_destino,usuario) VALUES ('".$id."', '$destino', '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$_SESSION['conexionsql']->query("UPDATE cr_memos_ext_destino, a_direcciones SET cr_memos_ext_destino.ci_jefe_destino = a_direcciones.cedula WHERE cr_memos_ext_destino.direccion_destino=a_direcciones.id AND cr_memos_ext_destino.id_correspondencia = $id");	
	}
//-------------
$consult = "SELECT * FROM a_instrucciones ORDER BY id;"; // WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
	{
	if ($_POST['chk_'.$registro_x->id]==1)
		{
		$consult1 = "INSERT INTO cr_memos_ext_instruccion (id_correspondencia, descripcion, complemento, usuario) VALUES ($id, '".$registro_x->descripcion."', '".strtoupper($_POST['txt_'.$registro_x->id])."', '".$_SESSION['CEDULA_USUARIO']."');"; // WHERE id_direccion='$desde'
		$_SESSION['conexionsql']->query($consult1);
		}
	}
//-------------	
$consultx = "SELECT a_direcciones.correo, cr_memos_ext.observacion, cr_memos_ext.id, cr_memos_ext.asunto, cr_memos_ext.numero FROM cr_memos_ext, a_direcciones WHERE a_direcciones.id = cr_memos_ext.direccion_destino AND cr_memos_ext.id = $id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();
$desde = 'despacho@cebg.com.ve';//$registro_x->desde;
$para = $registro_x->correo;
$asunto = $registro_x->asunto;
$observacion = $registro_x->observacion;
$direccion = 'Correspondencia Externa';//$registro_x->direccion;
$numero = $registro_x->numero;
//-------------	
$mensaje1 = "Correspondencia Enviada Exitosamente!";
//************************* TEXTO DEL MENSAJE ****************************************
// destinatarios
//$para  = $email_origen;// . ', '; // atención a la coma
//$para = 'soporte@cebg.com.ve';
// titulo
$titulo = $asunto;

// mensaje
//$mensaje = '
//<html>
//<head>
//  <title>'.$asunto.'</title>
//  <p><strong>'.$asunto.'</strong></p>
//</head>
//<body>
//  <p>'.$observacion.'</p>
//</body>
//</html>
//';

$mensaje = '
<html>
<head>
  <title>'.$asunto.'</title>
</head>
<body>
  <p>'.$observacion.'</p>
</body>
</html>
';

// Para enviar un correo HTML, debe establecerse la cabecera Content-type
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Cabeceras adicionales
//$cabeceras .= 'To: '. $para . "\r\n";
$cabeceras .= 'From: '.$direccion.' <'.$desde.'>' . "\r\n";
//$cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
$cabeceras .= 'Bcc: soporte@cebg.com.ve' . "\r\n";

// Enviarlo
//$respuesta = enviar_email($para, $asunto, $mensaje, $cabeceras);
mail($para, $titulo, $mensaje, $cabeceras);
//************************************************************************************
//-------------
$info = array ("tipo"=>$tipo1, "msg"=>$mensaje1, "id"=>$_GET['id']);
echo json_encode($info);
?>