<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Todas las Areas</option>
<?php
$division = $_GET['division'] ;
//--------------------
$consultx = "SELECT a_areas.* FROM bn_bienes, a_areas,	a_direcciones WHERE bn_bienes.id_area = a_areas.id AND a_areas.id_direccion = a_direcciones.id AND a_direcciones.id=$division GROUP BY a_direcciones.id ORDER BY a_direcciones.id";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" >';
	echo $registro_x->area;
	echo '</option>';
	}
?>