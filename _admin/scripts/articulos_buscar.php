<?php

$id = $_GET['id'];

require __DIR__ . '/articulos_rutinas.php';

$buscar_articulo = new CrudAdminArticulos();

echo $buscar_articulo->Buscar($id);
?>
