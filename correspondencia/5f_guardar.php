<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//----------------
$tipo='success';
$id = decriptar($_POST['oid']);
$origen = ($_POST['txt_origen']);
$firma = ($_POST['txt_firma']);
$destino = trim($_POST['txt_destinatario']);
$fecha = voltea_fecha($_POST['txt_fecha']);
$anno = anno(voltea_fecha($_POST['txt_fecha']));
$instituto = trim($_POST['txt_instituto']);
$asunto = trim($_POST['txt_asunto']);
$cuerpo = trim($_POST['txt_concepto']);
$direccion = trim($_POST['txt_direccion']);
$telefono = trim($_POST['txt_telefono']);
$pre = trim($_POST['txt_pre']);
//-------
if ($asunto<>'' and $cuerpo<>'' and $origen>0 and $destino<>'')
	{
	if ($id==0) 
		{		 
		$consultx = "INSERT INTO cr_memos_dir_ext (pre, cuerpo, direccion, telefono, estatus, direccion_origen, destinatario, instituto, fecha, anno,  asunto, firma_contralor, usuario) VALUES ('$pre', '$cuerpo', '$direccion', '$telefono', 0, '$origen', '$destino', '$instituto', '$fecha', '$anno', '$asunto','$firma', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object();
		$id = $registrox->id;
		}
	else
		{

		$consulta_x = "UPDATE cr_memos_dir_ext SET pre='$pre', cuerpo='$cuerpo', direccion='$direccion', telefono='$telefono', instituto='$instituto', direccion_origen='$origen', fecha='$fecha', asunto='$asunto', firma_contralor='$firma', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id=$id;"; //echo $consulta_x;
		$tabla_x = $_SESSION['conexionsql']->query($consulta_x);

		}
	
	//-------------	
	$consultx = "UPDATE cr_memos_dir_ext, a_direcciones SET cr_memos_dir_ext.ci_jefe = a_direcciones.cedula WHERE cr_memos_dir_ext.direccion_origen = a_direcciones.id AND cr_memos_dir_ext.id = $id;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	
	$consultx = "UPDATE cr_memos_dir_ext, a_direcciones SET cr_memos_dir_ext.ci_contralor = a_direcciones.cedula WHERE 1 = a_direcciones.id AND cr_memos_dir_ext.id = $id;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------
	$mensaje = "Memorando Generado Exitosamente!";
	}
else
	{
	$tipo='alert';
	$mensaje = "Existen Campos Vacios!";
	}
//-------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id));
echo json_encode($info);
?>