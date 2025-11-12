<?php

require __DIR__ . '/rutinas_declaracion.php';


$empresa = new CrudDeclaracion();

echo $empresa->DatosSistema();
//echo $_SESSION['id_usuario'];

?>