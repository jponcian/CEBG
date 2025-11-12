<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=106;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>

<div class="text-right mb-3 mr-3" >
	<div data-toggle="modal" data-target="#modal_largo" data-keyboard="false" type="button" onclick="agregar_mov();" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold"><i class="fas fa-plus-circle" ></i> Agregar Movimiento</div>	
</div>

<div class="row ml-3" align="right">
	<div class="form-group col-sm-1"><strong>Banco:</strong></div>
	<div class="form-group col-sm-6">
		<a data-toggle="tooltip" title="BANCO">
            <select class="form-control" name="op_tipo1" id="op_tipo1" onChange="busca_lista();" >
             <?php
			$consulta_x = 'SELECT * FROM a_cuentas WHERE id;'; 
			//---------------
			$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
			while ($registro_x = $tabla_x->fetch_array())
				{
				echo '<option value='.$registro_x['id'].'>'.$registro_x['banco'].' '.$registro_x['cuenta'].' '.$registro_x['descripcion'].'</option>';
				}
			?>
            </select>
          </a>
	</div>
</div>
	
<div class="row mb-2 ml-3" align="right">
	<strong>Estatus:</strong>
	<div class="form-check ml-3">
		<label class="form-check-label">
		<input type="radio" class="form-check-input" id="op_tipo2" name="op_tipo2" value="1" onclick="busca_lista();">Por Conciliar</label>
	</div>
	<div class="form-check ml-3">
		<label class="form-check-label">
		<input type="radio" class="form-check-input" id="op_tipo2" name="op_tipo2" value="2" onclick="busca_lista();">Conciliado</label>
	</div>
	<div class="form-check ml-3">
		<label class="form-check-label">
		<input type="radio" class="form-check-input" id="op_tipo2" name="op_tipo2" value="3" onclick="busca_lista();" checked><strong>Todos</strong></label>
	</div>
</div>
	
        <diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" id="optradio" name="optradio" value="2" onclick="ver();">
                Referencia</label>
            </div>
           
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="ver();">
                Monto</label>
            </div>
           
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" checked onclick="ver();busca_lista();" >
                   <strong>Dia Actual</strong>
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="ver();">
                   Por Fecha
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="6" onclick="ver();busca_lista();" >
                   Ver Todas
                </label>
            </div>
			
        </diw>

<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onKeyPress="buscar(event,this);" /></div>
<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="busca_lista();"><i class="fas fa-search mr-2"></i>Buscar</button></td></tr></table></div>
	
 <br>
<div id="div1"></div>
</form>
<script language="JavaScript">
//---------------------
busca_lista();
$('#cuadro').hide();
$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
$('#divcombos').hide();
//---------------------
function imprimir(id, tipo)
	{	
	if (tipo=="FINANCIERA")
		{	window.open("administracion/formatos/1b_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="ORDEN" || tipo=="MANUAL")
		{	window.open("administracion/formatos/1a_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/1_orden_pago.php?id="+id,"_blank");	}
	}
//---------------------
function imprimir2(id, tipo)
	{	
	if (tipo=="ORDEN" || tipo=="MANUAL")
		{	window.open("administracion/formatos/2a_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="FINANCIERA")
		{	window.open("administracion/formatos/2b_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/2_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="CHEQUE")
		{	window.open("administracion/formatos/3_cheque.php?id="+id,"_blank");	}
	}	
//---------------------
function imprimir3(id, tipo)
	{	
//	if (tipo=="ORDEN" || tipo=="MANUAL")
//		{	
			window.open("contabilidad/formatos/2_nota_debito.php?id="+id,"_blank");	
//		}
	}	
//----------------
function posicion(id)
	{
	//alertify.confirm("Estas seguro de eliminar el Movimiento?",  
	//function()
			//{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "contabilidad/9k_posicion.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Actualizado Correctamente');
			//--------------
			busca_lista();
			}
			});
		//});
}
//----------------
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
			url: "contabilidad/9j_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			busca_lista();
			}
			});
			//-----------------------
			}
		})
}
//----------------
function agregar_mov()
	{
	$('#modal_lg').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#modal_lg').load('contabilidad/9e_modal_mov.php');
	}
//----------------
function agregar_excel()
	{
	$('#modal_lg').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#modal_lg').load('contabilidad/9e_modal_excel.php');
	}
//---------------------------
function rep()
 	{
	window.open("contabilidad/reporte/10_estado_cuenta.php","_blank");
	}
//---------------------------
function buscar(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{busca_lista();}
	}
//----------------
function busca_lista()
	{
	$('#div1').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#div1').load('contabilidad/9a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&tipo1='+document.form1.op_tipo1.value+'&tipo2='+document.form1.op_tipo2.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
	}
//---------------------------
function ver()
 	{
	$('#cuadro').hide();
	$('#fechas').hide();
	if (document.form1.optradio.value==2 || document.form1.optradio.value==3)
	 	{
		$('#cuadro').show();
		}
	if (document.form1.optradio.value==5 || document.form1.optradio.value==7)
	 	{
		$('#fechas').show();
		}
	if (document.form1.optradio.value==7)
	 	{
		document.form1.op_tipo2.value=2;
		}
	}
//---------------------------
function sinc_op()
 	{
	if (document.form1.optradio.value==5)
	 	{
		$('#boton1a').hide();
		var parametros = $("#form1").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'contabilidad/9b_procedimiento.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	busca_lista();	$('#boton1a').show(); }
			else
				{	alertify.alert(data.msg);	}
			//--------------
			} 
		 
		});
		}
	else
		{
		alertify.alert("Debe Seleccionar un Rango de Fechas!");	
		}
	}
</script>