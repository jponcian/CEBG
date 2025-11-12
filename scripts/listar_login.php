<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$userid = $data['producto']['userid'];
$passw = $data['producto']['passw'];
//($data['producto']['nombre']

require __DIR__ . '/rutinas_login.php';

$login = new Crud();

echo $login->Read($userid, $passw);

?>