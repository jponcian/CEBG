<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<!--<option value="999" >DOZAVO (Todas)</option>-->
<?php
$categoria = $_GET['categoria'] ;
$fecha = $_GET['fecha'];
$partida = $_GET['partida'] ;
//--------------------
$consultx = "SELECT * FROM a_presupuesto_".anno(voltea_fecha($fecha))." WHERE categoria='$categoria'  ORDER BY codigo;";
//AND left(trim(codigo),3)<>'401'
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->codigo;
	echo '" ';
	if ($partida==$registro_x->codigo) {echo 'selected="selected"';}
	echo ' >';
	echo formato_partida($registro_x->codigo) . " - " . $registro_x->descripcion;
	echo '</option>';
	}
?>