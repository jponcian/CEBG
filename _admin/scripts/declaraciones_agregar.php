<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$codigo = strtoupper($data['registro']['codigo']);
$descripcion = strtoupper($data['registro']['descripcion']);
$tasa = $data['registro']['tasa'];
$usuario = $_GET['usuario'];

require __DIR__ . '/actividades_rutinas.php';

$actividad = new CrudAdminActividades();

echo $actividad->Agregar($codigo,$descripcion,$tasa,$usuario);

?>

