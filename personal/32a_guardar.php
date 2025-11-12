<?php
session_start();
include_once "../conexion.php";
include_once( '../funciones/auxiliar_php.php' );
//--------
$info = array();
$tipo = 'info';
//-------------	
$consultx = "SELECT * FROM a_asignaciones";
$tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
while ( $registro_x = $tablx->fetch_array() ) {
  $consultx = "UPDATE a_asignaciones SET activo=0" . $_POST[ 'txt' . ( $registro_x[ 'id' ] ) ] . " WHERE id=" . $registro_x[ 'id' ] . ";";
  $act = $_SESSION[ 'conexionsql' ]->query( $consultx );
}
//-------------	
$mensaje = "Registro Actualizado Exitosamente!";
//-------------
$info = array( "tipo" => $tipo, "msg" => $mensaje, "consulta" => $consultx );
echo json_encode( $info );
?>