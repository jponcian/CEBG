<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$nombre = $data['registro']['nombre_usuario'];
$user = $data['registro']['user'];
$password = $data['registro']['password'];
$email = $data['registro']['email'];
$acceso = $data['registro']['tipo_acceso'];
$usuario = $data['registro']['usuario'];

require __DIR__ . '/usuarios_rutinas.php';

$tabla_usuario = new CrudAdminUsuarios();

echo $tabla_usuario->Agregar($nombre, $user, $password, $email, $acceso, $usuario);
//echo $nombre.' - '.$user.' - '.$password.' - '.$email.' - '.$acceso.' - '.$usuario;


?>

