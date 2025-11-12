<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$id = $data['registro']['id'];
$numero = $data['registro']['numero'];
$fecha = getObtenerfecha($data['registro']['fecha']);
$fecha = date('Y-m-d', strtotime($fecha));
$descripcion = $data['registro']['descripcion'];
$direccion = $data['registro']['direccion'];
$representante = $data['registro']['representante'];
$cedula = $data['registro']['cedula'];
$vencimiento = getObtenerfecha($data['registro']['vencimiento']);
$vencimiento = date('Y-m-d', strtotime($vencimiento));
$obreros = $data['registro']['obreros'];
$estatus = $data['registro']['estatus'];
$cierre_tmp = getObtenerfecha($data['registro']['cierre_tmp']);
//$cierre_tmp = date('Y-m-d', strtotime($cierre_tmp));
$cierre_def = getObtenerfecha($data['registro']['cierre_def']);
//$cierre_def = date('Y-m-d', strtotime($cierre_def));
$empleados = $data['registro']['empleados'];
$turnos = $data['registro']['turnos'];
$manana = $data['registro']['manana'];
$tarde = $data['registro']['tarde'];
$nocturnos = $data['registro']['nocturno'];
$talento_vivo = $data['registro']['talento_vivo'];
$rockola = $data['registro']['rockola'];
$otro = $data['registro']['otro'];
$rif = $data['registro']['rif'];
$usuario = $data['registro']['usuario'];

require __DIR__ . '/patentes_rutinas.php';

$patentes = new CrudAdminPatentes();

echo $patentes->Editar($id,$numero,$fecha,$descripcion,$direccion,$representante,$cedula,$vencimiento,$obreros,$empleados,$turnos,$manana,$tarde,$nocturnos,$talento_vivo,$rockola,$otro,$usuario,$rif,$estatus,$cierre_tmp,$cierre_def);
//echo $id.' - '.$numero.' - '.$fecha.' - '.$descripcion.' - '.$direccion.' - '.$representante.' - '.$cedula.' - '.$vencimiento.' - '.$obreros.' - '.$empleados.' - '.$turnos.' - '.$manana.' - '.$tarde.' - '.$nocturnos.' - '.$talento_vivo.' - '.$rockola.' - '.$otro.' - '.$usuario.' - '.$rif;
//echo 'Valores: '.$estatus.' --- '.$cierre_tmp.' --- '.$cierre_def;

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
//--------------
//function voltea_fec($a)
//	{
//	if ($a=='') 
//		{
//		if (substr($a,2,1)=='-' or substr($a,2,1)=='/')	{ return '00/00/0000'; }
//		else 
//			{ 
//			if (substr($a,4,1)=='-' or substr($a,4,1)=='/')	{ return '00/00/0000'; }
//				else
//					{ return '0000/00/00'; }
//			}
//		//-----------
//		}
//	else
//		{
//		if (substr($a,2,1)=='-' or substr($a,4,1)=='-')	{$caracter='-';}
//			else {$caracter='/';}
//		//-----------
//		$a = explode($caracter,$a);
//		$aux = $a[2];
//		$a[2] = $a[0];
//		$a[0] = $aux;
//		$caracter='/';
//		return implode($caracter,$a);
//		}
//	}
//--------------
?>