<?php
//----------------
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$info = array();
$tipo = 'info';

$_SESSION['id'] = decriptar($_POST['oid']);

$destino = ($_POST['txt_destino']);
$origen = trim($_POST['txt_origen']);
$instituto = trim($_POST['txt_instituto']);
$asunto = trim($_POST['txt_asunto']);
$numero = trim($_POST['txt_numero']);
$fecha = voltea_fecha($_POST['txt_fecha']);
$anno = anno(voltea_fecha($_POST['txt_fecha']));
//Si se quiere subir una imagen

if ($numero<>'' and $fecha<>'' and $asunto<>'' and $origen<>'' and $destino>0)
	{
	if ($_SESSION['id']==0) 
		{		 
		$consulta_x = "INSERT INTO cr_memos_ext(instituto, origen, direccion_destino, anno, numero, fecha, asunto, usuario) VALUES ('$instituto', '$origen', '$destino', '$anno', '$numero', '$fecha', '$asunto', '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consulta_x;
		$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
		//------------
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object(); //echo $registrox->id;
		$_SESSION['id'] = $registrox->id;
		}
	else
		{
		$consulta_x = "UPDATE cr_memos_ext SET instituto='$instituto', origen='$origen', direccion_destino='$destino', anno='$anno', numero='$numero', fecha='$fecha', asunto='$asunto', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id=".$_SESSION['id'].";"; //echo $consulta_x;
		$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
		}
	$mensaje = 'Correspondecia Registrada Exitosamente!'; 
	}
else {
     $mensaje = 'Debe adjuntar el archivo correctamente.'; $tipo = 'error';
     }
	
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$_SESSION['id']);
echo json_encode($info);
?>