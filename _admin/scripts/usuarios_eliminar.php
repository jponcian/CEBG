<?php

$id = $_GET['id'];

require __DIR__ . '/usuarios_rutinas.php';

$tabla_usuario = new CrudAdminUsuarios();

echo $tabla_usuario->Eliminar($id);
?>
