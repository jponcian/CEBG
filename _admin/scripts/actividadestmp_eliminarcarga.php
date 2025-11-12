<?php

require __DIR__ . '/actividades_rutinas.php';

$usuario = $_GET['usuario'];

$actividad = new CrudAdminActividades();

echo $actividad->EliminarTmpCarga($usuario);

?>