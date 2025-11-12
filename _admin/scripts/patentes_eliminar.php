<?php

$id = $_GET['id'];

require __DIR__ . '/patentes_rutinas.php';

$patente = new CrudAdminPatentes();

echo $patente->Eliminar($id);
?>
