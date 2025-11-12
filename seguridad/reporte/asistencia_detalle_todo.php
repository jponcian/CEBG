<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
$_SESSION['conexionsql']->query("SET NAMES 'UTF8'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
$fecha1 = fecha_a_numero(voltea_fecha($_GET['desde']));
$hasta1 = fecha_a_numero(voltea_fecha($_GET['hasta']));
$fecha = voltea_fecha($_GET['desde']);
$hasta = voltea_fecha($_GET['hasta']);
$tipo = decriptar($_GET['tipo']);
$cedula = ($_GET['cedula']);
$direccion = decriptar($_GET['direccion']);

//-------------
if ($direccion==0) {}
	else { 	
		if ($cedula==0) 	
			{	$filtro = ' AND id_direccion='.$direccion;		}	
		  	else 
				{	$filtro = ' AND cedula='.$cedula;	}
		 }

//-------------
$consultx = "DROP TABLE IF EXISTS aux;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$consultx = "CREATE TEMPORARY TABLE aux (SELECT * FROM asistencia_diaria WHERE fecha >= '$fecha' AND fecha <= '$hasta' $filtro);";
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
?>
<!--
<link href="tablecloth.css" rel="stylesheet" type="text/css" media="screen" />
<script src="tablecloth.js" type="text/javascript" ></script>
-->
<div id="container">
	<div id="content">
<br><h2><strong><?php echo "ENTRADAS Y SALIDAS DE LA FECHA ".voltea_fecha($fecha); ?></strong></h2><br>
		<table >
<?php
while ($fecha1<=$hasta1)
{
$diasemana = date('N',$fecha1);
if ($diasemana<>6 and $diasemana<>7)
	{
$consult = "SELECT aux.*, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre FROM aux, rac WHERE aux.cedula=rac.cedula AND fecha = '$fecha' GROUP BY cedula ORDER BY id_direccion, cedula, hora;"; 
$tablx = $_SESSION['conexionsql']->query($consult);
if ($tablx->num_rows > 0) 
	{
	 ?>
	<tr><th align="center" valign="top"><strong>#</strong></th><th align="center" valign="top"><strong>Cedula</strong></th><th align="center" valign="top"><strong>Funcionario</strong></th><th align="center" valign="top"><strong>Fecha</strong></th></tr>
	<?php
	$fecha1 = ($hasta1);

	$i=0;
	$nomina = '';
	$direccion = '';
	//-----------------

	$tabla = $_SESSION['conexionsql']->query($consult);
	//-----------------
	$i=0; $monto=0;
	while ($registro = $tabla->fetch_object())
		{
		$diasemana = date('N',fecha_a_numero($registro->fecha));
		if ($diasemana<>6 and $diasemana<>7)
			{
			if ($direccion<>$registro->id_div)
				{	
				$pdf->Cell(0,5.5,'				'.$registro->direccion,1,1,'L',1);	
				$direccion = $registro->id_div ;
				}
			//----------
			?>
			<tr>
			<td align="left" valign="top"><?php echo $i+1; ?></td>
			<td align="left" valign="top"><?php echo $registro->cedula; ?></td>
			<td align="left" valign="top"><?php echo $registro->nombre; ?></td>
			<td align="left" valign="top"><?php echo voltea_fecha($registro->fecha) ?></td>
<?php
$consult = "SELECT aux.hora FROM aux WHERE fecha = '$fecha' AND cedula=".$registro->cedula." ORDER BY hora;"; 
$tablx2 = $_SESSION['conexionsql']->query($consult);
while ($registro2 = $tablx2->fetch_object())
	{
?>
<td align="left" valign="top"><?php echo hora_militar(substr($registro2->hora,0,5)) ?></td>				
<?php
	}	
?>
			</tr>
			<?php
			$monto = $monto + $registro->sueldo;
			//-----------
			$i++;
			}
		}

	//-----------
		}
	}
$fecha1 = $fecha1 + 86400;
$fecha = sube_dia($fecha);
}
?>
</table>
</div>
</div>