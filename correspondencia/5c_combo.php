<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$origen = $_GET['origen'] ;
//--------------------
$consult = "SELECT * FROM a_direcciones WHERE id<>$origen AND id<99 ORDER BY direccion;";// WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	echo ' >';
	echo $registro_x->direccion;
	echo '</option>';
	}
?>