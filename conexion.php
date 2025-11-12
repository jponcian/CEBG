<?php 	
//session_start();
date_default_timezone_set('America/Caracas');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
error_reporting(0);
//--------------
if ($_SESSION['bdd']=='prueba')
	{$_SESSION['conexionsql'] = new mysqli ("localhost", "CEBG", "Ponciano2021.", "javier_ponciano_3");}
else
	{$_SESSION['conexionsql'] = new mysqli ("localhost", "CEBG", "Ponciano2021.", "javier_ponciano_2");}
$_SESSION['conexionsql']->query("SET NAMES 'utf8'");
//mysql.zz.com.ve Miranda Ponciano2021.
	
//-------PARA GUARDAR EL RECORRIDO
//$valor = print_r($_SESSION,true);	
//$consulta_zzz = "INSERT INTO bitacora (usuario, variables, direccionurl, ip) VALUES (".$_SESSION['CEDULA_USUARIO'].", '$valor','".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."', '".$_SERVER['REMOTE_ADDR']."');";
//echo $consulta_zzz;
//$tabla_zzz = $_SESSION['conexionsql']->query($consulta_zzz);
//----------
$ip = rand(1, PHP_INT_MAX);
//echo $ip ;
//$ip = getRealIP();
//function getRealIP()
//{
//
//    if (isset($_SERVER["HTTP_CLIENT_IP"]))
//    {
//        return $_SERVER["HTTP_CLIENT_IP"];
//    }
//    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
//    {
//        return $_SERVER["HTTP_X_FORWARDED_FOR"];
//    }
//    elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
//    {
//        return $_SERVER["HTTP_X_FORWARDED"];
//    }
//    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
//    {
//        return $_SERVER["HTTP_FORWARDED_FOR"];
//    }
//    elseif (isset($_SERVER["HTTP_FORWARDED"]))
//    {
//        return $_SERVER["HTTP_FORWARDED"];
//    }
//    else
//    {
//        return $_SERVER["REMOTE_ADDR"];
//    }
//
//}
?>