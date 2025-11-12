<?php

$id = $_GET['id'];

require __DIR__ . '/actividades_rutinas.php';

$actividad = new CrudAdminActividades();

echo $actividad->Eliminar($id);
?>
