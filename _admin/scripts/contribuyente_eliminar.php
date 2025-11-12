<?php

$id = $_GET['id'];

require __DIR__ . '/contribuyente_rutinas.php';

$contribuyente = new CrudAdminContribuyente();

echo $contribuyente->Eliminar($id);
?>
