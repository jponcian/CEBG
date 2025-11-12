<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=8;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Bienes Nacionales (Ingreso y Salida)</div>
		<br >
<br>
 <div id="div1"></div>
</form>
<script language="JavaScript">
setTimeout(function()	{
		listar_tabla(); 
		},500);	//document.form1.ocedula.focus;
//----------------------------
function devolver(id,idbien,bien)
	{
Swal.fire({
	title: '¿Desea darle Ingreso al Bien Nacional?',
	icon: 'question',				
//	text: "¿Desea darle Salida a algun Bien Nacional?",				
	showDenyButton: true,
	input: 'text',
	inputPlaceholder: 'Ingrese el número de Cedula del Funcionario',
	inputAttributes: {
	maxlength: 8,
	autocapitalize: 'off'
	},
	showCancelButton: false,
	//confirmButtonText: 'INGRESO',
	denyButtonText: `No`,
}).then((result) => {
  if (result.isConfirmed) { 
   		var parametros = "id=" + id ;
			$.ajax({
			url: "seguridad/7b_ingreso.php?id="+ id+"&ci="+result.value+"&idbien="+idbien+"&bien="+bien,
			dataType:"json",
			type: "POST",
			data: parametros,
			success: function(data) {
				if (data.tipo=='error')	{ Swal.fire(data.msg, '', data.tipo)	}
					else {alertify.success(data.msg); listar_tabla();}			
			//--------------
						}
					});
  				}
			})
					
	}
//---------------------
function borrarb2(id,idbien,ida)
	{
		Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id + "&idb=" + idbien;
				$.ajax({
				url: "seguridad/5h_eliminar.php",
				type: "POST",
				data: parametros,
				success: function(r) {
					//Swal.fire('Borrado!', 'El registro fue borrado.', 'success');
					alertify.success('El registro fue borrado con Exito!');
					listar_tabla();
					}
				});
			//-----------------------
			}
		})
	}
//--------------------- PARA BUSCAR
function listar_tabla(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('seguridad/7a_tabla.php');
}
</script>