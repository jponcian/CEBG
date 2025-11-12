<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }

$acceso=22;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------

?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Valor Prima por Hijos Actual
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">		
				
				<div class="form-group col-sm-12">
					<div class="input-group">
						<input id="txt_actual" name="txt_actual" placeholder="Monto Bs" class="form-control" type="text" style="text-align:right" value="<?php echo formato_moneda(prima_hijos()); ?>" />
					</div>
				</div>
				
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_firma()" ><i class="fas fa-save prefix grey-text mr-1"></i>Guardar</button>
</div>
</form>
<script language="JavaScript">
//--------------------------------
setTimeout(function()	{
		$('#txt_actual').focus();
		},500)	
//-------------
//--------------------------------
function guardar_firma()
	{
	alertify.confirm("Estas seguro de Guardar los Cambios?", function()
		{
		var parametros = $("#form999").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'personal/21b_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	$('#modal_normal .close').click();	
				}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			}  
		});
		});
	}
//--------------------------------
$("#txt_actual").on({
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