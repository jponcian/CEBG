<?php
$data = json_decode(file_get_contents('php://input'), TRUE);

$id_declaracion = $data['pago']['id_declaracion'];
$id_planilla = $data['pago']['id_planilla'];
$id = $data['pago']['id'];
$accion = $data['pago']['accion'];
$usuario = $data['pago']['usuario'];
$origen = $data['pago']['origen'];

require __DIR__ . '/declaraciones_rutinas.php';

$pagos = new CrudAdminDeclaraciones();

echo $pagos->ActualizarEstatusPago($id_declaracion, $id_planilla, $id, $accion, $usuario, $origen);
//echo $id_declaracion.' - '.$id_planilla.' - '.$id.' - '.$accion.' - '.$usuario.' - '.$origen;
?>