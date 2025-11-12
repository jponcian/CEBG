<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
$fecha = voltea_fecha($_POST['txt_desde']);
$hasta = voltea_fecha($_POST['txt_hasta']);
//----------------
$datestart= strtotime($fecha);
$dateend= strtotime($hasta);
$continuos = 1;
$habiles = 1;
//----------
$siguiente = 'no';
while ($siguiente == 'no')
	{
	$continuos++;
	$datestart = $datestart + 86400;
	$diasemana = date('N',$datestart);
//	echo '<br>continuo '.date("d-m-Y", $datestart).' '.date('N',$datestart);
	if ($diasemana<>6 and $diasemana<>7)
		{
		//BUSCAMOS SI ES DIA FERIADO
		$consultax = "SELECT dia as fecha FROM rrhh_dias_feriados WHERE fecha='".date("Y-m-d", $datestart)."'";
		$tablax = $_SESSION['conexionsql']->query($consultax);
		if ($tablax->num_rows==0)
			{
			$habiles++;
			if (($datestart > $dateend))
				{	$siguiente = 'si';	}
//			echo '<br>habil '.date("d-m-Y", $datestart).' '.date('N',$datestart);
			}
		}
	}
$incorporacion = date("d-m-Y", $datestart);
//----------------
$info = array ("tipo"=>$tipo, "incorporacion"=>($incorporacion), "continuos"=>$continuos-1, "habiles"=>$habiles-1);

echo json_encode($info);
?>