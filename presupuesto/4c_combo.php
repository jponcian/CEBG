<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
?>
<option value="0" > Seleccione </option>
<?php
if ($_GET['tipo']==1)
	{
	$anno = $_GET['anno'] ;
	//--------------------
	$consultx = "SELECT codigo, descripcion FROM a_presupuesto_$anno WHERE categoria is null AND left(a_presupuesto_$anno.codigo,8) <> '00000000' GROUP BY codigo;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_object())
	//-------------
		{
		echo '<option value="';
		echo $registro_x->codigo;
		echo '" ';
		echo ' >';
		echo $registro_x->codigo . " - " . $registro_x->descripcion;
		echo '</option>';
		}
	}
//-----------------------
if ($_GET['tipo']==2)
	{
	$anno = $_GET['anno'] ;
	$categoria = $_GET['categoria'] ;
	//-------------
	if ($categoria<>'0' and $categoria<>'-1')
		{	
		//--------------------
		$consultx = "SELECT codigo, descripcion FROM a_presupuesto_$anno WHERE categoria=$categoria AND categoria is NOT null AND left(a_presupuesto_$anno.codigo,8) <> '00000000' GROUP BY codigo;";
		//echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro_x = $tablx->fetch_object())
		//-------------
			{
			echo '<option value="';
			echo $registro_x->codigo;
			echo '" ';
			echo ' >';
			echo $registro_x->codigo . " - " . $registro_x->descripcion;
			echo '</option>';
			}
		}
	}
//-----------------------
if ($_GET['tipo']==3)
	{
	$anno = $_GET['anno'] ;
	$categoria = $_GET['categoria'] ;
	$categoria1 = $_GET['categoria1'] ;
	$partida1 = $_GET['partida1'] ;
	if ($categoria1==$categoria)
		{	$filtro = " codigo <> $partida1 AND "; }
		else
			{	$filtro = ""; }
	//-------------
	if ($categoria<>'0' and $categoria<>'-1')
		{	
		//--------------------
		$consultx = "SELECT codigo, descripcion FROM a_presupuesto_$anno WHERE $filtro categoria=$categoria AND categoria is NOT null AND left(a_presupuesto_$anno.codigo,8) <> '00000000' GROUP BY codigo;";
		//echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro_x = $tablx->fetch_object())
		//-------------
			{
			echo '<option value="';
			echo $registro_x->codigo;
			echo '" ';
			echo ' >';
			echo $registro_x->codigo . " - " . $registro_x->descripcion;
			echo '</option>';
			}
		}
	}
?>