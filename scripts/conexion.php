<?php
//session_start();
setlocale(LC_ALL, 'sp_ES', 'sp', 'es');
date_default_timezone_set('America/Caracas');
error_reporting(0);
/*
define('HOST', 'localhost'); // Host de la base de datos
define('USER', 'root'); // Usuario
define('PASSWORD', ''); // Contraseña
define('DATABASE', 'sigemdb'); // Nombre de Base de Datos
*/
define('HOST', 'localhost'); // Host de la base de datos
define('USER', 'CEBG'); // Usuario
define('PASSWORD', 'Ponciano2021.'); // Contraseña
define('DATABASE', 'javier_ponciano_2'); // Nombre de Base de Datos


function DB()
{
    static $instance;
    if ($instance === null) {
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => FALSE,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        );
        $dsn = 'mysql:host=' . HOST . ';dbname=' . DATABASE;
        $instance = new PDO($dsn, USER, PASSWORD, $opt);
    }
    return $instance;
}