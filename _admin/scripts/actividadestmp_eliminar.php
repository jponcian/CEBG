<?php

require __DIR__ . '/actividades_rutinas.php';

$id = $_GET['id'];
$numero = $_GET['numero'];
$usuario = $_GET['usuario'];

$actividad = new CrudAdminActividades();

echo $actividad->EliminarTmp($id,$numero,$usuario);

?>