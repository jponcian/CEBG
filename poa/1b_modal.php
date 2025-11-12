<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }

$acceso=62;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Generar POA
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">		
																
				<div class="form-group col-sm-6">
<!--					<div class="input-group">-->
						<select class="custom-select" style="font-size: 14px" name="txt_anno" id="txt_anno">
			<?php
			$i = date ('Y')+1;
			while ($i>=2022)
			//-------------
			{
			echo '<option ';
			echo ' value="';
			echo $i;
			echo '">Ejercicio ';
			echo $i;
			echo '</option>';
			$i--;
			}
			?>
		</select>
<!--					</div>-->
				</div>
				
				<div class="form-group col-sm-6">
					<div class="input-group">
						<input id="txt_fecha" name="txt_fecha" class="form-control" type="text" style="text-align:center" maxlength="20" value="<?php echo date('d/m/Y'); ?>" readonly />
					</div>
				</div>
				
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="generar_poa()" ><i class="fas fa-save prefix grey-text mr-1"></i>Generar</button>
</div>
</form>
<script language="JavaScript">
$("#txt_fecha").datepicker();
//--------------------------------
function generar_poa()
	{
	Swal.fire({
	title: 'Estas seguro de generar el POA?',
//	text: "Esta acción no se puede revertir!",
	icon: 'question',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	confirmButtonText: 'Si',
	cancelButtonText: 'Cancelar'
	}).then((result) => {
	if (result.isConfirmed) {
		//-----------------------
		var parametros = $("#form999").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'poa/1d_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	$('#modal_normal .close').click(); buscar();	}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			}  
		});
		//-----------------------
		}
		})
	}
</script>