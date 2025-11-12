<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=13;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
	<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Relaci&oacute;n de Pagos de Nomina</div>
		<br >
		
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" onclick="buscar()" >
                   Pendientes
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="buscar()" >
                   Aprobados y Solicitados
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar()" >
                   Recibidos
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="buscar();" >
                  <strong>Todas</strong>
                </label>
            </div>
        </diw>
	<br >
	<diw class="row ml-3">
            <strong>Filtrado:</strong>
           <div class="form-check ml-3">
            <div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="50" placeholder="Escriba aqui para Filtrar..." class="form-control" onKeyPress="buscar2(event,this);" /></div>
<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control fecha" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('01/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control fecha" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('30/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="buscar();"><i class="fas fa-search mr-2"></i>Buscar</button></td></tr></table></div></div>
        </diw>
	
 <br>
 <div id="div1"></div>
</form>
<script language="JavaScript">
//------------------
$('.fecha').dateRangePicker({
	autoClose: true,
	monthSelect: true,
    yearSelect: true,
	extraClass: 'date-range-picker19',
	format: 'DD/MM/YYYY',
	language:'es', 
	separator : ' al ',
	getValue: function()
	{
		if ($('#OFECHA').val() && $('#OFECHA2').val() )
			return $('#OFECHA').val() + ' to ' + $('#OFECHA2').val();
		else
			return '';
	},
	setValue: function(s,s1,s2)
	{
		$('#OFECHA').val(s1);
		$('#OFECHA2').val(s2);
	}
});

//------------------
function generar_solicitud(id, boton)
{
	$(boton).hide();
	//alertify.alert('Espere mientras se actualiza la Solicitud...');
	var parametros = "id=" + id; 
	$.ajax({  
		type : 'POST',
		url  : 'personal/2a_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	buscar();	}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			} 
		 
		});
}
//----------------
function buscar(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('personal/2b_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
}
//---------------------
function buscar2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{buscar();}
	}
//---------------------
function imprimir_sol(id, tipo, estatus)
	{	
	if (estatus=="1")
		{	window.open("personal/formatos/1_memo.php?id="+id,"_blank");	}
	if (tipo=="001" || tipo=="004" || tipo=="006")
		{	window.open("personal/formatos/2_nomina.php?id="+id+"&tipo="+tipo+"&estatus="+estatus,"_blank");
		window.open("personal/formatos/2p_nomina.php?id="+id+"&tipo="+tipo+"&estatus="+estatus,"_blank");}
	if (tipo=="002")
		{	window.open("personal/formatos/4_tickets.php?id="+id+"&estatus="+estatus,"_blank");	}
	if (tipo=="003" || tipo=="005")
		{	window.open("personal/formatos/3_vacaciones.php?id="+id+"&tipo="+tipo+"&estatus="+estatus,"_blank");	}
	if (tipo=="009")
		{	window.open("personal/formatos/9_fideicomiso.php?id="+id+"&tipo="+tipo+"&estatus="+estatus,"_blank");	}
	if (tipo=="013")
		{	window.open("personal/formatos/11_aguinaldos.php?id="+id+"&tipo="+tipo+"&estatus="+estatus,"_blank");	}
	}
</script>