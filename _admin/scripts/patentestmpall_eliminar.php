<?php

require __DIR__ . '/patentes_rutinas.php';

$numero = $_GET['numero'];
$usuario = $_GET['usuario'];

$patente = new CrudAdminPatentes();

echo $patente->EliminarTmpAll($numero, $usuario);

?>