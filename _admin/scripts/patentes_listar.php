<?php

require __DIR__ . '/patentes_rutinas.php';

//$rif = $_GET['rif'];

$patentes = new CrudAdminPatentes();

echo $patentes->Listar();

?>