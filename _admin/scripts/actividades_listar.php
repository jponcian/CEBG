<?php

require __DIR__ . '/actividades_rutinas.php';

//$rif = $_GET['rif'];

$actividad = new CrudAdminActividades();

echo $actividad->Listar();

?>