<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$origen = $_GET['origen'] ;
//--------------------
$consult = "SELECT * FROM bn_materiales WHERE id_bien NOT IN (SELECT id_bien FROM bn_ingresos_detalle WHERE estatus = '0' ) ORDER BY descripcion_bien;"; 
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo encriptar($registro_x->id_bien);
	echo '" ';
//	if ($partida==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->descripcion_bien;
	echo '</option>';
	}
?>