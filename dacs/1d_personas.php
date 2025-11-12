<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$consultx = "SELECT cedula FROM asistencia_diaria_visita WHERE id_direccion=4 AND fecha='".date('Y/m/d')."' AND estatus=0;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{
	if ($tablx->num_rows==1)	
		{
		echo 'ACTUALMENTE HAY ' . $tablx->num_rows. ' PERSONA EXTERNA EN LA DIRECCIÓN';
		}	
	else
		{
		echo 'ACTUALMENTE HAY ' . $tablx->num_rows. ' PERSONAS EXTERNAS EN LA DIRECCIÓN';
		}	
	}
else 
	{
	echo 'ACTUALMENTE NO HAY PERSONAS EXTERNAS EN LA DIRECCIÓN';
	}
?>