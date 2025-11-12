<?php
//-----------
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
setlocale(LC_TIME, 'sp_ES','sp', 'es');
$id = decriptar($_GET['id']);
//--------------
$consultx = "SELECT sum(nomina.total) as total, nomina_solicitudes.descripcion, nomina_solicitudes.desde, nomina_solicitudes.hasta FROM nomina_solicitudes , nomina , rac , ordenes_pago WHERE nomina.id_solicitud = nomina_solicitudes.id AND nomina_solicitudes.id_orden_pago = $id AND nomina.cedula = rac.cedula AND ordenes_pago.id = nomina_solicitudes.id_orden_pago;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$total = $registro->total;
$nombre = $registro->descripcion.' desde '.voltea_fecha($registro->desde).' al '.voltea_fecha($registro->hasta);
//----------------------
$consultx = "SELECT rac.cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, rac.cuenta, nomina.sueldo, nomina.asignaciones, nomina.descuentos, nomina.total FROM nomina_solicitudes , nomina , rac , ordenes_pago WHERE nomina.id_solicitud = nomina_solicitudes.id AND nomina_solicitudes.id_orden_pago = $id AND nomina.cedula = rac.cedula AND ordenes_pago.id = nomina_solicitudes.id_orden_pago;"; 
$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
//----------------------
$file = fopen("../archivo.txt", "w");
$titulo = "ONTNOMG2000128700000".rellena_cero($cantidad,3).rellena_cero(str_replace('.','',$total),15)."VES".date('Ymd');
fwrite($file, $titulo);
while ($registro = $tablx->fetch_object())
	{
	$linea = '0'.$registro->cuenta.'0'.rellena_cero(str_replace('.','',$registro->total),10).'0770'.(str_pad(str_replace('Ñ','N',utf8_decode($registro->nombre)),40)).'0'.rellena_cero(str_replace('V','',$registro->cedula),10).'03291';
	fwrite($file, "\r\n");
	fwrite($file, $linea);
	}
fclose($file);
//-----------
$archivo = '../archivo.txt';
$texto = file_get_contents($archivo);
$texto = iconv("UTF-8", "WINDOWS-1252", $texto);
file_put_contents($archivo, $texto);
//-----------
//ob_clean();
header("Content-Description: Descargar TXT");
header("Content-Disposition: attachment; filename=$nombre.txt");
header("Content-Type: application/force-download");
header("Content-Length: " . filesize($archivo));
header("Content-Transfer-Encoding: binary");
readfile($archivo);
//----------- 
?>