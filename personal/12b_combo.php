<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Todas</option>
<?php
$direccion = $_GET['id'] ;
//--------------------
$consultx = "SELECT * FROM a_areas WHERE id_direccion='$direccion' ORDER BY area;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	if ($direccion==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo ($registro_x->area);
	echo '</option>';
	}
?>