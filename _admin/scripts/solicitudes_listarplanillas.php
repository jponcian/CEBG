<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$id = $data['registro']['id_contribuyente'];

require __DIR__ . '/solicitudes_rutinas.php';

$solicitud = new CrudSolicitudes();

echo $solicitud->listarPlanillas($id);
//echo $id;
?>