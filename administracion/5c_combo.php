<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0"> -SELECCIONE- </option>
<?php
$id = '9,8';
if ($_GET['islr']=='si')
	{ $id = $id . ',6';	}
if ($_GET['iva']>0)
	{ $id = $id . ',7';	}
//--------------------
$consultx = "SELECT * FROM a_retenciones WHERE id IN ($id) AND id NOT IN (SELECT id_descuento FROM ordenes_pago_descuentos WHERE id_orden_pago=".$_GET['id'].");";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
{
echo '<option ';
echo ' value="';
echo $registro_x->id;
echo '">';
echo ($registro_x->decripcion);
echo '</option>';
}
?>