<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$nombre = $data['tabla']['nombre'];
$estado = $data['tabla']['estado'];

require __DIR__ . '/contribuyente_rutinas.php';

$zonificacion = new CrudAdminContribuyente();

echo $zonificacion->ListarZonificacion($nombre, $estado);
//echo $nombre.' - '.$estado;

?>

