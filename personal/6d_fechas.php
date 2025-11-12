<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$fecha = voltea_fecha(extrae_fecha($_POST['OINICIO']));
$fecha2 = strtotime(voltea_fecha(extrae_fecha($_POST['OFIN'])));
//----------------
$datestart= strtotime($fecha);
$continuos = -1;
$habiles = -1;
while ($datestart <= $fecha2)
	{
	$continuos++;
	$datestart = $datestart + 86400;
	$diasemana = date('N',$datestart);
	if ($diasemana<>0 and $diasemana<>7)
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
//----------------
if ($continuos>=0)
	{	$mensaje = "Fechas Calculadas Exitosamente!";	}
else
	{	$mensaje = "Error en fechas!";		}//$tipo = 'alerta';

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "continuos"=>$continuos, "habiles"=>$habiles);

echo json_encode($info);
?>