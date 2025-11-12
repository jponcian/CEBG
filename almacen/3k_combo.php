<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione el material a solicitar</option>
<?php
$direccion = $_GET['direccion'] ;
$i=0;
//--------------------
if ($direccion>0)
	{
	$consultx = "SELECT * FROM bn_materiales WHERE inventario > 0 AND id_bien NOT IN (SELECT bn_solicitudes_detalle.id_bien FROM bn_solicitudes_detalle, bn_materiales WHERE bn_materiales.id_bien = bn_solicitudes_detalle.id_bien AND division=$direccion AND estatus=0) ORDER BY descripcion_bien;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_object())
	//-------------
		{
		$i++;
		echo '<option value="';
		echo $registro_x->id_bien;
		echo '" ';
		if ($partida==$registro_x->id_bien) {echo 'selected="selected"';}
		echo ' >';
		echo ($i) . " - " . $registro_x->descripcion_bien;
		echo '</option>';
		}
	}
?>