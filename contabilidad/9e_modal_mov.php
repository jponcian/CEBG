<?php
session_start();
include_once "../conexion.php";
include_once( '../funciones/auxiliar_php.php' );

if ( $_SESSION[ 'VERIFICADO' ] != "SI" ) {
  header( "Location: ../validacion.php?opcion=val" );
  exit();
}
?>
<form id="form999" name="form999" method="post">
  <!-- Modal Header -->
  <div class="modal-header bg-fondo text-center">
    <h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Agregar Movimiento al Estado de Cuenta
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </h4>
  </div>
  <!-- Modal body -->
  <div class="p-1">
    <div class="input-group mb-3">
      <div class="input-group-prepend"> <span class="input-group-text"><i class="fas fa-university"></i></span> </div>
      <a data-toggle="tooltip" title="BANCO">
      <select class="select2" style="width: 700px" name="txt_banco" id="txt_banco" onchange="combo(this.value);" >
        <?php
        $consulta_x = 'SELECT * FROM a_cuentas WHERE id;';
        //---------------
        $tabla_x = $_SESSION[ 'conexionsql' ]->query( $consulta_x );
        while ( $registro_x = $tabla_x->fetch_array() ) {
          echo '<option value=' . $registro_x[ 'id' ] . '>' . $registro_x[ 'banco' ] . ' ' . $registro_x[ 'cuenta' ] . ' ' . $registro_x[ 'descripcion' ] . '</option>';
        }
        ?>
      </select>
      </a> </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend"> <span class="input-group-text"><i class="fas fa-university"></i></span> </div>
      <a data-toggle="tooltip" title="BANCO">
      <select class="select2" style="width: 700px" name="txt_banco2" id="txt_banco2" onChange="valida_descripcion2(this.value)"  >
        <option value='0'>Seleccione la Cuenta destino</option>
        <?php
        $consulta_x = 'SELECT * FROM a_cuentas WHERE id>1;';
        //---------------
        $tabla_x = $_SESSION[ 'conexionsql' ]->query( $consulta_x );
        while ( $registro_x = $tabla_x->fetch_array() ) {
          echo '<option value=' . $registro_x[ 'id' ] . '>' . $registro_x[ 'banco' ] . ' ' . $registro_x[ 'cuenta' ] . ' ' . $registro_x[ 'descripcion' ] . '</option>';
        }
        ?>
      </select>
      </a> </div>
    <div class="row">
      <div class="form-group col-sm-4">
        <div class="input-group">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
          <input onkeyup="saltar(event,'txt_referencia')" type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha"  minlength="1" maxlength="10" value="<?php  echo date('d/m/Y');?>" required>
        </div>
      </div>
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-text"><i class="fas fa-file-invoice"></i></div>
          <input onkeyup="saltar(event,'txt_rif')" type="text" style="text-align:center" class="form-control " name="txt_referencia" id="txt_referencia" placeholder="Referencia #"  minlength="1" maxlength="10" required>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12">
        <div class="input-group">
          <div class="input-group-text"><i class="fa-solid fa-user-tie"></i></div>
          <input onkeyup="saltar(event,'txt_beneficiario')" type="text" style="text-align:left" class="form-control " name="txt_rif" id="txt_rif" placeholder="Rif del Beneficiario"  minlength="1" maxlength="15" required>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12">
        <div class="input-group">
          <div class="input-group-text"><i class="fa-solid fa-user-tie"></i></div>
          <input onkeyup="saltar(event,'txt_debe')" type="text" style="text-align:left" class="form-control " name="txt_beneficiario" id="txt_beneficiario" placeholder="Beneficiario"  minlength="1" maxlength="250" required>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12">
        <div class="input-group">
          <div class="input-group-text"><i class="fas fa-file-alt"></i></div>
          <input onkeyup="saltar(event,'txt_debe')" type="text" style="text-align:left" class="form-control " name="txt_descripcion" id="txt_descripcion" placeholder="Descripcion"  minlength="1" maxlength="250" required>
        </div>
      </div>
    </div>
	      <div class="row" id="descripcion2">
      <div class="form-group col-sm-12">
        <div class="input-group">
          <div class="input-group-text"><i class="fas fa-file-alt"></i></div>
          <input onkeyup="saltar(event,'txt_debe')" type="text" style="text-align:left" class="form-control " name="txt_descripcion2" id="txt_descripcion2" placeholder="2da Descripcion"  minlength="1" maxlength="250" required>
        </div>
      </div>
    </div>

    <table width="100%" border="1">
      <tr>
        <th width="20%"scope="col"><div class="input-group mb-3">
            <div class="input-group-prepend"> <span class="input-group-text"><i class="fas fa-envelope"></i></span> </div>
            <input id="txt_debe" name="txt_debe" onkeyup="saltar(event,'txt_haber')" placeholder="Debe" class="form-control" type="text" style="text-align:right" />
          </div></th>
        <th width="20%"scope="col"><div class="input-group mb-3">
            <div class="input-group-prepend"> <span class="input-group-text"><i class="fas fa-envelope"></i></span> </div>
            <input onkeyup="guardar_mov(event); saltar(event,'txt_referencia');" id="txt_haber" name="txt_haber" placeholder="Haber" class="form-control" type="text" style="text-align:right" />
          </div></th>
      </tr>
    </table>
    <br>
  </div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
	$("#descripcion2").hide();
});
//--------------------------------
$("#txt_fecha").datepicker();
//--------------------------------
function valida_descripcion2(id) {
    if (id>0)
		{	
		$("#descripcion2").show();	
		document.getElementById('txt_rif').value='G200012870';	
		document.getElementById('txt_beneficiario').value='CONTRALORIA DEL ESTADO BOLIVARIANO DE GUARICO';	
		}
}
//--------------------------------
function combo(id) {
    $.ajax({
        type: "POST",
        url: 'contabilidad/9k_combo.php?id=' + id,
        success: function(resp) {
            $('#txt_banco2').html(resp);
        }
    });
}
//----------------
function guardar_mov(e)
	{
	(e.keyCode)?k=e.keyCode:k=e.which;
	if(k==13)
		{
		alertify.warning("Espere mientras insertamos el movimiento...");
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'contabilidad/9i_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	busca_lista(); }
				else
					{	
					Swal.fire({
			//		  title: 'Informacion!',
					  icon: 'info',				
					  text: data.msg,				
					  timer: 5500,				
			//		  timerProgressBar: true,				
					  showDenyButton: false,
					  showCancelButton: false
								})			
								}
				//--------------
				} 

			});
		}			
	}
//--------------------------------
$("#txt_debe").on({
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
//--------------------------------
$("#txt_haber").on({
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