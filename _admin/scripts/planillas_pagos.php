<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$id_pago = $data['registro']['id_pago']; 
$id_banco = $data['registro']['id_banco']; 
$id_planilla = $data['registro']['id_planilla'];
$fecha = Obtenerfecha($data['registro']['fecha']);
$referencia = $data['registro']['referencia'];
$monto_pago = $data['registro']['monto_pago'];
$monto_planilla = $data['registro']['monto_planilla'];
$usuario = $data['registro']['usuario'];

require __DIR__ . '/declaraciones_rutinas.php';

$planillas = new CrudAdminDeclaraciones();

echo $planillas->ConfirmarPago($id_banco, $id_planilla, $fecha, $referencia, $monto_pago, $monto_planilla, $usuario, $id_pago);
//echo $id_planilla;

function Obtenerfecha($fecha)
{
	$fecha = str_replace('/', '-', $fecha);
	$fecha = explode("-",$fecha);
	$dia = $fecha[0];
	$dia = str_pad($dia, 2, "0", STR_PAD_LEFT);
	$mes = $fecha[1];
	$mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
	$anio = $fecha[2];
	$fecha = $anio.'-'.$mes.'-'.$dia; 
	return $fecha;
}

?>

