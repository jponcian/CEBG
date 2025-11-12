<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

if (trim($_POST['txt_grupo'])<>'' and trim($_POST['txt_numero'])<>''  and trim($_POST['txt_descripcion'])<>'' )
{
if ($_GET['id']==0)
	{
	$consultx = "SELECT grupo, numero FROM arc_biblioteca WHERE grupo='".$_POST['txt_grupo']."' AND numero='".$_POST['txt_numero']."' LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = "Ya existe un lote con ese Grupo y Número!";
		}
	else
		{
		//----------------
		$consultx = "INSERT INTO arc_biblioteca (grupo, numero, descripcion, usuario ) VALUES ('".trim($_POST['txt_grupo'])."', '".trim($_POST['txt_numero'])."', '".trim($_POST['txt_descripcion'])."', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//------------
		$id = 0;
		//-------------	
		$mensaje = "Información Registrada Exitosamente!";
		}
	}
if ($_GET['id']>0)
	{
	//----------------
	$consultx = "UPDATE arc_biblioteca SET grupo='".($_POST['txt_grupo'])."', numero='".($_POST['txt_numero'])."', descripcion='".($_POST['txt_descripcion'])."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id=".$_GET['id'].";";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//------------
	$id = 0;
	//-------------	
	$mensaje = "Información Actualizada Exitosamente!";
	}
}
else
{
	$tipo = 'alerta';
	$mensaje = "Existen Campos Vacíos!";
}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$id);

echo json_encode($info);
?>