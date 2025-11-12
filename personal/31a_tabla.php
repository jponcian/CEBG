<?php
session_start();
include_once "../conexion.php";
include_once( '../funciones/auxiliar_php.php' );

if ( $_SESSION[ 'VERIFICADO' ] != "SI" ) {
  header( "Location: ../validacion.php?opcion=val" );
  exit();
}

$acceso = 18;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada table" border="1" align="center" >
  <tr>
    <td class="TituloTablaP" height="41" colspan="10" align="center">Horario Registrado</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Horario</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Hora Flexible</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Guardar</strong></td>
  </tr>
  <?php
  //------ MONTAJE DE LOS DATOS
  $consultx = "SELECT * FROM a_horario WHERE 1=1 ORDER BY horario;"; //$filtrar.$_GET['valor'].";"; 
  //echo $consultx;
  $tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
  while ( $registro = $tablx->fetch_object() ) {
    $i++;
    //list($banco,$cuenta)=explode(' ', $registro->codigo);
    ?>
  <tr id="fila<?php echo $registro->id; ?>">
    <td><div align="center"> <?php echo ($i); ?> </div></td>
    <td><div align="right"> <?php echo hora_militar($registro->horario); ?> </div></td>
    <td><div align="center">
        <input id="txt_hora" name="txt_hora" class="form-control timepicker<?php echo $registro->id; ?>" value="<?php echo ($registro->ingreso); ?>" style="align-content: center" />
      </div></td>
    <td><div align="center"> <?php echo ($registro->tipo); ?> </div></td>
    <td><a data-toggle="tooltip" title="Editar" >
      <button type="button" class="btn btn-outline-warning btn-sm" onclick="editar('<?php echo ($registro->id); ?>');"><i class="fas fa-edit"></i></button>
      </a></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
  </tr>
</table>
<script language="JavaScript">
$('.timepicker1').timepicker({
    timeFormat: 'hh:mm:ss p',
    interval: 1,
    minTime: '8',
    maxTime: '11:59am',
	startTime: '08:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});
	$('.timepicker2').timepicker({
    timeFormat: 'hh:mm:ss p',
    interval: 1,
    minTime: '08:05am',
    maxTime: '11:59am',
//    startTime: '11:59am',
	dynamic: false,
    dropdown: true,
    scrollbar: true
});
	$('.timepicker3').timepicker({
    timeFormat: 'hh:mm:ss p',
    interval: 1,
    minTime: '01:00pm',
    maxTime: '03:59pm',
    startTime: '01:00pm',   
    dynamic: false,
    dropdown: true,
    scrollbar: true
});
$('.timepicker4').timepicker({
    timeFormat: 'hh:mm:ss p',
    interval: 1,
    minTime: '01:00pm',
    maxTime: '03:59pm',
//	startTime: '03:59pm',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});
</script>