<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione el Motivo</option>
<?php
$destino = $_GET['destino'] ;
//--------------------
$consult = "SELECT * FROM a_motivo_bienes WHERE codigo='51';"; //WHERE id_dependencia=$origen AND por_reasignar=0
if ($destino==19)
	{
	$consult = "SELECT * FROM a_motivo_bienes ORDER BY codigo;"; //WHERE id_dependencia=$origen AND por_reasignar=0
	}
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo ($registro_x->codigo);
	echo '" ';
//	if ($partida==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->codigo .' - '. $registro_x->motivo;
	echo '</option>';
	}
?>