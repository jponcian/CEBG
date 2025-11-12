<?php
//sleep(10);

require __DIR__ . '/articulos_rutinas.php';

//$rif = $_GET['rif'];

$articulos = new CrudAdminArticulos();

echo $articulos->Listar();

?>