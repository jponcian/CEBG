<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$combo = $_GET['combo'];
$nomina = $_POST['ONOMINA'];
$tipo = $_POST['ODESCRIPCION'];
//---------
if ($_GET['combo']==1)
	{
	$consultx = "SELECT descripcion FROM nomina WHERE estatus=0 AND tipo_pago<>'007' AND tipo_pago<>'008' AND nomina='$nomina' GROUP BY descripcion ORDER BY descripcion;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_array())
		{
		echo '<option value="'.$registro_x['descripcion'].'">'.$registro_x['descripcion'].'</option>';
		}
	}
//---------
if ($_GET['combo']==2)
	{
	$consultx = "SELECT desde, hasta FROM nomina WHERE estatus=0 AND tipo_pago<>'007' AND tipo_pago<>'008' AND nomina='$nomina' AND descripcion='$tipo' GROUP BY desde, hasta ORDER BY desde, hasta;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_array())
		{
		echo '<option value="'.$registro_x['hasta'] . '">'.voltea_fecha($registro_x['desde']).' al '.voltea_fecha($registro_x['hasta']).'</option>';
		}
	}
?>