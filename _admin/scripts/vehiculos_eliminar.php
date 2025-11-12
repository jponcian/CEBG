<?php

$id = $_GET['id'];

require __DIR__ . '/vehiculos_rutinas.php';

$vehiculo = new CrudAdminVehiculos();

echo $vehiculo->Eliminar($id);
?>