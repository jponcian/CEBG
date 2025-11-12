<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$id = decriptar($_POST['id']);
//-------------
$consultx = "UPDATE cr_memos_div SET estatus = 10 WHERE id = $id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$consultx = "SELECT * FROM vista_memorando_div WHERE id = $id ;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();
$desde = $registro_x->desde;
$para = $registro_x->para;
$asunto = $registro_x->asunto;
$cuerpo = $registro_x->cuerpo;
$direccion = $registro_x->direccion;
$siglas = $registro_x->siglas1;
$numero = $registro_x->numero;
$anno = $registro_x->anno;
//-------------	
$mensaje1 = "Memorando Enviado Exitosamente!";
//************************* TEXTO DEL MENSAJE ****************************************
// destinatarios
//$para  = $email_origen;// . ', '; // atención a la coma
//$para = 'soporte@cebg.com.ve';
// titulo
$titulo = $asunto;

// mensaje
$mensaje = '
<html>
<head>
  <title>'.$asunto.'</title>
  <p>'.$siglas.$anno.'/'.rellena_cero($numero,6).'</p>
  <p><strong>'.$asunto.'</strong></p>
</head>
<body>
  <p>'.$cuerpo.'</p>
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
$info = array ("tipo"=>$tipo, "msg"=>$mensaje1, "id"=>$_POST['id']);
echo json_encode($info);
?>