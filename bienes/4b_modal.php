<?php
session_start();
include_once "../conexion.php";
include_once( '../funciones/auxiliar_php.php' );

if ( $_SESSION[ 'VERIFICADO' ] != "SI" ) {
  header( "Location: ../validacion.php?opcion=val" );
  exit();
}

$acceso = 50;
if ( $_GET[ 'id' ] > 0 ) {
  $id = $_GET[ 'id' ];
} else {
  $id = 0;
}
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT * FROM bn_bienes WHERE id_bien = $id;"; //echo $consultx;
$tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
if ( $tablx->num_rows > 0 ) {
  $registro = $tablx->fetch_object();
  //--------
  $numero_bien = $registro->numero_bien;
  $nombre = $registro->descripcion_bien;
  $id_direccion = $registro->id_direccion;
  $id_categoria = $registro->id_categoria;
  $conservacion = $registro->conservacion;
  $grupo = $registro->grupo;
  $subgrupo = $registro->subgrupo;
  $seccion = $registro->seccion;
  $subseccion = $registro->subseccion;
  $valor = formato_moneda( $registro->valor );
  $marca = $registro->marca;
  $fabricante = $registro->fabricante;
  $modelo = $registro->modelo;
  $serial = $registro->serial;
  $proveedor = $registro->proveedor;
  $orden_compra = $registro->orden_compra;
  $factura = $registro->factura;
  $cuenta = $registro->cuenta;
  $fecha = voltea_fecha( $registro->fecha_adquisicion );
  $empleado = empleado( $registro->usuario );
  $modificado = ( $empleado[ 1 ] . ' ' . $registro->fechaproceso );
}
?>
<form id="form999" name="form999" method="post" >
  <!-- Modal Header -->
  <div class="modal-header bg-fondo text-center">
    <h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Ficha Bien Nacional
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $id; ?>"/>
  </div>
  <br>
  <!-- Modal body -->
  <div class="p-1">
    <div class="row">
      <div class="form-group col-sm-4">
        <div class="input-group">
          <div class="input-group-text" align="center">Bien</div>
          <input maxlength="8" onkeyup="saltar(event,'txt_bien')"  placeholder="Numero" id="txt_numero" name="txt_numero" class="form-control" type="text" style="text-align:center" value="<?php echo $numero_bien; ?>" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12">
        <div class="input-group">
          <div class="input-group-text" align="center">Descripcion</div>
          <input id="txt_bien" onkeyup="saltar(event,'txt_valor')" placeholder="Descripcion" name="txt_bien" class="form-control" type="text" value="<?php echo $nombre; ?>"/>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-3">
        <div class="input-group">
          <input style="text-align: center" placeholder="Grupo" id="txt_grupo" maxlength="10" name="txt_grupo" class="form-control" type="text" value="<?php echo $grupo; ?>" onkeyup="saltar(event,'txt_subgrupo')" />
        </div>
      </div>
      <div class="form-group col-sm-3">
        <div class="input-group">
          <input style="text-align: center" placeholder="SubGrupo" id="txt_subgrupo" maxlength="10" name="txt_subgrupo" class="form-control" type="text" value="<?php echo $subgrupo; ?>"  onkeyup="saltar(event,'txt_seccion')" />
        </div>
      </div>
      <div class="form-group col-sm-3">
        <div class="input-group">
          <input style="text-align: center" placeholder="Seccion" id="txt_seccion" maxlength="10" name="txt_seccion" class="form-control" type="text" value="<?php echo $seccion; ?>" onkeyup="saltar(event,'txt_subseccion')" />
        </div>
      </div>
      <div class="form-group col-sm-3">
        <div class="input-group">
          <input style="text-align: center" placeholder="SubSeccion" id="txt_subseccion" maxlength="10" name="txt_subseccion" class="form-control" type="text" value="<?php echo $subseccion; ?>"  onkeyup="saltar(event,'txt_categoria')" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12">
        <div class="input-group">
          <div class="input-group-text" align="center">Categoria</div>
          <!--						<input placeholder="Modelo" id="txt_rif" maxlength="100" name="txt_rif" class="form-control" type="text" value="<?php //echo $proveedor; ?>"  onkeyup="saltar(event,'txt_oc')" />-->
          <select class="select2" style="width: 600px" name="txt_categoria" id="txt_categoria" onchange="" >
            <?php
            $consultx = "SELECT * FROM bn_categorias ORDER BY codigo";
            $tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
            while ( $registro_x = $tablx->fetch_array() ) {
              echo '<option value=' . $registro_x[ 'id_categoria' ];
              if ( $id_categoria == $registro_x[ 'id_categoria' ] ) {
                echo ' selected="selected" ';
              }
              echo '>' . $registro_x[ 'codigo' ] . ' - ' . $registro_x[ 'descripcion' ] . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12">
        <div class="input-group">
          <div class="input-group-text" align="center">Direccion</div>
          <!--						<input placeholder="Modelo" id="txt_rif" maxlength="100" name="txt_rif" class="form-control" type="text" value="<?php //echo $proveedor; ?>"  onkeyup="saltar(event,'txt_oc')" />-->
          <select class="select2" style="width: 600px" name="txt_area" id="txt_area" onchange="" >
            <?php
            $consultx = "SELECT * FROM bn_dependencias ORDER BY codigo";
            $tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
            while ( $registro_x = $tablx->fetch_array() ) {
              echo '<option value=' . $registro_x[ 'id' ];
              if ( $id_direccion == $registro_x[ 'id' ] ) {
                echo ' selected="selected" ';
              }
              echo '>' . ( $registro_x[ 'codigo' ] ) . ' ' . $registro_x[ 'division' ] . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-4">
        <div class="input-group">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
          <input type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Adquisicion"  minlength="1" maxlength="10" value="<?php  echo $fecha;?>" required>
        </div>
      </div>
      <div class="form-group col-sm-4">
        <div class="input-group">
          <div class="input-group-text" align="center">Condicion</div>
          <select class="custom-select" style="font-size: 14px" name="txt_conservacion" id="txt_conservacion" onchange="">
            <?php
            //					muy malo”, 2 a “malo”, 4 a “regular”, 6 a “bueno” y 7 “muy bueno”
            //--------------------
            echo '<option ';
            echo ' value="Bueno"';
            if ( $conservacion == "Bueno" ) {
              echo ' selected="selected" ';
            }
            echo '>Bueno</option>';
            //--------------------
            echo '<option ';
            echo ' value="Regular"';
            if ( $conservacion == "Regular" ) {
              echo ' selected="selected" ';
            }
            echo '>Regular</option>';
            //--------------------
            echo '<option ';
            echo ' value="Malo"';
            if ( $conservacion == "Malo" ) {
              echo ' selected="selected" ';
            }
            echo '>Malo</option>';
            //--------------------
            ?>
          </select>
        </div>
      </div>
      <div class="form-group col-sm-4">
        <div class="input-group">
          <input id="txt_valor" onkeyup="saltar(event,'txt_marca')" placeholder="Valor Bs" name="txt_valor" class="form-control"   type="text" maxlength="10" style="text-align:right"  value="<?php echo $valor; ?>"/>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-text" align="center">Marca</div>
          <input placeholder="Marca" id="txt_marca" maxlength="100" name="txt_marca" class="form-control" type="text" value="<?php echo $marca; ?>" onkeyup="saltar(event,'txt_fabricante')" />
        </div>
      </div>
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-text" align="center">Fabricante</div>
          <input placeholder="Fabricante" id="txt_fabricante" maxlength="100" name="txt_fabricante" class="form-control" type="text" value="<?php echo $fabricante; ?>"  onkeyup="saltar(event,'txt_modelo')" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-text" align="center">Modelo</div>
          <input placeholder="Modelo" id="txt_modelo" maxlength="100" name="txt_modelo" class="form-control" type="text" value="<?php echo $modelo; ?>"  onkeyup="saltar(event,'txt_serial')" />
        </div>
      </div>
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-text" align="center">Serial</div>
          <input placeholder="Serial" id="txt_serial" maxlength="100" name="txt_serial" class="form-control" type="text" value="<?php echo $serial; ?>"  onkeyup="saltar(event,'txt_rif')" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12">
        <div class="input-group">
          <div class="input-group-text" align="center">Proveedor</div>
          <!--						<input placeholder="Modelo" id="txt_rif" maxlength="100" name="txt_rif" class="form-control" type="text" value="<?php //echo $proveedor; ?>"  onkeyup="saltar(event,'txt_oc')" />-->
          <select class="select2" style="width: 600px" name="txt_rif" id="txt_rif" onchange="" >
            <?php
            $consultx = "SELECT rif, nombre FROM contribuyente ORDER BY nombre";
            $tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
            while ( $registro_x = $tablx->fetch_array() ) {
              echo '<option value=' . $registro_x[ 'rif' ];
              if ( $proveedor == $registro_x[ 'rif' ] ) {
                echo ' selected="selected" ';
              }
              echo '>' . $registro_x[ 'rif' ] . ' - ' . $registro_x[ 'nombre' ] . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-text" align="center">OC</div>
          <input placeholder="Orden de Compra" id="txt_oc" maxlength="100" name="txt_oc" class="form-control" type="text" value="<?php echo $orden_compra; ?>"  onkeyup="saltar(event,'txt_factura')" />
        </div>
      </div>
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-text" align="center">Factura</div>
          <input placeholder="Factura" id="txt_factura" maxlength="100" name="txt_factura" class="form-control" type="text" value="<?php echo $factura; ?>" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12">
        <div class="input-group">
          <div class="input-group-text" align="center">Cuenta</div>
          <!--						<input placeholder="Modelo" id="txt_rif" maxlength="100" name="txt_rif" class="form-control" type="text" value="<?php //echo $proveedor; ?>"  onkeyup="saltar(event,'txt_oc')" />-->
          <select class="select2" style="width: 600px" name="txt_cuenta" id="txt_cuenta" onchange="" >
            <?php
            $consultx = "SELECT * FROM `a_partidas` WHERE left(codigo,3)<>'401' and left(codigo,3)<>'407' ORDER BY codigo";
            $tablx = $_SESSION[ 'conexionsql' ]->query( $consultx );
            while ( $registro_x = $tablx->fetch_array() ) {
              echo '<option value=' . $registro_x[ 'codigo' ];
              if ( $cuenta == $registro_x[ 'codigo' ] ) {
                echo ' selected="selected" ';
              }
              echo '>' . $registro_x[ 'codigo' ] . ' - ' . $registro_x[ 'descripcion' ] . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12">
        <div class="alert alert-info" role="alert">Última Modificación: <?php echo $modificado; ?></div>
      </div>
    </div>
  </div>
  <!-- Modal footer -->
  <div class="modal-footer justify-content-center">
    <button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar()" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>
  </div>
  </div>
  </div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
	$("#txt_fecha").datepicker();
});
//--------------------------------
setTimeout(function()	{
		$('#txt_numero').focus();
		},500)	
//--------------------------------
$("#txt_valor").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    }
});
</script>