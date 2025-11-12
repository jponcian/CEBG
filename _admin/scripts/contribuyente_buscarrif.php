<?php

$rif = $_GET['rif'];

require __DIR__ . '/contribuyente_rutinas.php';

$contribuyente = new CrudAdminContribuyente();

echo $contribuyente->BuscarRif($rif);

?>
