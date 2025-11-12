<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id = $_POST['id']; 
//$fecha = voltea_fecha($_POST['fecha']); 
//-------------
//if ((checkdate(mes(($fecha)),dia(($fecha)),anno(($fecha))))==1)
//	{
	$consulta_x = "SELECT * FROM poa_metas WHERE id='$id';";
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	if ($tabla_x->num_rows>0)
		{
		$registro = $tabla_x->fetch_object();
		$id_responsable = $registro->id_responsable;
		$codigo = $registro->codigo;
		$costo = formato_moneda($registro->costo);
		$meta = $registro->meta;
		$actividad = $registro->actividad;
		$indicador = $registro->indicador;
		$fecha = date('d/m/Y');
		}
	else
		{
		$tipo = 'alerta';
		$mensaje = 'Error al buscar la Información';
		}	
//	}
//else
//	{
//	$tipo = 'alerta';
//	$mensaje = 'Ingrese una fecha Correcta!';
//	}	
//-------------
$info = array ("tipo"=>$tipo, "id_responsable"=>$id_responsable, "codigo"=>$codigo, "costo"=>$costo, "meta"=>$meta, "actividad"=>$actividad, "indicador"=>$indicador, "fecha"=>$fecha, "msg"=>$mensaje);
echo json_encode($info);
?>