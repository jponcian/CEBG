<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$numero = $data['registro']['numero'];
$fecha = getObtenerfecha($data['registro']['fecha']);
$fecha = date('Y-m-d', strtotime($fecha));
$vencimiento = getObtenerfecha($data['registro']['vencimiento']);
$vencimiento = date('Y-m-d', strtotime($vencimiento));
$descripcion = $data['registro']['descripcion'];
$direccion = $data['registro']['direccion'];
$representante = $data['registro']['representante'];
$cedula = $data['registro']['cedula'];
$obreros = $data['registro']['obreros'];
$empleados = $data['registro']['empleados'];
$turnos = $data['registro']['turnos'];
$manana = $data['registro']['manana'];
$tarde = $data['registro']['tarde'];
$nocturnos = $data['registro']['nocturnos'];
$talento_vivo = $data['registro']['talento_vivo'];
$rockola = $data['registro']['rockola'];
$otro = $data['registro']['otro'];
$rif = $data['registro']['rif'];
$usuario = $data['registro']['usuario'];

require __DIR__ . '/patentes_rutinas.php';

$patente = new CrudAdminPatentes();

echo $patente->Agregar($numero,$fecha,$descripcion,$direccion,$representante,$cedula,$vencimiento,$obreros,$empleados,$turnos,$manana,$tarde,$nocturnos,$talento_vivo,$rockola,$otro,$usuario,$rif);
//echo $fecha.' --- '.$vencimiento;

function getObtenerfecha($fecha)
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

