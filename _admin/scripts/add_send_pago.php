<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$numeropatente = $data['sendpago']['numeropatente'];
$id_formapago = $data['sendpago']['formapago'];
$referencia = $data['sendpago']['referencia'];
$fecha_pago = Obtenerfecha($data['sendpago']['fechapago']);
$id_bancoorigen = $data['sendpago']['bancoorigen'];
$id_bancodestino = $data['sendpago']['bancodestino'];
$monto_pagado = $data['sendpago']['montopago'];
$detalle_planilla = $data['sendpago']['observacion'];
$usuario = $data['sendpago']['usuario'];

require __DIR__ . '/declaraciones_rutinas.php';

$envias_pago = new CrudAdminDeclaraciones();

echo $envias_pago->CreateEnviarPago($numeropatente, $id_formapago, $referencia, $fecha_pago, $id_bancoorigen, $id_bancodestino, $monto_pagado, $detalle_planilla, $usuario);
//echo $id_patente.'  -  '.$id_formapago.'  -  '.$referencia.'  -  '.$fecha_pago.'  -  '.$id_bancoorigen.'  -  '.$id_bancodestino.'  -  '.$monto_pagado.'  -  '.$detalle_planilla.'  -  '.$usuario;


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