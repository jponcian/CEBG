<?php
session_start();
include_once "conexion.php";
$consulta_x = "UPDATE usuarios SET sesion = 0 WHERE user = '" . $_SESSION['USER'] . "';";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
$_SESSION['VERIFICADO'] = 'NO';
mysqli_close($_SESSION['conexionsql']);
session_destroy();
$info = array("tipo" => 'info');
echo json_encode($info);
