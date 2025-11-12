<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione el Número de Cédula</option>
<?php
//--------------------
$consultx = "SELECT * FROM rac_visita ORDER BY cedula;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	$i++;
	echo '<option value="';
	echo encriptar($registro_x->cedula);
	echo '" ';
	echo ' >';
	echo $registro_x->cedula. " - " . $registro_x->nombre;
	echo '</option>';
	}
?>