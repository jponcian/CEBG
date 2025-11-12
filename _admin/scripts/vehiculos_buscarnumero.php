<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$numero = $data['registro']['numero'];

require __DIR__ . '/vehiculos_rutinas.php';

$vehiculo = new CrudAdminVehiculos();

echo $vehiculo->buscarNumero($numero);

?>