<?php session_start(); 
include_once('../funciones/auxiliar_php.php');
$_SESSION['variable'] = trim($_POST['valor']); 
$_SESSION['fecha1'] = trim($_POST['fecha1']);
$_SESSION['fecha2'] = trim($_POST['fecha2']);
$_SESSION['cajero'] = trim($_POST['cajero']); 
//------------
$_SESSION['anno'] = trim($_POST['oanno']); 
$_SESSION['categoria'] = trim($_POST['ocategoria']); 
$_SESSION['partida'] = trim($_POST['opartida']);
$_SESSION['resumen'] = trim($_POST['oresumen']);
//------------
if (trim($_POST['monto'])>0)	{$_SESSION['monto'] = formato_moneda(trim($_POST['monto']));}
?>