<?php
//session_start();
$data = json_decode(file_get_contents('php://input'), TRUE);

$rif = $data['usuario']['rif'];

require __DIR__ . '/rutinas_usuarios.php';

$usuario = new CrudUsuarios();

echo $usuario->recuperarUsuario($rif);
//echo 'Numero id enviado: '.$id;

?>