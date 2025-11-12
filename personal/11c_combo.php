<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<?php
$categoria = $_GET['categoria'] ;
$fecha = $_GET['fecha'] ;
$partida = $_GET['partida'] ;
//--------------------
$consultx = "SELECT *, left(descripcion,50) as partida FROM a_presupuesto_".anno(voltea_fecha($fecha))." WHERE (left(trim(codigo),6)='000000' or left(trim(codigo),6)='401079') AND categoria='$categoria' ORDER BY codigo;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->codigo;
	echo '" ';
	if ($partida==$registro_x->codigo) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->codigo. " - " . $registro_x->partida ;//
	echo '</option>';
	}
?>