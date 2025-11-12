<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$id = $data['registro']['id_actividad'];
$id_patente = $data['registro']['id_patente'];
$numero = $data['registro']['numero'];
$usuario = $data['registro']['usuario'];

require __DIR__ . '/patentes_rutinas.php';

$patente = new CrudAdminPatentes();

echo $patente->EditarDetalleTmp($id, $id_patente, $numero, $usuario);

?>

