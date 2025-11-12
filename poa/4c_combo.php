<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$anno = $_GET['anno'] ;
//--------------------
$consultx = "SELECT bn_dependencias.id, bn_dependencias.division FROM	poa_proyecto_responsable, bn_dependencias WHERE poa_proyecto_responsable.id_direccion = bn_dependencias.id AND poa_proyecto_responsable.anno = '$anno' GROUP BY bn_dependencias.id ORDER BY division;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
//	if ($partida==$registro_x->codigo) {echo 'selected="selected"';}
	echo ' >';
	echo ($registro_x->id) . " - " . $registro_x->division;
	echo '</option>';
	}
?>