<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
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
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Crear ODI</a></div>
		<diw class="row ml-3">
            <strong>Opciones de Filtrado:</strong>
<br><br>

            <div class="form-group col-sm-12">
				<div class="input-group">
					<div class="input-group-text">Proyecto:</div>
					<select class="select2" style="width: 600px" style="font-size: 14px" name="txt_proyecto1" id="txt_proyecto1" onchange="validar_campo('txt_proyecto1');">
			<option value="0">--- Seleccione el Proyecto ---</option>
				<?php
				//--------------------
				$consultx = "SELECT id, estatus, descripcion FROM evaluaciones WHERE estatus<=10 ORDER BY id DESC;"; 
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
	<br>

            <div class="form-group col-sm-12">
				<div class="input-group">
					<div class="input-group-text">Dirección:</div>
					<select class="select2" style="width: 600px" style="font-size: 14px" name="txt_direccion" id="txt_direccion" onchange="listar_areas(this.value); validar_campo('txt_direccion');">
					<option value="0">--- Todas ---</option>
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
		<select class="select2" style="width: 635px" style="font-size: 14px" name="txt_area" id="txt_area" onchange="buscar(); validar_campo('txt_area');">
		<option value="0">--- Todas ---</option>
		</select>
	</div>
</div>
			
        </diw>

 <br><div id="div2"></div>
</form>
<script language="JavaScript">
//-------------
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
//	buscar();
});
//----------------
function activar(id, estatus)
 {
	var parametros = "id="+id+"&estatus="+estatus;
	$.ajax({  
		type : 'POST',
		url  : 'personal/12d_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	
					//alertify.success(data.msg);	
				 Swal.fire({
			//		  title: 'Informacion!',
					  icon: 'info',				
					  text: data.msg,				
					  timer: 2500,				
			//		  timerProgressBar: true,				
					  showDenyButton: false,
					  showCancelButton: false
					})
//					$('#modal_normal .close').click(); 
//					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//----------------
function listar_areas(id) {
    $.ajax({
        type: "POST",
        url: 'personal/12b_combo.php?id=' + id,
        success: function(resp) {
            $('#txt_area').html(resp);
			buscar();
        }
    });
}
//----------------
function eliminarg(id)
	{
	Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id;
			$.ajax({
			url: "personal/12e_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
			//-----------------------
			}
		})
}
//----------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/12b_modal.php');
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('personal/12a_tabla.php?dir='+document.form1.txt_direccion.value+'&area='+document.form1.txt_area.value+'&id='+document.form1.txt_proyecto1.value);
	}
</script>