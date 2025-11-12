<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=58;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<div align="center" class="TituloP">Registro de Materiales y Suministros</div>
		<br ><div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar</a></div>

        <diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            
<!--
	 <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3">
                    Descripcion
                </label>
            </div>
-->
            		
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="busca_empleados()" >
                   Articulos de Trabajo
                </label>
            </div>			
            		
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="6" onclick="busca_empleados()" >
                   Suministros
                </label>
            </div>			
            		
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="busca_empleados()" >
                   Ver Todos
                </label>
            </div>			
        </diw>

	<br>
	
	<div class="buscador col-md-8 mb-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
        <input type="text" class="form-control" placeholder="Escriba aqui la informacion a buscar..." name="obuscar" id="obuscar" >
      </div>
    </div>

 <br>
<div id="div2"></div>
</form>
<script language="JavaScript">
//-------------------- PARA ELIMINAR
function eliminar(id)
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
				url: "almacen/4h_eliminar.php",
				type: "POST",
				data: parametros,
				success: function(r) {
				alertify.success('Item Eliminado Correctamente');
				//--------------
				busca_empleados();
				}
				});
			//-----------------------
			}
		})
	}
////---------------------------
//function rep()
// 	{
//	if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value==3){}
//	else	
//		{
//		window.open("almacen/reporte/1_almacen.php","_blank");
//		}
//	}
//--------------------------------
function guardar()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'almacen/4e_guardar.php?id='+ document.form999.oid.value,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					$('#modal_largo .close').click(); 
					busca_empleados();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//--------------------------------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('almacen/4b_modal.php');
	}
//----------------
function basicos(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('almacen/4b_modal.php?id='+id);
	}
//----------------
function busca_empleados()
	{
	if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value!=5 && document.form1.optradio.value!=6 && document.form1.optradio.value!=4 && document.form1.optradio.value!=7){}
	else	{
		$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div2').load('almacen/4f_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
			}
	}
</script>