<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$categoria = $_GET['categoria'] ;
$fecha = $_GET['fecha'] ;
$partida = $_GET['partida'] ;
$id_rif = $_POST['txt_rif'][0] ;
//--------------------
$consultx = "SELECT * FROM a_presupuesto_".anno(voltea_fecha($fecha))." WHERE categoria='$categoria' ORDER BY codigo;";
//------------- POR SI YA TIENE EL IVA INCLUIDO
$consult = "SELECT partida FROM presupuesto WHERE id_contribuyente='$id_rif' AND estatus=0 AND left(trim(partida),7)='4031801';";
$tablx = $_SESSION['conexionsql']->query($consult);
if ($tablx->num_rows>0)
	{
	$consultx = "SELECT * FROM a_presupuesto_".anno(voltea_fecha($fecha))." WHERE categoria='$categoria' AND left(trim(codigo),7)<>'4031801' ORDER BY codigo;";
	} echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->codigo;
	echo '" ';
	if ($partida==$registro_x->codigo) {echo 'selected="selected"';}
	echo ' >';
	echo formato_partida($registro_x->codigo) . " - " . $registro_x->descripcion;
	echo '</option>';
	}
?>