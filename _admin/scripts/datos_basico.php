<?php
$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente
$id = $data['tabla']['id'];

//require __DIR__ . '/contribuyentes_rutinas.php';

//$datos = new CrudAdminContribuyentes();

//echo $datos->DatosBasicos($id);
echo $id;

?>