<?php

$id = $_GET['id'];

require __DIR__ . '/slider_rutinas.php';

$eliminar_slider = new CrudAdminSlider();

echo $eliminar_slider->Eliminar($id);
?>
