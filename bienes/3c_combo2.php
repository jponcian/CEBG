<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$origen = $_GET['origen'] ;
if ($origen==3)
	{
	//--------------------
	$consult = "SELECT * FROM bn_dependencias WHERE id<>$origen ORDER BY division;";// WHERE id_direccion='$desde'
	$tablx = $_SESSION['conexionsql']->query($consult);
	while ($registro_x = $tablx->fetch_object())
	//-------------
		{
		echo '<option value="';
		echo $registro_x->id;
		echo '" ';
		if ($partida==$registro_x->id) {echo 'selected="selected"';}
		echo ' >';
		echo ($registro_x->codigo).' '.$registro_x->division;
		echo '</option>';
		}
	}
else
	{
	//--------------------
	$consult = "SELECT * FROM bn_dependencias WHERE id<>$origen ORDER BY division;";//id=3  WHERE id_direccion='$desde'
	$tablx = $_SESSION['conexionsql']->query($consult);
	while ($registro_x = $tablx->fetch_object())
	//-------------
		{
		echo '<option value="';
		echo $registro_x->id;
		echo '" ';
		if ($partida==$registro_x->id) {echo 'selected="selected"';}
		echo ' >';
		echo ($registro_x->codigo).' '.$registro_x->division;
		echo '</option>';
		}
	}
	
?>