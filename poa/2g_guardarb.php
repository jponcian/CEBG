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
	$consultx = "SELECT id_proyecto, id_responsable, estatus, codigo, costo, fecha, meta, actividad, indicador, usuario FROM poa_metas WHERE id = '$id';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	$registro_x = $tablx->fetch_object();
	$id_proyecto0 = $registro_x->id_proyecto;
	$id_responsable0 = $registro_x->id_responsable;
	$estatus0 = $registro_x->estatus;
	$codigo0 = $registro_x->codigo;
	$costo0 = $registro_x->costo;
	$fecha0 = $registro_x->fecha;
	$meta0 = $registro_x->meta;
	$actividad0 = $registro_x->actividad;
	$indicador0 = $registro_x->indicador;
	$fecha_modificada = voltea_fecha($_POST['ofecha']);
	//-------------
	if ((checkdate(mes(($fecha)),dia(($fecha)),anno(($fecha))))==1)
		{
		$consultx = "INSERT INTO poa_metas (fecha_modificada, meta_original, modificada, id_proyecto, id_responsable, estatus, codigo, costo, fecha, meta, actividad, indicador, usuario) VALUES ('$fecha_modificada', '$id', 1, '$id_proyecto0','$id_responsable0','0','$codigo','$costo','$fecha','$meta','$actividad','$indicador','".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		//-------------	
			$consultax = "SELECT LAST_INSERT_ID() as id;";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			$registrox = $tablax->fetch_object();
			$id_new = $registrox->id;
		//-------------	
		$consultx = "UPDATE poa_metas SET modificada = '99' WHERE id = $id;";
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		//-------------
		$consultx = "UPDATE poa_metas_frecuencia SET id_meta = $id_new WHERE id_meta = $id AND estatus = 0;";
		//$tablx = $_SESSION['conexionsql']->query($consultx);	
		//-------------
		$consultx = "UPDATE poa_metas_gestion SET id_meta = $id_new WHERE id_meta = $id;";
	//	$tablx = $_SESSION['conexionsql']->query($consultx);	
		//-------------
		$mensaje = "Meta Actualizada Exitosamente!";
		}
	else
		{
		$tipo = 'alerta';
		$mensaje = 'Ingrese una fecha Correcta!';
		}	
	}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);
echo json_encode($info);
?>