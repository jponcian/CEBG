<?php
session_start();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

$acceso=9;
//------- VALIDACION ACCESO USUARIO
include_once "../../validacion_usuario.php";
//-----------------------------------
$completo =0;
$correcto =0;
$retardados =0;
$correcto2 =0;
$retardados2 =0;

$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha1']);
//-----------	
$consultx = "SELECT funcionarios FROM asistencia_diaria WHERE (fecha>='$fecha1' and fecha<='$fecha2') limit 1;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	{
	$registro = $tablx->fetch_object();
	$completo = $registro->funcionarios;
	}
//-----------	
$consultx = "SELECT COUNT(cedula) as cant FROM	asistencia_diaria WHERE (fecha>='$fecha1' and fecha<='$fecha2') AND estatus=0 AND tipo='ENTRADA' AND horario='08:00:00' GROUP BY estatus;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	{
	$registro = $tablx->fetch_object();
	$correcto = $registro->cant;
	}
//-----------	
$consultx = "SELECT COUNT(cedula) as cant FROM	asistencia_diaria WHERE (fecha>='$fecha1' and fecha<='$fecha2') AND estatus=1 AND tipo='ENTRADA' AND horario='08:00:00' GROUP BY estatus;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	{
	$registro = $tablx->fetch_object();
	$retardados = $registro->cant;
	}
//-----------	
$faltantes = $completo - $correcto - $retardados;
//echo $faltantes .' '. $completo .' '. $correcto .' '. $retardados;
//-----------	
$consultx = "SELECT COUNT(cedula) as cant FROM	asistencia_diaria WHERE (fecha>='$fecha1' and fecha<='$fecha2') AND estatus=0 AND tipo='ENTRADA' AND horario='13:00:00' GROUP BY estatus;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	{
	$registro = $tablx->fetch_object();
	$correcto2 = $registro->cant;
	}
//-----------	
$consultx = "SELECT COUNT(cedula) as cant FROM	asistencia_diaria WHERE (fecha>='$fecha1' and fecha<='$fecha2') AND estatus=1 AND tipo='ENTRADA' AND horario='13:00:00' GROUP BY estatus;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	{
	$registro = $tablx->fetch_object();
	$retardados2 = $registro->cant;
	}
//-----------	
$faltantes2 = $completo - $correcto2 - $retardados2;
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
 
 <title>Asistencia Diaria</title>
    <script type="text/javascript" src="../../lib/googlecharts/loader.js"></script>
   
	<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Personal', 'Cantidad'],
          ['Correcto: <?php echo $correcto; ?>',     <?php echo $correcto; ?>],
          ['Fuera de Horario: <?php echo $retardados; ?>',      <?php echo $retardados; ?>],
          ['Personal Faltante: <?php echo $faltantes; ?>',  <?php echo $faltantes; ?>]
        ]);

        var options = {
          title: 'Personal Activo: <?php echo $completo; ?> Funcionarios'
//			,slices: {  4: {offset: 0.2},
//                    0: {offset: 0.3},
//                    1: {offset: 0.4} }
//			,is3D: true
        };

        var data2 = google.visualization.arrayToDataTable([
          ['Personal', 'Cantidad'],
          ['Correcto: <?php echo $correcto; ?>',     <?php echo $correcto; ?>],
          ['Fuera de Horario: <?php echo $retardados2; ?>',      <?php echo $retardados2; ?>],
          ['Personal Faltante: <?php echo $faltantes2; ?>',  <?php echo $faltantes2; ?>]
        ]);

        var options2 = {
          title: 'Personal Activo: <?php echo $completo; ?> Funcionarios'
//			,slices: {  4: {offset: 0.2},
//                    0: {offset: 0.3},
//                    1: {offset: 0.4} }
//			,is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
        
		var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));
        chart2.draw(data2, options2);
      }
		
     
    </script>
</head>
<body>
   
<div align="center"><h1>Asistencia Diaria:</h1></div>
<div align="center"><h2>Fecha: <?php echo voltea_fecha($fecha1); ?> al <?php echo voltea_fecha($fecha2); ?></h2></div>
<div align="center"><h2>TURNO: MAÑANA</h2>
</div>
<div align="center"><div id="piechart" style="width: 900px; height: 500px;"></div></div>

<div align="center"><h2>TURNO: TARDE</h2></div>
<div align="center"><div id="piechart2" style="width: 900px; height: 500px;"></div></div>

</body>
</html>