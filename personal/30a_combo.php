<?php
session_start();
include_once "../conexion.php";
include_once( '../funciones/auxiliar_php.php' );
//--------------------
$cedula = decriptar($_GET[ 'cedula' ]);
//--------------------
//$consultx = "SELECT desde, hasta FROM nomina_solicitudes WHERE (tipo_pago='001' or tipo_pago='002' or tipo_pago='003') AND estatus>=7 AND estatus<=10 GROUP BY hasta ORDER BY desde DESC, hasta DESC;"; //estatus AND 
$consultx = "SELECT nomina_solicitudes.desde, nomina_solicitudes.hasta FROM nomina_solicitudes, nomina WHERE (nomina_solicitudes.tipo_pago='001' or nomina_solicitudes.tipo_pago='002' or nomina_solicitudes.tipo_pago='003') AND nomina_solicitudes.estatus>=7 AND nomina_solicitudes.estatus<=10 AND nomina.id_solicitud=nomina_solicitudes.id and nomina.cedula=$cedula GROUP BY nomina_solicitudes.hasta ORDER BY nomina_solicitudes.desde DESC, nomina_solicitudes.hasta DESC;"; //estatus AND 
$tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
while ( $registro_x = $tablx->fetch_array() ) {
  echo '<option value="' . encriptar( $registro_x[ 'hasta' ] ) . '">' . voltea_fecha( $registro_x[ 'desde' ] ) . ' al ' . voltea_fecha( $registro_x[ 'hasta' ] ) . '</option>';
}
?>