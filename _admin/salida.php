<?php 	
session_start();
include_once "conexion.php";
$_SESSION['VERIFICADO'] = 'NO';
mysqli_close($_SESSION['conexionsql']);
session_destroy();
$info = array ("tipo"=>'info');
echo json_encode($info);
?>