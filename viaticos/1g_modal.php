<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=80;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$_SESSION['id'] = $_GET['id'];
$_SESSION['zona'] = $_GET['zona'];
$_SESSION['contralor'] = $_GET['contralor'];
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Solicitud de Viaticos
<button type="button" class="close" data-dismiss="modal" onclick="buscar();">&times;</button></h4>
	    <input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>"/>
    <input type="hidden" id="ozona" name="ozona" value="<?php echo $_GET['zona']; ?>"/>
    <input type="hidden" id="ocontralor" name="ocontralor" value="<?php echo $_GET['contralor']; ?>"/>

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
function calcular(e, valor)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		document.form999.txt_total.value = number_format((document.form999.txt_monto.value * document.form999.txt_cantidad.value),2);
		}
	}
//--------------------------- PARA GUARDAR
function montou(valor)
 	 {
	//$('#'+boton).hide();
	//Obtenemos datos formulario.
	//document.form999.txt_monto.value=valor;
	var monto = valor.split('-');
	document.form999.txt_monto.value = monto[1];
	document.form999.txt_monto2.value = number_format(monto[1],2);
	document.form999.txt_cantidad.focus();
	}
//--------------------------- PARA GUARDAR
function agregar2()
 	 {
	$('#check1').hide();
	//Obtenemos datos formulario.
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'viaticos/1h_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	
				alertify.success(data.msg);
				tabla();
				tabla2();
				$('#check1').show();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
	}
//--------------------- PARA BUSCAR
function tabla2(){
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('viaticos/1i_tabla.php?id='+document.form999.oid.value+'&zona='+document.form999.ozona.value+'&contralor='+document.form999.ocontralor.value);
	//tabla();
}
//---------------------
function tabla(){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('viaticos/1d_tabla.php?id='+document.form999.oid.value);
}
//------------------------------ PARA ELIMINAR
function eliminar_detalle(id, id_solicitud)
	{
	alertify.confirm("Estas seguro de eliminar el Registro?",  
	function()
			{ 
			var parametros = "id=" + id + "&id_solicitud=" + id_solicitud;
			$.ajax({
			url: "viaticos/1j_eliminar.php",
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