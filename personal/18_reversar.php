<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=94;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
 <div align="center" class="TituloP">Consultar Nominas Generadas</div>
		<br >
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" onclick="ver();">
                Nomina</label>
            </div>
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" onclick="ver();" checked="checked" >
                    Descripcion
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="ver();">
                   Por Fecha
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="ver();buscar();" >
                   Ver Todas
                </label>
            </div>
			
        </diw>

<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" /></div>
<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="buscar();"><i class="fas fa-search mr-2"></i>Buscar</button></td></tr></table></div>

 <br>
 
 <div id="div1"></div>
 </form>
<script language="JavaScript">
$('#cuadro').show();
$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//----------------------
function anular(id_solicitud)
	{
	alertify.confirm("Estas seguro de reversar la Solicitud de Pago?", function()
		{
		var parametros = "id_solicitud=" +id_solicitud;
		$.ajax({  
		type : 'POST',
		url  : 'personal/18b_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	buscar();}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			}  
		});
		});
	}
//---------------------
function imprimir_sol(id, tipo)
	{	
	window.open("personal/formatos/1_memo.php?id="+id,"_blank");
	if (tipo=="001" || tipo=="004" || tipo=="006")
		{	window.open("personal/formatos/2_nomina.php?id="+id+"&estatus=1","_blank");	}
	if (tipo=="002")
		{	window.open("personal/formatos/4_tickets.php?id="+id+"&estatus=1","_blank");	}
	if (tipo=="003" || tipo=="005")
		{	window.open("personal/formatos/3_vacaciones.php?id="+id+"&estatus=1","_blank");	}
	}
//---------------------------
function ver()
 	{
	if (document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5)
	 	{
		$('#cuadro').show();
		$('#fechas').hide();
		}
	if (document.form1.optradio.value==3)
	 	{
		$('#cuadro').hide();
		$('#fechas').show();
		}
	if (document.form1.optradio.value==4)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	}
//----------------
function buscar(){
if (((document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5) && document.form1.obuscar.value!='') || (document.form1.optradio.value==3 && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='') || (document.form1.optradio.value==4))
	{
	$('#div1').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#div1').load('personal/18a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
	}
}
</script>