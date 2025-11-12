<?php

require __DIR__ . '/solicitudes_rutinas.php';

//$rif = $_GET['rif'];

$solicitudes = new CrudSolicitudes();

echo $solicitudes->Listar();

?>