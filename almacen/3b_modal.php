<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=54;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$condicion = "WHERE id=".$_SESSION["direccion"]; 
?>
<form id="form999" name="form999" method="post" onSubmit="return evitar();" action="#">
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nueva Solicitud
<button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button></h4>
</div>
<!-- Modal body -->
	<br>
		<div class="p-1">
			
			<div class="row">
				
				<div class="form-group col-sm-7 ml-4">
					<select class="select2" name="txt_origen" id="txt_origen" onchange="combo(this.value);listar_bienes();" style="width: 500px">
						<option value="0" >Seleccione la Direccion</option>
<?php
//--------------------
if ($_SESSION["direccion"]==12)	{	$condicion = ""; 	}
//--------------------
$consult = "SELECT * FROM a_direcciones $condicion ORDER BY direccion;"; // WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	if ($partida==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->direccion;
	echo '</option>';
	}
?>
					</select>
				</div>

			</div>
	<br>
	<div class="row">
		<div class="form-group col-sm-8 ml-4">
			<select  name="txt_articulo" id="txt_articulo" onChange="siguiente();" class="select2" style="width: 500px">
			<option value="0" >Seleccione</option>
			</select><!---->
		</div>
		<div class="form-group col-sm-3">
			<input name="txt_cantidad" id="txt_cantidad" type="text" class="form-control" onFocus="this.select();" onkeyup="agregar2(event, this.value);" style="text-align: right" value="0,00" />
		</div>
	</div> 
	<br>
	</div>
	
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<div class="container" id="div3">			
	</div>
</div>

</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
});
//--------------------------------
setTimeout(function()	{
		listar_bienes();
		},400)	
//------------------------------ PARA ELIMINAR
function siguiente()
	{
	setTimeout(function()	{
		document.getElementById('txt_cantidad').focus();
		},400)	
	}
//------------------------------ PARA ELIMINAR
function eliminar(id)
	{
	alertify.confirm("Estas seguro de Eliminar el Artículo?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "almacen/3h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Artículo Eliminado Correctamente');
			//--------------
			listar_bienes();
			combo(document.form999.txt_origen.value);
			}
			});
		});
	}
//------------------------------ PARA ELIMINAR
function reasignar(cantidad)
	{
	//alertify.success("Agregando...");
	var parametros = "origen="+document.form999.txt_origen.value+ "&articulo="+document.form999.txt_articulo.value+ "&cantidad="+cantidad;
	$.ajax({
	url: "almacen/3f_reasignar.php",
	type: "POST",
	data: parametros,
	success: function(r) {
	alertify.success('Registro Agregado Correctamente');
	//--------------
	document.form999.txt_cantidad.value = '0,00';
	combo(document.form999.txt_origen.value);
	listar_bienes();
//	document.form999.txt_articulo.focus();
	document.getElementById("txt_articulo").focus();
	}
	});
	}
//--------------------------- PARA GUARDAR
function agregar2(e, cantidad)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{ reasignar(cantidad); }
	}
//--------------------------------
function combo(direccion) {
    $.ajax({
        type: "POST",
        url: 'almacen/3k_combo.php?direccion=' + direccion,
        success: function(resp) {
            $('#txt_articulo').html(resp);
        }
    });
}
//--------------------------------
$("#txt_cantidad").on({
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