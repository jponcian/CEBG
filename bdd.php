<?php
session_start();
//--------
$tipo = 'info';
//-------------
$_SESSION['bdd'] = 'prueba';
//-------------
$info = array ("tipo"=>$tipo);
echo json_encode($info);
?>