<?php
$data = json_decode(file_get_contents('php://input'), TRUE);

$numero = $data['patente']['numero'];

require __DIR__ . '/contribuyente_rutinas.php';

$contribuyente = new CrudAdminContribuyente();

echo $contribuyente->BuscarIdPatente($numero);

?>
