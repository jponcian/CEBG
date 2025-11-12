<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value='0'>Seleccione la Cuenta destino</option>
<?php
$id = $_GET['id'] ;
//--------------------
if ($id>0)
	{
	$consulta_x = "SELECT * FROM a_cuentas WHERE id<>$id;";
	//---------------
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	while ($registro_x = $tabla_x->fetch_array())
		{
		echo '<option value='.$registro_x['id'].'>'.$registro_x['banco'].' '.$registro_x['cuenta'].' '.$registro_x['descripcion'].'</option>';
		}
	}
?>