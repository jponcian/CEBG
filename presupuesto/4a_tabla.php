<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);

switch ($filtro) {
  case 1:
    $_SESSION['titulo'] = "Año $dato_buscar";
    $filtrar = " anno = '$dato_buscar' ";
    break;
  case 5:
    $_SESSION['titulo'] = "Categoria $dato_buscar";
    $filtrar = " (categoria1 = '$dato_buscar' or categoria2 = '$dato_buscar') ";
    break;
  case 2:
    $_SESSION['titulo'] = "Partida $dato_buscar";
    $filtrar = " (partida1 = '$dato_buscar' or partida2 = '$dato_buscar') ";
    break;
  case 3:
    $_SESSION['titulo'] = "Desde el " . voltea_fecha($fecha1) . " al " . voltea_fecha($fecha2);
    $filtrar = " fecha >= '$fecha1' AND fecha <= '$fecha2' ";
    break;
  case 4:
    $_SESSION['titulo'] = 'Año ' . date('Y');
    $filtrar = date('Y') . "=anno ";
    break;
}
?>
<table class="table table-hover" width="100%" border="0" align="center">
  <tr>
    <td class="TituloTablaP" height="41" colspan="10" align="center">Traslados en Sistema</td>
  </tr>
  <tr>
    <td colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC" align="center"><strong>N&deg;</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Resolución</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Disminuye</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Incrementa</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Diferencia</strong></td>
    <td bgcolor="#CCCCCC" align="center"></td>
    <td bgcolor="#CCCCCC" align="center"></td>
  </tr>
  <?php
  //------ MONTAJE DE LOS DATOS
  $consultx = "SELECT id_traspaso, sum(monto1) AS monto1, sum(monto2) AS monto2, traslados.anno, traslados.numero, traslados.fecha, 	traslados.concepto, estatus FROM	traslados WHERE 1=1 AND $filtrar GROUP BY traslados.anno, traslados.numero, traslados.fecha, traslados.concepto ORDER BY anno, estatus, numero;";
//  echo $consultx;
  $_SESSION['consulta'] = $consultx;
  $tablx = $_SESSION['conexionsql']->query($consultx);
  while ($registro = $tablx->fetch_object()) {
    $i++;
  ?>
    <tr>
      <td>
        <div align="center"><?php echo ($i); ?></div>
      </td>
      <td>
        <div align="left"><?php echo ($registro->concepto); ?></div>
      </td>
      <td>
        <div align="center"><?php echo voltea_fecha($registro->fecha); ?></div>
      </td>
      <td>
        <div align="right"><?php echo formato_moneda($registro->monto1); ?></div>
      </td>
      <td>
        <div align="right"><?php echo formato_moneda($registro->monto2); ?></div>
      </td>
      <td>
        <div align="right"><?php echo formato_moneda($registro->monto2 - $registro->monto1); ?></div>
      </td>
      <td>
        <div align="center"><a data-toggle="tooltip" title="Ver Traspaso"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir_t('<?php echo encriptar($registro->id_traspaso); ?>');"><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div>
      </td>
      <td>
        <div align="center"><?php if ($registro->estatus==0) { ?><button onclick="aprobar_traslado('<?php echo encriptar($registro->id); ?>');" type="button" id="boton<?php echo encriptar($registro->id_traspaso); ?>" class="btn btn-outline-success waves-effect"><i class="fa-regular fa-circle-check prefix grey-text mr-1"></i> Aprobar Traspaso</button><?php } ?></div>
      </td>
    </tr>
  <?php
  }
  ?>
  <tr>
    <td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
  </tr>
</table>