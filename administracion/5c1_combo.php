<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0"> -SELECCIONE- </option>
<?php
if (strtoupper($_GET['letra'])=='J')
	{
	$consultx = "SELECT * FROM a_cuadro_islr WHERE tipo='PJ';";
	}
else
	{
	$consultx = "SELECT * FROM a_cuadro_islr WHERE tipo<>'PJ';";
	}
//--------------------
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
{
echo '<option ';
echo ' value="';
echo $registro_x->id_codigo;
echo '">';
echo ($registro_x->descripcion).' ('.formato_moneda($registro_x->porcentaje).'%)';
echo '</option>';
}
?>