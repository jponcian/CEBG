<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$categoria = $_GET['categoria'] ;
$anno = $_GET['fecha'] ;
$partida = $_GET['partida'] ;
$_SESSION['categoria'] = $_GET['categoria'] ;
$_SESSION['anno']= $_GET['fecha'] ;
//--------------------
$consultx = "SELECT * FROM a_presupuesto_$anno WHERE categoria='$categoria' ORDER BY codigo;";
if ($categoria==0)	
	{	$consultx = "SELECT * FROM a_presupuesto_$anno WHERE categoria IS NOT NULL GROUP BY codigo ORDER BY codigo;";	}
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