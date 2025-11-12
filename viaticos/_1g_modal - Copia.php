<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=80;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Solicitud de Viaticos
<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
	    <input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>"/>
    <input type="hidden" id="ooficina" name="ooficina" value="<?php echo $_GET['oficina']; ?>"/>

</div>
<!-- Modal body -->
<div align="center" id="div2">			

</div>
<div align="center" id="div3">			

</div>

<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	
</div>

</form>
<script language="JavaScript">
//--------------------------------
setTimeout(function()	{
		tabla(); tabla2();
		},500)	
//--------------------------- PARA GUARDAR
function agregar2(cedula,boton)
 	 {
	$('#'+boton).hide();
	//Obtenemos datos formulario.
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'viaticos/1h_guardar.php?cedula='+cedula,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	
				alertify.success(data.msg);
				tabla();
				tabla2();
				//$('#boton').show();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
	}
//--------------------- PARA BUSCAR
function tabla2(){
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('viaticos/1i_tabla.php?id='+document.form999.oid.value+'&oficina='+document.form999.ooficina.value);
	//tabla();
}
//---------------------
function tabla(){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('viaticos/1d_tabla.php?id='+document.form999.oid.value);
}
//------------------------------ PARA ELIMINAR
function eliminar(id)
	{
	alertify.confirm("Estas seguro de eliminar el Registro?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "viaticos/1h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			tabla(); 
			tabla2();
			}
			});
		});
	}
</script>