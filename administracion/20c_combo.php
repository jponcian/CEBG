<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------------------
$categoria = $_GET['categoria'] ;
$anno = $_GET['anno'] ;
//--------------------
$consultx = "SELECT codigo, descripcion FROM a_presupuesto_$anno WHERE categoria='$categoria' ORDER BY codigo;"; //AND left(trim(codigo),3)<>'401' 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->codigo;
	echo '" ';
	//if ($partida==$registro_x->codigo) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->codigo;
	echo '</option>';
	}
?>