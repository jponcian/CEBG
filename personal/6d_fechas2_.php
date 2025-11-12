<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$fecha = voltea_fecha($_POST['txt_desde']);
//----------------
$datestart= strtotime($fecha);
$continuos = 1;
$habiles = 1;
while ($habiles < 30)
	{
	$continuos++;
	$datestart = $datestart + 86400;
	$diasemana = date('N',$datestart);
	if ($diasemana<>6 and $diasemana<>7)
		{
		//BUSCAMOS SI ES DIA FERIADO
		$consultax = "SELECT dia as fecha FROM rrhh_dias_feriados WHERE fecha='".date("Y-m-d", $datestart)."'";
		$tablax = $_SESSION['conexionsql']->query($consultax);
		if ($tablax->num_rows==0)
			{
			$habiles++;
			}
		}
	}
$fin = date("Y-m-d", $datestart);
//----------
while ($habiles < 31)
	{
	$continuos++;
	$datestart = $datestart + 86400;
	$diasemana = date('N',$datestart);
	if ($diasemana<>6 and $diasemana<>7)
		{
		//BUSCAMOS SI ES DIA FERIADO
		$consultax = "SELECT dia as fecha FROM rrhh_dias_feriados WHERE fecha='".date("Y-m-d", $datestart)."'";
		$tablax = $_SESSION['conexionsql']->query($consultax);
		if ($tablax->num_rows==0)
			{
			$habiles++;
			}
		}
	}
$incorporacion = date("d-m-Y", $datestart);
//$incorporacion = $diasemana.' '.date("d-m-Y", $datestart);
//----------------
if ($continuos>0)
	{	$mensaje = "Fechas Calculadas Exitosamente!";	}
else
	{	$mensaje = "Error en fechas!";		}//$tipo = 'alerta';

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "incorporacion"=>($incorporacion), "fin"=>voltea_fecha($fin), "continuos"=>$continuos-1, "habiles"=>$habiles-1);

echo json_encode($info);
?>