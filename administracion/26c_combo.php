<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$id = $_GET['id'] ;
$valor = explode("-",$id);
$id = $valor[0];
$ci = $valor[1];
//--------------------
$consultx = "SELECT cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre FROM rac ORDER BY (cedula * 1);";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->cedula;
	echo '" ';
	if ($ci==$registro_x->cedula) {echo 'selected="selected"';}
	echo ' >';
	echo rellena_cero($registro_x->cedula,8) . " - " . $registro_x->nombre;
	echo '</option>';
	}
?>