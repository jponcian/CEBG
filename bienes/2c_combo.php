<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$origen = $_GET['origen'] ;
//--------------------
$consult = "SELECT * FROM a_areas WHERE id<>$origen and id_direccion IN (SELECT id_direccion FROM a_areas WHERE id=$origen) ORDER BY area;";// WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	if ($partida==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->area;
	echo '</option>';
	}
?>