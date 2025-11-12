<?php
session_start();
include_once "../conexion.php";
include_once( '../funciones/auxiliar_php.php' );
//-----------
$valor = $_GET[ 'valor' ];
$fecha1 = voltea_fecha( $_GET[ 'fecha1' ] );
$fecha2 = voltea_fecha( $_GET[ 'fecha2' ] );
//-----------
$filtro1 = " AND estado_cuenta.id_banco=" . $_GET[ 'tipo1' ] . ' ';
//----------
$consultx = "SELECT * FROM a_cuentas WHERE id=" . $_GET[ 'tipo1' ];
$tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
$registro = $tablx->fetch_object();
$_SESSION[ 'titulo' ] = 'BANCO ' . $registro->banco . ' ' . $registro->cuenta . ' (' . $registro->descripcion . ')';

//-----------
if ( $_GET[ 'tipo2' ] == '1' ) {
  $filtro2 = " AND estatus=0 ";
  $_SESSION[ 'titulo' ] .= ' (Por Conciliar) ';
} elseif ( $_GET[ 'tipo2' ] == '2' ) {
  $filtro2 = " AND estatus=1 ";
  $_SESSION[ 'titulo' ] .= ' (Conciliadas) ';
}
elseif ( $_GET[ 'tipo2' ] == '3' ) {
    $filtro2 = " ";
    //$_SESSION['titulo'] .= ' ' ;
  }
  //-------------
if ( $_GET[ 'tipo' ] == '1' ) {
  $filtro = " AND estatus=0 ";
} elseif ( $_GET[ 'tipo' ] == '2' ) {
  $filtro = " AND (concepto like '%" . ( $_GET[ 'valor' ] ) . "%' OR referencia like '%" . ( $_GET[ 'valor' ] ) . "%')";
  $_SESSION[ 'titulo' ] .= ' (Por Referencia ' . $_GET[ 'valor' ] . ')';
}
elseif ( $_GET[ 'tipo' ] == '3' ) {
  $filtro = " AND haber=$valor ";
  $_SESSION[ 'titulo' ] .= ' (Por Monto ' . $_GET[ 'valor' ] . ')';
}
elseif ( $_GET[ 'tipo' ] == '4' ) {
  $filtro = " AND fecha='" . date( 'Y/m/d' ) . "'";
  $_SESSION[ 'titulo' ] .= ' (Dia ' . date( 'd/m/Y' ) . ')';
  $fecha1 = date( 'Y/m/d' );
}
elseif ( $_GET[ 'tipo' ] == '5' ) {
  $filtro = " AND fecha>='$fecha1' AND fecha<='$fecha2' ";
  $_SESSION[ 'titulo' ] .= ' (Desde el ' . voltea_fecha( $fecha1 ) . ' al ' . voltea_fecha( $fecha2 ) . ')';
}
elseif ( $_GET[ 'tipo' ] == '7' ) {
  $filtro = " AND fecha_conciliacion>='$fecha1' AND fecha_conciliacion<='$fecha2' ";
  $_SESSION[ 'titulo' ] .= ' (Conciliado desde el ' . voltea_fecha( $fecha1 ) . ' al ' . voltea_fecha( $fecha2 ) . ')';
}
elseif ( $_GET[ 'tipo' ] == '6' ) {
  $filtro = " ";
}
?>
<table class="table table-hover" width="90%" border="0" align="center">
  <tr>
    <td class="TituloTablaP" height="41" align="center">Libro de Banco</td>
  </tr>
  <tr>
    <td align="center"><button type="button" id="boton1a" class="btn btn-lg btn-block btn-warning" onClick="sinc_op();"><i class="fas fa-search mr-2"></i>Actualizar # OP</button></td>
  </tr>
</table>
<table class="table table-hover" width="100%" border="0" align="center">
  <tr>
    <td colspan="11" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Libro de Banco en Pdf</button></td>
  </tr>
  <tr>
    <td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong># Orden Pago</strong></td>
    <td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
    <td  bgcolor="#CCCCCC" align="left"><strong>Beneficiario</strong></td>
    <td  bgcolor="#CCCCCC" align="left"><strong>Concepto</strong></td>
    <td bgcolor="#CCCCCC" align="left"><strong>Referencia</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Debe</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Haber</strong></td>
    <td bgcolor="#CCCCCC" align="right"><strong>Saldo</strong></td>
    <td bgcolor="#CCCCCC" ></td>
  </tr>
  <?php
  $saldo = 0;
  $estatus = array( '<div class="badge badge-warning">Por Conciliar</div>', '<div class="badge badge-success">Conciliada</div>', '<div class="badge badge-warning">Fecha Diferente</div>', '<div class="badge badge-warning">Referencia Igual</div>', '<div class="badge badge-warning">Monto Igual</div>' );
  //------ SALDO INICIAL
  $consultx = "SELECT SUM(debe) - SUM(haber) as saldo FROM estado_cuenta WHERE fecha<'$fecha1' AND YEAR(fecha)='" . anno( $fecha1 ) . "' $filtro1;";
  $_SESSION[ 'saldo' ] = $consultx;
  $tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
  $registro = $tablx->fetch_object();
  $saldo = $registro->saldo;
  //------ MONTAJE DE LOS DATOS
  $consultx = "SELECT	estado_cuenta.*, a_cuentas.banco, right(estado_cuenta.referencia,12) as ref FROM estado_cuenta, a_cuentas WHERE		estado_cuenta.id_banco = a_cuentas.id $filtro2 $filtro1 $filtro ORDER BY fecha, ordenado;";
  //echo $consultx;
  $_SESSION[ 'consulta' ] = $consultx;
  $tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
  $mes_actual = 0;
  $i = 0;
  while ( $registro = $tablx->fetch_object() ) {
    if ( $mes_actual <> mes( $registro->fecha ) ) {
      $mes_actual = mes( $registro->fecha );
      //--------------
      //		$total += $registro->monto;
      //		$saldo = ($registro->debe);
  if ( $i > 0 ) {
    ?>
  <tr id="fila9999">
    <td><div align="center" ></div></td>
    <td><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="left" ><strong>SALDO FINAL</strong></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="right" ><strong><?php echo formato_moneda($saldo); ?></strong></div></td>
    <td ><div align="center" ></div></td>
  </tr>
  <?php
  }
  ?>
  <tr id="fila<?php echo $registro->id; ?>">
    <td><div align="center" ></div></td>
    <td><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <?php //echo voltea_fecha($registro->fecha); ?>
    <td ><div align="center" ></div></td>
    <td ><div align="left" ><strong>SALDO INICIAL</strong></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="right" ><strong><?php echo formato_moneda($saldo); ?></strong></div></td>
    <td ><div align="center" ></div></td>
  </tr>
  <?php
  //--------------
  }
  $i++;
  $total += $registro->debe;
  $saldo += ( $registro->debe - $registro->haber );
  if ( $registro->id_orden > 0 ) {
    $op = $registro->id_orden;
  } else {
    $op = '';
  }
  ?>
  <tr id="fila<?php echo $registro->id; ?>">
    <td><!--<div align="center" ><?php //echo ($i); ?></div>-->
      
      <select class="form-control" name="txt_posicion" id="txt_posicion" onChange="posicion(this.value);">
        <option value='S-<?php echo $registro->id; ?>-<?php echo $registro->ordenado; ?>'>Subir</option>
        ';
		
        <option selected value='<?php echo $registro->id; ?>'><?php echo rellena_cero($i,4); ?></option>
        ';
		
        <option value='B-<?php echo $registro->id; ?>-<?php echo $registro->ordenado; ?>'>Bajar</option>
        ';
	
      </select>
      
      <!--<div align="center" ><?php //echo ($i); ?></div>--></td>
    <td><div align="center" ><?php echo $estatus[$registro->estatus_op]; ?></div></td>
    <td ><div align="center" >
        <?php if ($registro->id_orden>0) { ?>
        <a data-toggle="tooltip" title="Ver Orden de Pago">
        <button type="button" class="badge badge-success" onclick="imprimir('<?php echo encriptar($op); ?>','<?php echo ($registro->tipo_orden); ?>');" ><?php echo rellena_cero($registro->numero_orden,6); ?></button>
        </a>
        <?php }// else {	echo $estatus[$registro->estatus_op];	} ?>
      </div></td>
    <td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
    <td ><div align="left" ><?php echo ($registro->nombre_orden); ?></div></td>
    <td ><div align="left" ><?php echo ($registro->concepto); ?></div></td>
    <td ><div align="right" ><a data-toggle="tooltip" title="Buscar la Referencia en las Ordenes de Pago">
        <button data-toggle="modal" data-target="#modal_largo" data-keyboard="false" type="button" class="btn btn-outline btn-sm" onclick="busca_op(2,'<?php echo ($registro->referencia); ?>','<?php echo ($registro->id); ?>','<?php echo ($registro->id_orden); ?>');"><strong><?php echo trim($registro->referencia); ?></strong></button>
        </a></div></td>
    <td ><div align="right" ><strong><?php echo formato_moneda($registro->debe); ?></strong></div></td>
    <td ><div align="right" ><a data-toggle="tooltip" title="Buscar Monto en las Ordenes de Pago">
        <button data-toggle="modal" data-target="#modal_largo" data-keyboard="false" type="button" class="btn btn-outline btn-sm" onclick="busca_op(1,'<?php echo ($registro->haber); ?>','<?php echo ($registro->id); ?>','<?php echo ($registro->id_orden); ?>');"><strong><?php echo formato_moneda($registro->haber); ?></strong></button>
        </a></div></td>
    <td ><div align="right" ><strong><?php echo formato_moneda($saldo); ?></strong></div></td>
    <td ><div align="center" >
        <?php if ($registro->id_orden<=0) { ?>
        <a data-toggle="tooltip" title="Ver Nota de Debito">
        <button type="button" class="btn btn-outline-success btn-sm" onclick="imprimir3('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button>
        </a>
        <?php } ?>
        <a data-toggle="tooltip" title="Eliminar">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button>
        </a></div></td>
  </tr>
  <?php
  }
  ?>
  <tr id="fila99991">
    <td><div align="center" ></div></td>
    <td><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="left" ><strong>SALDO FINAL</strong></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="center" ></div></td>
    <td ><div align="right" ><strong><?php echo formato_moneda($saldo); ?></strong></div></td>
    <td ><div align="center" ></div></td>
  </tr>
  <tr>
    <td colspan="11" class="PieTabla">Alcaldia del Municipio Francisco de Miranda</td>
  </tr>
</table>
<script language="JavaScript">
//----------------
function busca_op(tipo,valor,movimiento,orden)
	{
	$('#modal_lg').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#modal_lg').load('contabilidad/9c_modal_buscar.php?valor='+valor+'&tipo='+tipo+'&movimiento='+movimiento+'&orden='+orden);
	}
</script>