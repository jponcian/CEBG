<?php
//sleep(10);
require_once __DIR__ . '/funciones.php';

$passw = $_GET['passw'];

$cadena = decrypt($passw);

$datos = array(
	"permitido" => true,
	"clave" => $cadena
);

echo json_encode($datos);

?>