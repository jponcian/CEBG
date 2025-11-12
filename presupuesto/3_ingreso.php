<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=67;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Decretos (Creditos Adicionales)</div>
		<br >
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Decreto</a></div>
<table> <tr>
      <th scope="row">Año</th>
      <td>
		<select class="custom-select" style="font-size: 14px" name="txt_anno" id="txt_anno" onchange="buscar();">
			<?php
			$i = date ('Y');
			while ($i>=2022)
			//-------------
			{
			echo '<option ';
			echo ' value="';
			echo $i;
			echo '">';
			echo $i;
			echo '</option>';
			$i--;
			}
			?>
		</select>
		</td>
    </tr>
</table>		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" onclick="ver();">
                N&uacute;mero</label>
            </div>
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" checked="checked" onclick="ver();">
                    Descripcion
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
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="ver();buscar();" >
                   Por Aprobar
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="ver();buscar();" >
                   Aprobados
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
//---------------------------
setTimeout(function()	{
	$('#obuscar').focus();
	},500)	
//---------------------------
function ver()
 	{
	document.form1.obuscar.value="";
	if (document.form1.optradio.value==1 || document.form1.optradio.value==2)
	 	{
		$('#cuadro').show();	$('#obuscar').focus();
		$('#fechas').hide();	
		}
	if (document.form1.optradio.value==5)
	 	{
		$('#cuadro').hide();
		$('#fechas').show();	$('#OFECHA').focus();
		}
	if (document.form1.optradio.value==3 || document.form1.optradio.value==4)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	}
//---------------------------
function rep()
 	{
	if (document.form1.optradio.value>0)
		{
			//{
			window.open("presupuesto/reporte/3_rep_decreto.php","_blank");
			//}
		//----------------
		}
	}
//------------------
function reversar_credito(id, boton)
	{
	alertify.confirm("Estas seguro de Reversar el Decreto?",  
	function()
		{
		$('#'+boton).hide();
		//alertify.alert('Espere mientras se actualiza la Solicitud...');
		var parametros = "id=" + id; 
		$.ajax({  
			type : 'POST',
			url  : 'presupuesto/3n_reversar.php',
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
		});
	}
//------------------
function anular_credito(id, boton)
	{
	alertify.confirm("Estas seguro de Anular el Decreto?",  
	function()
		{
		$('#'+boton).hide();
		//alertify.alert('Espere mientras se actualiza la Solicitud...');
		var parametros = "id=" + id; 
		$.ajax({  
			type : 'POST',
			url  : 'presupuesto/3m_anular.php',
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
		});
	}
//------------------
function generar_solicitud(numero, fecha, boton)
	{
	alertify.confirm("Estas seguro de realizar la Solicitud?",  
	function()
		{
		$('#'+boton).hide();
		var parametros = "numero=" + numero + '&fecha=' + fecha; 
		$.ajax({  
			type : 'POST',
			url  : 'presupuesto/3j_guardar.php',
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
		});
	}
//------------------------------ PARA ELIMINAR
function eliminar(id)
	{
	alertify.confirm("Estas seguro de eliminar el Registro?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "presupuesto/3h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			tabla();
			}
			});
		});
	}
//--------------------- PARA BUSCAR
function tabla(){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('presupuesto/3d_tabla.php?id='+document.form999.txt_control.value+'&fecha='+document.form999.txt_fecha.value);
}
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_concepto.value=="")	
		{	 document.form999.txt_concepto.focus(); 	alertify.alert("Debe Indicar el Concepto");			error = 1;  }
	if(document.form999.txt_control.value=="")	
		{	 document.form999.txt_control.focus(); 		alertify.alert("Debe Indicar el Numero del Oficio");			error = 1;  }
	if(document.form999.txt_partida.value=="0")	
		{	 document.form999.txt_partida.focus(); 		alertify.alert("Debe Seleccionar la Partida");			error = 1;  }
	if(document.form999.txt_categoria.value=="0")	
		{	 document.form999.txt_categoria.focus(); 	alertify.alert("Debe Seleccionar la Categoria");			error = 1;  }
	if(document.form999.txt_detalle.value=="")		
		{	 document.form999.txt_detalle.focus();		alertify.alert("Debe Indicar la Descripcion");		error = 1;  }
	if(document.form999.txt_precio.value=="")		
		{	 document.form999.txt_precio.focus();		alertify.alert("Debe Indicar el Monto");	error = 1;  }
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
			{guardar_detalle();}
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
			url  : 'presupuesto/3e_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
					if (data.tipo=="info")
						{	alertify.success(data.msg);	tabla();  
						combo(document.form999.txt_categoria.value);
						document.form999.txt_detalle.value='';	
						document.form999.txt_precio.value='';	
						document.form999.txt_precio.focus();	
						$('#boton').show();		}
					else
					{	alertify.alert(data.msg);	}
				}  
			});
		}
	}
//-------------
function combo0(fecha)
{
	$.ajax({
        type: "POST",
        url: 'presupuesto/3f_combo.php?fecha='+fecha,
        success: function(resp){
            $('#txt_categoria').html(resp);
        }
    });
}
//-------------
function combo(categoria)
{
	$.ajax({
        type: "POST",
        url: 'presupuesto/3c_combo.php?categoria='+categoria+'&partida=0&fecha='+document.form999.txt_fecha.value+'&numero='+document.form999.txt_control.value,
        success: function(resp){
            $('#txt_partida').html(resp);
        }
    });
}
//-----------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('presupuesto/3b_modal.php');
	}
//----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value!=3 && document.form1.optradio.value!=4 && document.form1.optradio.value!=5){}
else	{
		//valor = document.form1.obuscar.value; 
		//valor = valor.replace(/ /g, '_');
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('presupuesto/3a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value+'&anno='+document.form1.txt_anno.value);
		}
}
//----------------
function buscar2(){
	document.form1.optradio.value=3;
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('presupuesto/3a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo=3');
}
//---------------------
function imprimir(num, fecha)
	{	
	window.open("presupuesto/formatos/1_credito.php?num="+num+"&fecha="+fecha,"_blank");
	}
//---------------------
function imprimir2(id)
	{	
	window.open("presupuesto/formatos/2_decreto.php?id="+id,"_blank");
	}
</script>