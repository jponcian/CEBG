<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
  header("Location: ../validacion.php?opcion=val");
  exit();
}
//$_SESSION['id_ct'] = $_SESSION['CEDULA_USUARIO'];
//-----------	
$consultx = "SELECT left(codigo,3) as codigo, sum(modificado) as modificado, sum(compromiso) as compromiso FROM `a_presupuesto_" . date('Y') . "` WHERE LEFT(codigo,2)<>'01' AND categoria='0101020051' GROUP BY left(codigo,3);";
$tablx = $_SESSION['conexionsql']->query($consultx);

//-----------	
$consultx2 = "SELECT left(codigo,3) as codigo, sum(modificado) as modificado, sum(compromiso) as compromiso FROM `a_presupuesto_" . date('Y') . "` WHERE LEFT(codigo,2)<>'01' AND categoria='0101020052' GROUP BY left(codigo,3);";
$tablx2 = $_SESSION['conexionsql']->query($consultx2);

//-----------	

$consultx3 = "SELECT left(codigo,3) as codigo, sum(modificado) as modificado, sum(compromiso) as compromiso FROM `a_presupuesto_" . date('Y') . "` WHERE LEFT(codigo,2)<>'01' AND categoria='0101020053' GROUP BY left(codigo,3);";
$tablx3 = $_SESSION['conexionsql']->query($consultx3);
?>
<div class="row">
  <div class="col-12 col-sm-6 col-md-3 mt-3">
    <div class="info-box" style="cursor: pointer" onClick="ver_poai();" data-toggle="modal" data-target="#modal_extra">
      <span class="info-box-icon bg-danger elevation-1" onMouseOver=""><i class="fa-solid fa-users fa-beat"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><strong>GESTION POAI</strong></span>
      </div>
    </div>
  </div>
  <?php
  $cedula = $_SESSION['CEDULA_USUARIO'];
  $consulta_x = "SELECT usuarios_accesos.acceso, ip FROM usuarios_accesos, usuarios WHERE usuarios_accesos.usuario = usuarios.usuario AND usuarios_accesos.usuario = " . $cedula . " AND usuarios_accesos.acceso IN (108)";
  $tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
  if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {  ?>
    <div class="col-12 col-sm-6 col-md-3 mt-3">
      <div class="info-box" style="cursor: pointer" onClick="menu124();" data-toggle="modal" data-target="#modal_largo">
        <span class="info-box-icon bg-primary elevation-1" onMouseOver=""><i class="fa-regular fa-clock fa-beat"></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><strong>ASISTENCIA DIARIA</strong></span>
        </div>
      </div>
    </div>
  <?php }  ?>
  <?php
  $cedula = $_SESSION['CEDULA_USUARIO'];
  $consulta_x = "SELECT usuarios_accesos.acceso, ip FROM usuarios_accesos, usuarios WHERE usuarios_accesos.usuario = usuarios.usuario AND usuarios_accesos.usuario = " . $cedula . " AND usuarios_accesos.acceso IN (107)";
  $tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
  if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {  ?>
    <div class="col-12 col-sm-6 col-md-3 mt-3">
      <div class="info-box" style="cursor: pointer" onClick="menu126();">
        <span class="info-box-icon bg-primary elevation-1" onMouseOver=""><i class="fa-solid fa-person-walking fa-beat"></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><strong>PERMISOS</strong></span>
        </div>
      </div>
    </div>
  <?php }  ?>
  <?php
  $cedula = $_SESSION['CEDULA_USUARIO'];
  $consultx = "SELECT evaluaciones.estatus FROM eval_asignacion, evaluaciones WHERE eval_asignacion.id_evaluacion = evaluaciones.id  AND evaluaciones.estatus = 4 AND eval_asignacion.estatus = 3 AND cedula = '$cedula'"; //$filtrar.$_GET['valor'].";"; 
  $tablx5 = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
  if ($tablx5->num_rows > 0) {  ?>
    <div class="col-12 col-sm-6 col-md-3 mt-3">
      <div class="info-box" style="cursor: pointer" onClick="menu116();">
        <span class="info-box-icon bg-primary elevation-1" onMouseOver=""><i class="fa-solid fa-users fa-beat"></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><strong>VALIDAR ODI</strong></span>
        </div>
      </div>
    </div>
  <?php }  ?>
  <?php
  $cedula = $_SESSION['CEDULA_USUARIO'];
  $consultx = "SELECT evaluaciones.estatus FROM eval_asignacion, evaluaciones WHERE eval_asignacion.id_evaluacion = evaluaciones.id  AND evaluaciones.estatus = 8 AND eval_asignacion.estatus = 7 AND cedula = '$cedula'"; //$filtrar.$_GET['valor'].";"; 
  $tablx5 = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
  if ($tablx5->num_rows > 0) {  ?>
    <div class="col-12 col-sm-6 col-md-3 mt-3">
      <div class="info-box" style="cursor: pointer" onClick="menu119();">
        <span class="info-box-icon bg-primary elevation-1" onMouseOver=""><i class="fa-solid fa-users fa-beat"></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><strong>ACEPTAR EVALUACIÓN</strong></span>
        </div>
      </div>
    </div>
  <?php }  ?>
</div>

<?php
$cedula = $_SESSION['CEDULA_USUARIO'];
$consultx = "SELECT evaluaciones.estatus FROM eval_asignacion, evaluaciones WHERE eval_asignacion.id_evaluacion = evaluaciones.id 	AND evaluaciones.estatus = 4 AND eval_asignacion.estatus = 3 AND cedula = '$cedula'"; //$filtrar.$_GET['valor'].";"; 
$tablx5 = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
if ($tablx5->num_rows > 0) {  ?>
  <div class="col-12 col-sm-6 col-md-3 mt-3">
    <div class="info-box" style="cursor: pointer" onClick="menu116();">
      <span class="info-box-icon bg-primary elevation-1" onMouseOver=""><i class="fa-solid fa-users fa-beat"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><strong>VALIDAR ODI</strong></span>
      </div>
    </div>
  </div>
<?php }  ?>
<?php
$cedula = $_SESSION['CEDULA_USUARIO'];
$consultx = "SELECT evaluaciones.estatus FROM eval_asignacion, evaluaciones WHERE eval_asignacion.id_evaluacion = evaluaciones.id 	AND evaluaciones.estatus = 8 AND eval_asignacion.estatus = 7 AND cedula = '$cedula'"; //$filtrar.$_GET['valor'].";"; 
$tablx5 = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
if ($tablx5->num_rows > 0) {  ?>
  <div class="col-12 col-sm-6 col-md-3 mt-3">
    <div class="info-box" style="cursor: pointer" onClick="menu119();">
      <span class="info-box-icon bg-primary elevation-1" onMouseOver=""><i class="fa-solid fa-users fa-beat"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><strong>ACEPTAR EVALUACIÓN</strong></span>
      </div>
    </div>
  </div>
<?php }  ?>
<br />

<br />
<div class="container-fluid">
  <table width="80%" border="0">
    <tbody>
      <tr>
        <th scope="col"></th>
      </tr>
      <tr>
        <th scope="col">
          <div id="chart_div"></div>
        </th>
        <th scope="col">
          <div id="chart_div2"></div>
        </th>
        <th scope="col">
          <div id="chart_div3"></div>
        </th>
      </tr>
    </tbody>
  </table>
</div>
<br />
<br />

<!-- fix for small devices only -->
<!--  <div class="clearfix hidden-md-up"></div>-->

<script language="JavaScript">
  //-----------------------
  google.charts.load('current', {
    packages: ['corechart', 'bar']
  });
  google.charts.setOnLoadCallback(drawMultSeries);

  function drawMultSeries() {
    var data = google.visualization.arrayToDataTable([
      ['Partidas', 'Presupuesto', 'Comprometido'],

      <?php while ($registro = $tablx->fetch_object()) {  ?>['<?php echo ($registro->codigo); ?>', <?php echo $registro->modificado; ?>, <?php echo $registro->compromiso; ?>], <?php  } ?>
    ]);

    var options = {
      title: 'Actividad 51',
      hAxis: {
        title: 'Partidas'
      },
      vAxis: {
        title: ''
      }
    };

    var chart = new google.visualization.ColumnChart(
      document.getElementById('chart_div'));

    //chart.draw(data, options);

    //--------------------------
    var data2 = google.visualization.arrayToDataTable([
      ['Partidas', 'Presupuesto', 'Comprometido'],

      <?php while ($registro2 = $tablx2->fetch_object()) {  ?>['<?php echo ($registro2->codigo); ?>', <?php echo $registro2->modificado; ?>, <?php echo $registro2->compromiso; ?>], <?php  } ?>
    ]);

    var options2 = {
      title: 'Actividad 52',
      hAxis: {
        title: 'Partidas'
      },
      vAxis: {
        title: ''
      }
    };

    var chart2 = new google.visualization.ColumnChart(
      document.getElementById('chart_div2'));

    //chart2.draw(data2, options2);	

    //--------------------------
    var data3 = google.visualization.arrayToDataTable([
      ['Partidas', 'Presupuesto', 'Comprometido'],

      <?php while ($registro3 = $tablx3->fetch_object()) {  ?>['<?php echo ($registro3->codigo); ?>', <?php echo $registro3->modificado; ?>, <?php echo $registro3->compromiso; ?>], <?php  } ?>
    ]);

    var options3 = {
      title: 'Actividad 53',
      hAxis: {
        title: 'Partidas'
      },
      vAxis: {
        title: ''
      }
    };

    var chart3 = new google.visualization.ColumnChart(
      document.getElementById('chart_div3'));

    //chart3.draw(data3, options3);
  }
  //-----------------------
  function ver_poai() {
    $('#modal_xl').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
    $('#modal_xl').load('inicio/poa.php');
  }
  //---------------------------
</script>