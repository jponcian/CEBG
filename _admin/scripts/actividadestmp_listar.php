<?php
$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente
$id_patente = $data['registro']['id_patente'];
$numero = $data['registro']['numero'];

require __DIR__ . '/actividades_rutinas.php';

$actividad = new CrudAdminActividades();

echo $actividad->ListarTmp($id_patente, $numero);

?>