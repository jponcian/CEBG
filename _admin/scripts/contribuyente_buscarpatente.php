<?php

$patente = $_GET['patente'];

require __DIR__ . '/contribuyente_rutinas.php';

$contribuyente = new CrudAdminContribuyente();

echo $contribuyente->BuscarPatente($patente);

?>
