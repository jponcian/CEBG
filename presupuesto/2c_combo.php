<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Todas las Actividades</option>
<?php
$anno = ($_GET['fecha']) ;
//--------------------
$consultx = "SELECT a_presupuesto_$anno.categoria, a_categoria.descripcion FROM a_presupuesto_$anno , a_categoria WHERE a_presupuesto_$anno.categoria = a_categoria.codigo AND left(a_presupuesto_$anno.codigo,8) <> '00000000' GROUP BY a_presupuesto_$anno.categoria ORDER BY a_presupuesto_$anno.categoria ASC;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->categoria;
	echo '" >';
	echo $registro_x->categoria . " - " . $registro_x->descripcion;
	echo '</option>';
	}
?>