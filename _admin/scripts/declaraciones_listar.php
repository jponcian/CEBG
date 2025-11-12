<?php

require __DIR__ . '/declaraciones_rutinas.php';

$id = $_GET['id'];

$planillas = new CrudAdminDeclaraciones();

echo $planillas->Listar($id);
//echo $id;

?>