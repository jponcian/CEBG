<?php

require __DIR__ . '/slider_rutinas.php';

//$rif = $_GET['rif'];

$sliders = new CrudAdminSlider();

echo $sliders->Listar();

?>