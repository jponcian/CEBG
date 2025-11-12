<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<!--<option value="0" >Seleccione</option>-->
<?php
echo '<option value="';
	echo '0';
	echo '" ';
//		if ($partida==$registro_x->cedula) {echo 'selected="selected"';}
	echo ' >';
	echo 'Todos';
	echo '</option>';

$origen = decriptar($_GET['origen']) ; 
if ($origen>0)
	{
	//--------------------
	$consult = "SELECT cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre FROM rac WHERE id_div= '$origen' AND nomina <> 'EGRESADOS' ORDER BY (cedula+1);";// WHERE id_direccion='$desde'
	$tablx = $_SESSION['conexionsql']->query($consult);
	while ($registro_x = $tablx->fetch_object())
	//-------------
		{
		echo '<option value="';
		echo $registro_x->cedula;
		echo '" ';
//		if ($partida==$registro_x->cedula) {echo 'selected="selected"';}
		echo ' >';
		echo ($registro_x->cedula).' - '.$registro_x->nombre;
		echo '</option>';
		}
	}
?>