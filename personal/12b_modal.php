<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }

$acceso=97;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
if ($_SESSION["direccion"]==10 or $_SESSION['ADMINISTRADOR']==1)
	{
	$id = ' ';
	}
else
	{
	$id = ' AND id = '.$_SESSION["direccion"];
	}
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nuevo ODI
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
<div class="p-1">
			
<br>
	 <div class="form-group col-sm-12">
		<div class="input-group">
			<div class="input-group-text">Proyecto:</div>
			<select class="select2" style="width: 600px" style="font-size: 14px" name="txt_proyecto" id="txt_proyecto">
			<option value="0">--- Seleccione el Proyecto ---</option>
				<?php
				//--------------------
				$consultx = "SELECT id, estatus, descripcion FROM evaluaciones WHERE estatus<10 ORDER BY id DESC;"; 
				$tablx = $_SESSION['conexionsql']->query($consultx);
				while ($registro_x = $tablx->fetch_object())
				//-------------
				{
				echo '<option ';
	//				if ($unidad == $registro_x->id_direccion) { echo 'selected';}
				echo ' value="';
				echo $registro_x->id;
				echo '">';
				echo ($registro_x->descripcion);
				echo '</option>';
				}
				?>
			</select>
		</div>
	</div>

<!--<br>-->
	 <div class="form-group col-sm-12">
		<div class="input-group">
			<div class="input-group-text">Dirección:</div>
			<select class="select2" style="width: 600px" style="font-size: 14px" name="txt_direccion2" id="txt_direccion2" onchange="listar_areas2(this.value); validar_campo('txt_direccion2');">
			<option value="0">--- Seleccione la Direccion ---</option>
				<?php
				//--------------------
				$consultx = "SELECT id, direccion FROM	a_direcciones WHERE id<50 $id ORDER BY direccion;"; 
				$tablx = $_SESSION['conexionsql']->query($consultx);
				while ($registro_x = $tablx->fetch_object())
				//-------------
				{
				echo '<option ';
	//				if ($unidad == $registro_x->id_direccion) { echo 'selected';}
				echo ' value="';
				echo $registro_x->id;
				echo '">';
				echo ($registro_x->direccion);
				echo '</option>';
				}
				?>
			</select>
		</div>
	</div>

	<div class="form-group col-sm-12">
		<div class="input-group">
			<div class="input-group-text">Area:</div>
			<select class="select2" style="width: 635px" style="font-size: 14px" name="txt_area2" id="txt_area2" onchange=" validar_campo('txt_area2');">
			<option value="0">--- Seleccione la Direccion ---</option>
			</select>
		</div>
	</div>

	<div class="form-group col-sm-12">
		<div class="input-group">
			
			<textarea id="txt_descripcion" name="txt_descripcion" onchange="validar_campo('txt_descripcion');" placeholder="Descripcion" class="form-control" rows="3" ></textarea>
			
		</div>
	</div>

	<div class="form-group col-sm-12">
		<div class="input-group">
			<div class="input-group-text">Peso:</div>
			<select class="select2" style="width: 100px" style="font-size: 14px" name="txt_peso" id="txt_peso">
			<?php $ii=1; while ($ii<=50) { ?>
					<option value="<?php echo $ii ?>"><?php echo $ii ?></option>
			<?php $ii++; } ?>
			</select>
		</div>
	</div>

	</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="generar_poa()" ><i class="fas fa-save prefix grey-text mr-1"></i>Generar</button>
</div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
 });
//----------------
function listar_areas2(id) {
    $.ajax({
        type: "POST",
        url: 'personal/12b_combo.php?id=' + id,
        success: function(resp) {
            $('#txt_area2').html(resp);
        }
    });
}
//--------------------------------
function generar_poa()
	{
//	Swal.fire({
//	title: 'Estas seguro de generar el de proceso Evaluación?',
////	text: "Esta acción no se puede revertir!",
//	icon: 'question',
//	showCancelButton: true,
//	confirmButtonColor: '#3085d6',
//	cancelButtonColor: '#d33',
//	confirmButtonText: 'Si',
//	cancelButtonText: 'Cancelar'
//	}).then((result) => {
//	if (result.isConfirmed) {
		//-----------------------
		var parametros = $("#form999").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'personal/12f_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	
					Swal.fire({
			//		  title: 'Informacion!',
					  icon: 'info',				
					  text: data.msg,				
					  timer: 2500,				
			//		  timerProgressBar: true,				
					  showDenyButton: false,
					  showCancelButton: false
					})
					$('#modal_largo .close').click(); buscar();	}
			else
				{	Swal.fire(data.msg, '', data.tipo)	}
			//--------------
			}  
		});
		//-----------------------
//		}
//		})
	}
</script>