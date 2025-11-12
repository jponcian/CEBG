<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$rif = strtoupper($data['registro']['rif']);
$nombre = strtoupper($data['registro']['nombre']);
$domicilio = strtoupper($data['registro']['direccion']);
$ciudad = strtoupper($data['registro']['ciudad']);
$estado = strtoupper($data['registro']['estado']);
$parroquia = strtoupper($data['registro']['zona']);
$representante = strtoupper($data['registro']['representante']);
$ced_representante = $data['registro']['cedula'];
$cel_contacto = $data['registro']['celular'];
$email = strtoupper($data['registro']['email']);
$usuario = $_GET['usuario'];

require __DIR__ . '/contribuyente_rutinas.php';

$contribuyente = new CrudAdminContribuyente();

echo $contribuyente->Agregar($rif,$nombre,$domicilio,$ciudad,$estado,$parroquia,$representante,$ced_representante,$cel_contacto,$email,$usuario);

?>

