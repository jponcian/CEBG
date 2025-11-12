<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=6;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Correspondencia por Direcciones</div>
		<br >
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Crear Nuevo</a></div>
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
             <!--<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" >
                N&uacute;mero</label>
            </div>-->
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" checked="checked" onclick="ver();" >
                    Busqueda
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="buscar();ver();" >
                   Pendiente
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar();ver();" >
                   Aprobadas
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="buscar();ver();" >
                   Enviados
                </label>
            </div>
 			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="8" onclick="ver();">
                   Por Fecha
                </label>
            </div>
       </diw>

	<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="80" placeholder="Escriba aqui para buscar..." class="form-control" onKeyPress="buscar();" /></div>
<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('01/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="buscar();"><i class="fas fa-search mr-2"></i>Buscar</button></td></tr></table></div>

 <br>
 
 <div id="div1"></div>
</form>
<script language="JavaScript">
//$('#cuadro').show();
//$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//---------------------
buscar2(); ver();
//---------------------------
function ver()
 	{
	if (document.form1.optradio.value==2 || document.form1.optradio.value==8)
	 	{
		$('#cuadro').show();
		$('#fechas').hide();
		}
	if (document.form1.optradio.value==8)
	 	{
		$('#cuadro').hide();
		$('#fechas').show();
		}
	if (document.form1.optradio.value!=2 && document.form1.optradio.value!=8)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	}
//----------------
function direccion(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('correspondencia/3g_modal.php?id='+id);
	}
//---------------------
function borrar(id)
	{
		Swal.fire({
		title: 'Estas seguro de eliminar el Memorando?',
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
			var parametros = "id=" + id;
					$.ajax({
					url: "correspondencia/3g_eliminar.php",
					type: "POST",
					data: parametros,
					success: function(r) {
						Swal.fire('Borrado!', 'El registro fue borrado.', 'success');
						buscar(); 
						}
					});
			//-----------------------
			}
		})
	}
//-----------------------
function editar(id)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('correspondencia/3b_modal.php?id='+id);
	}
//--------------------- PARA BUSCAR
function listar_bienes(){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('correspondencia/3d_tabla.php?id='+cambia(document.form999.txt_numero.value)+'&nombre='+cambia(document.form999.txt_bien.value)+'&origen='+(document.form999.txt_origen.value));
}
//------------------
function enviar_memo(id)
	{
	alertify.confirm("Estas seguro de Enviar el Memorando?",  
	function()
		{
		var parametros = "id=" + id; 
		$.ajax({  
			type : 'POST',
			url  : 'correspondencia/3k_guardar.php?',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	buscar(); 
						//window.open("correspondencia/formatos/memo_dir.php?p=2&origen=0&destino=0&estatus=5&id="+id,"_blank");
					}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
			 
			});
		});
	}
//------------------
function generar_memo(id, id_origen, anno)
	{
		Swal.fire({
		title: 'Estas seguro de Aprobar el Memorando?',
		text: "",
		icon: 'success',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, Aprobar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id + "&origen="+ id_origen+ "&anno="+ anno; 
			$.ajax({  
				type : 'POST',
				url  : 'correspondencia/3j_guardar.php?',
				dataType:"json",
				data:  parametros, 
				success:function(data) {  	
					if (data.tipo=="info")
						{	Swal.fire('Aprobado!', data.msg, 'success');
						 	buscar(); 
							window.open("correspondencia/formatos/memo_dir.php?p=2&id="+id,"_blank");
						}
					else
						{	alertify.alert(data.msg);	}
					//--------------
					} 

				});
			//-----------------------
			}
		})
	}
//------------------------------ PARA ELIMINAR
function guardar(id)
	{
	 if (validar_detalle()==0)
		{
		alertify.confirm("Estas seguro de Generar el Memorando?",  
		function()
				{ 
				var parametros = $("#form999").serialize();
				$.ajax({
				url: "correspondencia/3f_guardar.php",
				type: "POST",
				dataType:"json",
				data: parametros,
				success: function(data) {  	
				if (data.tipo=="info")
					{	$('#modal_largo .close').click();	alertify.success(data.msg);	buscar(); 
						window.open("correspondencia/formatos/memo_dir.php?p=1&id="+data.id,"_blank");
					}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
				});
			});
		}
	}
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_origen.value=="0")	
		{	 document.form999.txt_origen.focus(); 	alertify.alert("Debe Seleccionar el Area de Origen");			error = 1;  }
	if(document.form999.txt_destino.value=="0")	
		{	 document.form999.txt_destino.focus(); 	alertify.alert("Debe Seleccionar el Area de Destino");			error = 1;  }
	return error;
	}
//-------------
function combo()
{
	$.ajax({
        type: "POST",
        url: 'correspondencia/3c_combo.php?origen='+document.form999.txt_origen.value+'&id='+document.form999.oid.value,
        success: function(resp){
            $('#txt_destino').html(resp);
			//listar_bienes();
        }
    });
}
//----------------
function buscar2(){
	document.form1.optradio.value=3;
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('correspondencia/3a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo=3');
}
//----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value<3){}
else	{
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('correspondencia/3a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
		}
}
//-----------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('correspondencia/3b_modal.php');
	}
//---------------------
function imprimir(id)
	{	
	window.open("correspondencia/formatos/memo_dir.php?p=1&id="+id,"_blank");
	}
</script>