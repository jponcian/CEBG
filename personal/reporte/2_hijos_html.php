<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'utf8'");

if ($_SESSION['VERIFICADO'] != "SI") {
  header("Location: ../index.php?errorusuario=val");
  exit();
}

// Consulta principal
$tabla = $_SESSION['conexionsql']->query($_SESSION['consultaH']);
$i = 0;
$monto = 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Reporte de Hijos</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
    }

    .filtro-nomina {
      margin: 20px auto;
      text-align: center;
    }

    h2 {
      text-align: center;
      margin-top: 30px;
    }
  </style>
</head>

<body>
  <h2>Reporte de Carga Familiar de Empleados</h2>
  <div style="width:95%;margin:auto;">
    <table style="width:100%;" border="1" cellpadding="5" cellspacing="0">
      <thead>
        <tr>
          <th>#</th>
          <th>Nómina</th>
          <th>Cédula</th>
          <th>Empleado</th>
          <th>Cédula</th>
          <th>Nombre</th>
          <th>Fecha Nac.</th>
          <th>Edad</th>
          <th>Sexo</th>
          <th>Parentesco</th>
        </tr>
      </thead>
      <tbody>
        <?php
        function calcular_edad($fecha_nac)
        {
          $fecha = new DateTime($fecha_nac);
          $hoy = new DateTime();
          $edad = $hoy->diff($fecha)->y;
          return $edad;
        }
        $edad_min = isset($_GET['edad_min']) ? intval($_GET['edad_min']) : null;
        $edad_max = isset($_GET['edad_max']) ? intval($_GET['edad_max']) : null;
        while ($registro = $tabla->fetch_object()) {
          $edad = calcular_edad($registro->fecha_nac);
          if ((isset($_GET['edad_min']) && $_GET['edad_min'] !== '' && $edad < $edad_min) || (isset($_GET['edad_max']) && $_GET['edad_max'] !== '' && $edad > $edad_max)) {
            continue;
          }
        ?>
          <tr>
            <td><?php echo $i + 1; ?></td>
            <td><?php echo $registro->nomina; ?></td>
            <td><?php echo formato_cedula($registro->cedula); ?></td>
            <td><?php echo $registro->nombre; ?></td>
            <td><?php echo formato_cedula(abs($registro->cih)); ?></td>
            <td><?php echo ($registro->nombres); ?></td>
            <td><?php echo voltea_fecha($registro->fecha_nac) ?></td>
            <td style="text-align:right;"><?php echo $edad; ?></td>
            <td><?php echo $registro->sexo; ?></td>
            <td><?php echo $registro->parentesco; ?></td>
          </tr>
        <?php
          $i++;
        }
        ?>
      </tbody>
    </table>
  </div>
  <!-- Solo la tabla funcional, sin duplicados -->
</body>

</html>