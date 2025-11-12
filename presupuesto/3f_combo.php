<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$anno = anno(voltea_fecha($_GET['fecha'])) ;
//--------------------
$consultx = "SELECT a_presupuesto_$anno.descripcion, codigo FROM a_presupuesto_$anno WHERE categoria is null and descripcion<>'PATRIA' ORDER BY codigo;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->codigo;
	echo '" >';
	echo $registro_x->codigo . " - " . $registro_x->descripcion;
	echo '</option>';
	}
?>