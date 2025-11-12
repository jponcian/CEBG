<?php

$id = $_GET['id'];

require __DIR__ . '/articulos_rutinas.php';

$eliminar_articulo = new CrudAdminArticulos();

echo $eliminar_articulo->Eliminar($id);
?>
