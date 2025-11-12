<?php

require __DIR__ . '/vehiculos_rutinas.php';

//$rif = $_GET['rif'];

$vehiculos = new CrudAdminVehiculos();

echo $vehiculos->Listar();

?>