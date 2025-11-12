<?php

require __DIR__ . '/declaraciones_rutinas.php';

$pagos = new CrudAdminDeclaraciones();

echo $pagos->ListarPagosEnviados();
//echo $id;

?>