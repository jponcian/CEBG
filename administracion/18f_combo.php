<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$anno = anno(voltea_fecha($_GET['fecha'])) ;
$rif = (($_GET['rif'])) ;
//--------------------
$consultx = "SELECT a_presupuesto_$anno.codigo, a_categoria.descripcion FROM a_presupuesto_$anno , a_categoria WHERE a_presupuesto_$anno.rif = '$rif' AND a_presupuesto_$anno.codigo = a_categoria.codigo AND a_presupuesto_$anno.categoria IS NULL ORDER BY a_presupuesto_$anno.categoria ASC;";//left(trim(a_presupuesto_$anno.codigo),3) <> '401' AND
$tablx = $_SESSION['conexionsql']->query($consultx); ECHO $consultx;
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