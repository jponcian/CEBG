<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=56;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Despachar Solicitudes</div>
		<br >
<!--<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Generar Solicitud</a></div>
-->		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
             <!--<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" >
                N&uacute;mero</label>
            </div>-->
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2"  onClick="ver();" >
                    Fecha
                </label>
            </div>
	
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" checked="checked" onclick="ver();buscar()" >
                   Pendiente
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="ver();buscar()" >
                   Despachadas
                </label>
            </div>
			<!--<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar()" >
                   Ver Todos
                </label>
            </div>-->
        </diw>

<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onKeyPress="buscar2(event,this);" /></div>

<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="buscar();"><i class="fas fa-search mr-2"></i>Buscar</button></td></tr></table></div>

 <br>
 
 <div id="div1"></div>
</form>
<script language="JavaScript">
buscar2();
$('#cuadro').hide();
$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//---------------------------
function ver()
 	{
	if (document.form1.optradio.value!=2)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	if (document.form1.optradio.value==2)
	 	{
		$('#cuadro').hide();
		$('#fechas').show();
		}
	}
//--------------------- PARA BUSCAR
function listar_bienes(id){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('almacen/6d_tabla.php?id='+id);
}
//------------------
function generar_solicitud(id)
	{
	Swal.fire({
	title: '¿Estas seguro de Despachar la Solicitud?',
	//text: "Esta acción no se puede revertir!",
	icon: 'success',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	confirmButtonText: '<i class="fa fa-thumbs-up"></i>, Despachar!',
	cancelButtonText: 'Cancelar'
	}).then((result) => {
	if (result.isConfirmed) {
		//-----------------------
		var parametros = "id=" + id; 
		$.ajax({  
			type : 'POST',
			url  : 'almacen/6j_guardar.php?',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	$('#modal_normal .close').click();	alertify.success(data.msg);	buscar(); 
						window.open("almacen/formatos/solicitud.php?origen=0&estatus=5&id="+id,"_blank");
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
function reasignar(id, cantidad)
	{
	var parametros = "id=" + id + "&cantidad="+cantidad;
	$.ajax({
	url: "almacen/6f_reasignar.php",
	type: "POST",
	data: parametros,
	success: function(r) {
	alertify.success('Registro Actualizado Correctamente');
	//--------------
	//listar_bienes();
			}
		});
	}
//--------------------------- PARA GUARDAR
function agregar2(e, id, cantidad)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{ reasignar(id, cantidad); }
	}
//--------------------------- PARA GUARDAR
function listar_bienes2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{listar_bienes();}
	}
//-------------
function combo2()
{
	$.ajax({
        type: "POST",
        url: 'almacen/6c_combo2.php?origen='+document.form999.txt_origen.value,
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
	$('#div1').load('almacen/6a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo=3');
}
//----------------
function buscar(){
$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
$('#div1').load('almacen/6a_tabla.php?fecha1='+cambia(document.form1.OFECHA.value)+'&tipo='+document.form1.optradio.value+'&fecha2='+document.form1.OFECHA2.value);
}
//-----------------------
function agregar(id)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('almacen/6b_modal.php?id='+id);
	}
//---------------------
function imprimir(origen, estatus, id)
	{	
	window.open("almacen/formatos/solicitud.php?origen="+origen+"&estatus="+estatus+"&id="+id,"_blank");
	}
</script>