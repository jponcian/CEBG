<?php

require __DIR__ . '/usuarios_rutinas.php';

//$rif = $_GET['rif'];

$tabla_usuario = new CrudAdminUsuarios();

echo $tabla_usuario->Listar();

?>