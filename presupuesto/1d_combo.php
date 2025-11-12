<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="">Todas las Actividades</option>
<?php
$anno = $_GET['anno'] ;
//--------------------
$consultx = "SELECT codigo, descripcion FROM a_presupuesto_$anno WHERE categoria is null AND left(a_presupuesto_$anno.codigo,8) <> '00000000' GROUP BY codigo;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->codigo;
	echo '" ';
	echo ' >';
	echo $registro_x->codigo . " - " . $registro_x->descripcion;
	echo '</option>';
	}
?>