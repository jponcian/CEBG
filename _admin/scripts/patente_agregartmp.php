<?php

require __DIR__ . '/actividades_rutinas.php';

$id_patente = $_GET['id_patente'];

$actividad = new CrudAdminActividades();

echo $actividad->AgregarDetalleTmpEditar($id_patente);

?>