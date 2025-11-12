<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$oficina = $_GET['oficina'] ;
//--------------------
$consultx = "SELECT * FROM a_areas ORDER BY area;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	if ($oficina==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo ($registro_x->area);
	echo '</option>';
	}
?>