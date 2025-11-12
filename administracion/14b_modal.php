<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=24;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
    <input type="hidden" id="oid" name="oid" value="0"/>
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nueva Orden Financiera
  <button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group">
				<div class="input-group-text" align="center">Proveedor</div>

				<select class="select2" style="width: 600px" placeholder="Seleccione el Proveedor" name="txt_id_rif" id="txt_id_rif" onchange="buscar_orden();" >
				<?php
				$consultx = "SELECT id, rif, nombre FROM contribuyente ORDER BY nombre"; 
				$tablx = $_SESSION['conexionsql']->query($consultx);
				while ($registro_x = $tablx->fetch_array())
					{
					echo '<option value='.$registro_x['id'].'/'.$registro_x['rif'];
					//if ($id_categoria==$registro_x['id']) {echo ' selected="selected" ';}
					echo '>'.$registro_x['rif'].' - '.$registro_x['nombre'].'</option>';
					}
				?></select>	
				
			</div>
		</div>

	</div>	

	<!--<div class="row">
		<div class="form-group col-sm-4">
			<div class="input-group"><div class="input-group-text"><i class="fas fa-file-invoice"></i></div>
				<input onkeyup="saltar(event,'txt_control')" type="text" style="text-align:center" class="form-control " name="txt_factura" id="txt_factura" placeholder="Numero Factura"  minlength="1" maxlength="10" required></div>
			
		</div>	
		
		<div class="form-group col-sm-4">
			<div class="input-group"><div class="input-group-text"><i class="fas fa-file-alt"></i></div>
				<input onkeyup="saltar(event,'txt_fecha')" type="text" style="text-align:center" class="form-control " name="txt_control" id="txt_control" placeholder="Numero Control"  minlength="1" maxlength="10" required></div>
			
		</div>	
		
		
	</div>-->
			
	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group-text"><i class="fas fa-university mr-2"></i>
			<textarea id="txt_concepto" name="txt_concepto" placeholder="Escribe aqui el Concepto" class="form-control" rows="4" ></textarea>
			</div>
		</div>
	</div>			

<table width="100%" border="1">
  <tr>
    <th scope="col"><input onkeyup="saltar(event,'txt_detalle')" id="txt_cantidad" name="txt_cantidad" placeholder="Cant" class="form-control" type="text" style="text-align:center" /></th>
    <th width="70%" scope="col"><input onkeyup="saltar(event,'txt_precio')" id="txt_detalle" name="txt_detalle" placeholder="Detalle" class="form-control" type="text" style="text-align:center" /></th>
    <th width="20%"scope="col"><input onkeyup="guardar_detalle2(event)" id="txt_precio" name="txt_precio" placeholder="Precio" class="form-control" type="text" style="text-align:center" /></th>
  </tr>
</table>
			
			<br>
<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_detalle(0)" ><i class="fas fa-save prefix grey-text mr-1"></i> Agregar Detalle</button>			
</div>
	
	</div>
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<div align="center" id="div3">			

	</div>
</div>

</form>
<script language="JavaScript">
$(document).ready(function() {
    $('.select2').select2();
	//----------------
	$("#txt_fecha").datepicker();
	combo0('<?php  echo date('d/m/Y');?>');
});
//------------------------------ PARA ELIMINAR
function eliminar(id, id_cont)
	{
	alertify.confirm("Estas seguro de eliminar el Registro?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "administracion/13h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			tabla(id_cont);
			}
			});
		});
	}
//--------------------- PARA BUSCAR
function tabla(id){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('administracion/14d_tabla.php?id='+id);
}
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_id_rif.value=="" || document.form999.txt_id_rif.value=="0")	
		{	alertify.alert("Debe Indicar el Rif");			error = 1;  }
	if(document.form999.txt_concepto.value=="")	
		{	 document.form999.txt_concepto.focus(); 	alertify.alert("Debe Indicar el Concepto");			error = 1;  }
	if(document.form999.txt_cantidad.value=="")	
		{	 document.form999.txt_cantidad.focus(); 	alertify.alert("Debe Indicar la Cantidad");			error = 1;  }
	if(document.form999.txt_detalle.value=="")		
		{	 document.form999.txt_detalle.focus();	alertify.alert("Debe Indicar la Descripcion");		error = 1;  }
	if(document.form999.txt_precio.value=="")		
		{	 document.form999.txt_precio.focus();		alertify.alert("Debe Indicar el Precio Unitario");	error = 1;  }
	return error;
	}
//--------------------------- PARA GUARDAR
function guardar_detalle2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		if (validar_detalle()==0)
			{
			$('#boton').hide();
			//Obtenemos datos formulario.
			var parametros = $("#form999").serialize(); 
			$.ajax({  
				type : 'POST',
				url  : 'administracion/14e_guardar.php',
				dataType:"json",
				data:  parametros, 
				success:function(data) {  	
					if (data.tipo=="info")
						{	
						alertify.success(data.msg);	tabla(data.id);  
						document.form999.txt_cantidad.value='';	
						document.form999.txt_detalle.value='';	
						document.form999.txt_precio.value='';	
						document.form999.txt_cantidad.focus();	
						$('#boton').show();
						}
					else
						{	alertify.alert(data.msg);	}
					}  
				});
			}
		}
	}
//--------------------------- PARA GUARDAR
function guardar_detalle()
 	 {
	 if (validar_detalle()==0)
		{
		$('#boton').hide();
		//Obtenemos datos formulario.
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'administracion/14e_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	tabla(data.id); $('#boton').show();}
				else
					{	alertify.alert(data.msg);	}
				}  
			});
		}
	}
//--------------------- PARA BUSCAR
function buscar_orden(){
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'administracion/14i_buscar.php',
		data: parametros,
		dataType:"json",
		success:function(data) {  
			if (data.tipo=="alerta")
				{	alertify.alert(data.msg);	}
			else
				{
				//document.form999.txt_control.value = data.control;
				//document.form999.txt_factura.value = data.factura;
				//document.form999.txt_fecha.value = data.fecha_factura;
				document.form999.txt_concepto.value = data.concepto;
				document.form999.txt_concepto.focus();
				tabla(data.id_rif);
				}
			}  
		});
}
//--------------------------------
$("#txt_precio").on({
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