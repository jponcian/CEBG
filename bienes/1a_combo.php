<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Todas las Areas</option>
<?php
$division = $_GET['division'] ;
//--------------------
$consultx = "SELECT bn_dependencias.* FROM bn_bienes,	bn_dependencias WHERE bn_bienes.id_dependencia = bn_dependencias.id AND bn_dependencias.id=$division GROUP BY bn_dependencias.id ORDER BY bn_dependencias.id";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" >';
	echo $registro_x->division;
	echo '</option>';
	}
?>