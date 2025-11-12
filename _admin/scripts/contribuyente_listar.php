<?php

require __DIR__ . '/contribuyente_rutinas.php';

//$rif = $_GET['rif'];

$sliders = new CrudAdminContribuyente();

echo $sliders->Listar();

?>