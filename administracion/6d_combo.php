<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" >Seleccione</option>
<?php
$tipo = ($_GET['tipo']);
$banco = ($_GET['banco']);
$chequera = ($_GET['chequera']);
//--------------------
if ($tipo==1)
	{
	$consultx = "SELECT * FROM a_cuentas_chequera WHERE id_banco='$banco' ORDER BY chequera;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_object())
	//-------------
		{
		echo '<option ';
		if ($registro_x->id==$chequera)	{echo ' selected="selected" ';}
		echo ' value="';
		echo $registro_x->id;
		echo '" >';
		echo $registro_x->chequera;
		echo '</option>';
		}
	}
if ($tipo==2)
	{
	$cheque = ($_GET['cheque']);
	$chequera_bdd = ($_GET['chequera_bdd']);
	if ($chequera==0 and $chequera_bdd <>'')
		{
		$consultx = "SELECT * FROM a_cuentas_cheques WHERE (id_chequera = '$chequera_bdd') ORDER BY chequera;";// 
		}
	else
		{
		$consultx = "SELECT * FROM a_cuentas_cheques WHERE (id_chequera='$chequera' AND estatus IN (0,99)) ORDER BY chequera;";// 
		}
	//-------------
	//echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_object())
	//-------------
		{
		echo '<option ';
		if ($registro_x->id==$cheque)	{echo ' selected="selected" ';}
		echo ' value="';
		echo $registro_x->id;
		echo '" >';
		echo $registro_x->cheque;
		echo '</option>';
		}
	}
?>