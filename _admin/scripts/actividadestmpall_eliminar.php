<?php

require __DIR__ . '/actividades_rutinas.php';

$numero = $_GET['numero'];
$usuario = $_GET['usuario'];

$actividad = new CrudAdminActividades();

echo $actividad->EliminarTmpAll($numero, $usuario);

?>