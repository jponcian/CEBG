<?php
$data = json_decode(file_get_contents('php://input'), TRUE);

$numero = $data['pagos']['numero'];
//$numero = $_POST['numero'];

require __DIR__ . '/declaraciones_rutinas.php';

$pagos = new CrudAdminDeclaraciones();

echo $pagos->ListarPagosAll($numero);
//echo $numero;

?>