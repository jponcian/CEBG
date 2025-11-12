<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$categoria = $_GET['categoria'] ;
$fecha = $_GET['fecha'] ;
$partida = $_GET['partida'] ;
$numero = $_GET['numero'] ;
//--------------------
$consultx = "SELECT * FROM a_presupuesto_".anno(voltea_fecha($fecha))." WHERE categoria='$categoria' AND CONCAT(categoria, codigo) not in (SELECT CONCAT(categoria,partida) FROM credito_adicional_detalle WHERE numero = '$numero' AND anno = '".anno(voltea_fecha($fecha))."') ORDER BY codigo;";

$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->codigo;
	echo '" ';
	if ($partida==$registro_x->codigo) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->codigo . " - " . $registro_x->descripcion;
	echo '</option>';
	}
?>