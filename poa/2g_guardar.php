<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id_proyecto = decriptar($_POST['oid']);
$fecha = date('Y/m/d');
$id_responsable = $_SESSION['id_responsable'];
$id = trim($_POST['oidD']);
$codigo = trim($_POST['txt_codigo']);
$costo = trim($_POST['txt_costo']);
$meta = trim($_POST['txt_meta']);
$actividad = trim($_POST['txt_actividad']);
$indicador = trim($_POST['txt_indicador']);
$costo = str_replace('.','',$costo); 
$costo = str_replace(',','.',$costo); 
//-------------
if ($id>0)
	{
	$consultx = "UPDATE poa_metas SET codigo = '$codigo', costo = '$costo', meta = '$meta', actividad = '$actividad', indicador = '$indicador' WHERE id = $id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	$mensaje = "Meta Actualizada Exitosamente!";
	//-------------
	}
else
	{
	$consulta_x = "SELECT * FROM poa_metas WHERE codigo = '$codigo';";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	if ($tabla_x->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = 'Ya existe una Meta con ese Código!';
		}
	else
		{
		$consultx = "INSERT INTO poa_metas (id_proyecto, id_responsable, estatus, codigo, costo, fecha, meta, actividad, indicador, usuario) VALUES ('$id_proyecto', '$id_responsable', 0, '$codigo', '$costo', '$fecha', '$meta', '$actividad', '$indicador', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
		$mensaje = "Meta Registrada Exitosamente!";
		}	
	}


//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>