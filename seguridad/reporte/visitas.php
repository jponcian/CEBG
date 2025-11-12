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
$fecha2 = voltea_fecha($_GET['fecha2']);
//-----------	
$consultx1 = "SELECT fecha, COUNT(id) as total FROM asistencia_diaria_visita WHERE fecha>='$fecha1' and fecha<='$fecha2' GROUP BY fecha;"; 
$tablx1 = $_SESSION['conexionsql']->query($consultx1);
if ($tablx1->num_rows>0)	{

	}
//-----------	
$consultx2 = "SELECT direccion, COUNT(id) as total FROM asistencia_diaria_visita WHERE fecha>='$fecha1' and fecha<='$fecha2' GROUP BY direccion;"; 
$tablx2 = $_SESSION['conexionsql']->query($consultx2);
if ($tablx2->num_rows>0)	{
//	while ($registro2 = $tablx2->fetch_object())
//		{
//		
//		}
	}
//-----------	
$consultx3 = "SELECT left(ingreso,2) as ingreso, COUNT(id) as total FROM asistencia_diaria_visita WHERE fecha>='$fecha1' and fecha<='$fecha2' GROUP BY left(ingreso,2);"; 
$tablx3 = $_SESSION['conexionsql']->query($consultx3);
if ($tablx3->num_rows>0)	{
//	while ($registro3 = $tablx3->fetch_object())
//		{
//		
//		}
	}
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
          ['Fecha', 'Cantidad', { role: 'style' }],
		
			<?php $i=0; while ($registro1 = $tablx1->fetch_object())	{ $i++;	?>
			['<?php echo $_SESSION['dias_semana'][(date('N',(fecha_a_numero($registro1->fecha))))].' '.voltea_fecha($registro1->fecha); ?>',     <?php echo $registro1->total; $visitas += $registro1->total?>,     '<?php echo $_SESSION['color'][$i];?>'],
			<?php	} ?>
			
        ]);

        var options = {
          title: 'Total Visitas: <?php echo $visitas; ?>',
        hAxis: {
          title: 'Dia de Ingreso a las Instalaciones'
        },
        vAxis: {
          title: 'Cantidad de Personas Ingresadas'
        }
//			,slices: {  4: {offset: 0.2},
//                    0: {offset: 0.3},
//                    1: {offset: 0.4} }
//			,is3D: true
        };

//--------------------------
		  var data2 = google.visualization.arrayToDataTable([
          ['Hora', 'Cantidad'],
		
			<?php while ($registro2 = $tablx2->fetch_object())	{	?>
			['<?php echo ($registro2->direccion); ?>',     <?php echo $registro2->total; $direccion += $registro2->total?>],
			<?php	} ?>
			
        ]);

        var options2 = {
          title: 'Total Visitas: <?php echo $direccion; ?>'
//			,slices: {  4: {offset: 0.2},
//                    0: {offset: 0.3},
//                    1: {offset: 0.4} }
//			,is3D: true
        };

//-------------------------
		  
		  var data3 = google.visualization.arrayToDataTable([
          ['Hora', 'Cantidad', { role: 'style' }],
		
			<?php $i=0; while ($registro3 = $tablx3->fetch_object())	{	$i++; ?>
			['<?php echo hora_militar($registro3->ingreso.':00'); ?>',     <?php echo $registro3->total; $ingreso += $registro3->total?>,     '<?php echo $_SESSION['color'][$i];?>'],
			<?php	} ?>
			
        ]);

        var options3 = {
          title: 'Total Visitas: <?php echo $ingreso; ?>',
        hAxis: {
          title: 'Hora de Ingreso a las Instalaciones'
        },
        vAxis: {
          title: 'Cantidad de Personas Ingresadas'
        }
//			,slices: {  4: {offset: 0.2},
//                    0: {offset: 0.3},
//                    1: {offset: 0.4} }
//			,is3D: true
        };

		//-----------------------
		  
		var chart = new google.visualization.ColumnChart(document.getElementById('piechart'));
		chart.draw(data, options);

		var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));
		chart2.draw(data2, options2);

		var chart3 = new google.visualization.AreaChart(document.getElementById('piechart3'));
		chart3.draw(data3, options3);
        
      }
		
     
    </script>
</head>
<body>
   
<div align="center"><h1>Reporte de Visitas Diarias:</h1></div>
<div align="center"><h2>Fecha: <?php echo voltea_fecha($fecha1); ?> al <?php echo voltea_fecha($fecha2); ?></h2></div>

<div align="center"><h2>Segun los Dias de Visitas</h2></div>
<div align="center"><div id="piechart" style="width: 900px; height: 300px;"></div></div>

<div align="center"><h2>Segun las Direcciones Visitadas</h2></div>
<div align="center"><div id="piechart2" style="width: 900px; height: 300px;"></div></div>

<div align="center"><h2>Segun las horas de Visita</h2></div>
<div align="center"><div id="piechart3" style="width: 900px; height: 300px;"></div></div>

</body>
</html>