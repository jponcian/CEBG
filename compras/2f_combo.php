<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$anno = anno(voltea_fecha($_GET['fecha'])) ;
//--------------------
$consultx = "SELECT a_presupuesto_$anno.categoria, a_categoria.descripcion FROM a_presupuesto_$anno , a_categoria WHERE (left(trim(a_presupuesto_$anno.codigo),3)<>'402' AND left(trim(a_presupuesto_$anno.codigo),3)<>'404') AND a_presupuesto_$anno.categoria = a_categoria.codigo GROUP BY a_presupuesto_$anno.categoria ORDER BY a_presupuesto_$anno.categoria ASC;";
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