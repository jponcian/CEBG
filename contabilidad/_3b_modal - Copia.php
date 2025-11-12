<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=74;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT Sum(orden.total) as total, left(orden.rif,1) as letra FROM orden , orden_solicitudes , ordenes_pago WHERE ordenes_pago.id= '".$_GET['id']."' AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden.id_solicitud = orden_solicitudes.id GROUP BY ordenes_pago.id"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();
$total = $registro_x->total;
$letra = ($registro_x->letra);
//---------
$consultx = "SELECT Sum(orden.total) as iva FROM orden , orden_solicitudes , ordenes_pago WHERE ordenes_pago.id= '".$_GET['id']."' AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden.id_solicitud = orden_solicitudes.id AND left(orden.partida,9) = '403180100' GROUP BY ordenes_pago.id"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();
$iva = $registro_x->iva;
//---------
$consultx = "SELECT orden_solicitudes.factura, orden_solicitudes.control, orden_solicitudes.fecha_factura FROM orden , orden_solicitudes , ordenes_pago WHERE ordenes_pago.id= '".$_GET['id']."' AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden.id_solicitud = orden_solicitudes.id"; //echo $consultx;
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();
$factura = $registro_x->factura;
$control = $registro_x->control;
$fecha = $registro_x->fecha_factura;
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
   <input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>"/>
   <input type="hidden" id="oid2" name="oid2" value="<?php echo $_GET['id']; ?>"/>
   <input type="hidden" id="osubtotal" name="osubtotal" value="<?php echo $total-$iva; ?>"/>
   <input type="hidden" id="oiva" name="oiva" value="<?php echo $iva; ?>"/>
   <input type="hidden" id="txt_letra" name="txt_letra" value=""/>
   <input type="hidden" id="txt_islr" name="txt_islr" value=""/>
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Retenciones
<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
<div class="p-1">	
<br>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td bgcolor="#CCCCCC" align="center"><strong>Compromiso:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>N&deg; Factura</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>N&deg; Control</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha Factura</strong></td>
</tr>
<?php 	
$islr='no'; $cant_islr = 0;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT id, factura, control, fecha_factura, numero, tipo_orden FROM orden_solicitudes WHERE orden_solicitudes.id_orden_pago='".$_GET['id']."'"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	
	$i++;
	if ($registro->tipo_orden=='1')		{$tipo='C'.rellena_cero($registro->numero,6);}
	if ($registro->tipo_orden=='2')		
		{$tipo='S'.rellena_cero($registro->numero,6); $islr='si'; $cant_islr++;	}
	if ($registro->tipo_orden=='3')		{$tipo='M'.rellena_cero($registro->numero,6);}
	?>
<tr >
<!--<td align="center" valign="middle"><?php //echo ($i); ?></td>-->
<td align="left" valign="middle"><?php echo ($tipo); ?></td>
<td><input id="txt_factura<?php echo ($registro->id); ?>" placeholder="Numero Factura" name="txt_factura<?php echo ($registro->id); ?>" class="form-control" type="text" style="text-align:right" value="<?php echo ($registro->factura); ?>"/></td>
<td><input id="txt_control<?php echo ($registro->id); ?>" placeholder="Numero Control" name="txt_control<?php echo ($registro->id); ?>" class="form-control" type="text" style="text-align:right" value="<?php echo ($registro->control); ?>"/></td>
<td><input id="txt_fecha<?php echo ($registro->id); ?>" placeholder="Fecha Factura" name="txt_fecha<?php echo ($registro->id); ?>" class="form-control" type="text" style="text-align:center" readonly="" value="<?php echo voltea_fecha($registro->fecha_factura); ?>"/></td>
</tr>
 <?php 
	 echo '<script language="JavaScript">$("#txt_fecha'.($registro->id).'").datepicker();</script>';
	}
?>
<tr><td colspan="5">
	<div align="center">			
		<button type="button" id="boton2" class="btn btn-outline-success waves-effect" onclick="guardar_facturas(<?php echo $_GET['id']; ?>)" ><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar Cambios</button>			
	</div>
</td></tr>
</table>
<br>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<!--<td bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
--><td bgcolor="#CCCCCC" ><strong></strong></td>
<td bgcolor="#CCCCCC" ><strong>Imputacion Presup.</strong></td>
<td bgcolor="#CCCCCC" ><strong>Descripci&oacute;n:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Cantidad:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Precio Uni:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
</tr>
<?php 	
$i=0;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT orden_solicitudes.tipo_orden, orden.categoria, orden.partida, orden.cantidad, orden.descripcion, orden.precio_uni, orden.total, exento FROM orden , orden_solicitudes , ordenes_pago WHERE ordenes_pago.id= '".$_GET['id']."' AND orden_solicitudes.id_orden_pago = ordenes_pago.id AND orden.id_solicitud = orden_solicitudes.id"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//--------
	if ($registro->tipo_orden==2 and substr($registro->partida,0,9)<>'403180100')	
		{$monto_islr += $registro->total; }
	if ($registro->exento==1)	
		{$exento=' (e)';	$monto_exento+= $registro->total; }
		else	{$exento='';}
	?>
<tr >
<!--<td align="center"><div align="center" ><?php //echo ($i); ?></div></td>-->
<td ><?php echo $_SESSION['tipo_orden2'][($registro->tipo_orden)]; ?></td>
<td ><?php echo ($registro->categoria.'-'.$registro->partida); ?></td>
<td ><?php echo ($registro->descripcion.$exento); ?></td>
<td align="center" ><?php echo ($registro->cantidad); ?></td>
<td align="right" ><?php echo formato_moneda($registro->precio_uni); ?></td>
<td align="right" ><?php echo formato_moneda($registro->total); ?></td>
</tr>
 <?php 
 }
 ?>
</table>
<br>
	<input id="txt_cant_islr" name="txt_cant_islr" type="hidden" value="<?php echo ($cant_islr); ?>"/>
	<input id="txt_monto_islr" name="txt_monto_islr" type="hidden" value="<?php echo ($monto_islr); ?>"/>
<div class="row">
	<div class="form-group col-sm-3" align="center">
		<strong>Monto Exento</strong><input id="txt_exento" placeholder="Monto Exento" name="txt_exento" class="form-control" type="text" style="text-align:right" readonly="" value="<?php echo formato_moneda($monto_exento); ?>"/>
	</div>
	<div class="form-group col-sm-3" align="center">
		<strong>Sub-Total</strong><input id="txt_subtotal" placeholder="Sub-Total" name="txt_subtotal" class="form-control" type="text" style="text-align:right" readonly="" value="<?php echo formato_moneda($total-$iva); ?>"/>
	</div>
	<div class="form-group col-sm-3" align="center">
		<strong>IVA</strong><input id="txt_iva" placeholder="IVA" name="txt_iva" class="form-control" type="text" style="text-align:right" readonly="" value="<?php echo formato_moneda($iva); ?>"/>
	</div>
	<div class="form-group col-sm-3" align="center">
		<strong>TOTAL</strong><input id="txt_total" placeholder="Total" name="txt_total" class="form-control" type="text" style="text-align:right" value="<?php echo formato_moneda($total); ?>" readonly=""/>
	</div>
</div>
	
<table width="100%" border="1">
  <tr>
    <th width="50%" scope="col">
	<select onchange="calcular();islr();" class="custom-select" style="font-size: 14px" name="txt_retencion" id="txt_retencion" >
	</select></th>
    <th scope="col"><input onfocus="this.select()" onchange="calcular();" id="txt_porcentaje" name="txt_porcentaje" placeholder="%" class="form-control" type="text" style="text-align:center"  />
	<input id="txt_porcentaje2" name="txt_porcentaje2" type="hidden" /></th>
    <th width="20%" scope="col"><input readonly onkeyup="guardar_detalle2(event);" onfocus="this.select()" id="txt_monto" name="txt_monto" placeholder="Monto" class="form-control" type="text" style="text-align:center" /></th>
  </tr>  
</table>
<div id="div_islr">
<table width="100%" border="1">
<tr>
	<th scope="col" colspan="2">
	<select onchange="montar_codigos(this.value);" class="custom-select" style="font-size: 14px" name="txt_tipo_islr" id="txt_tipo_islr" >
	</select></th>
	    <th width="20%" scope="col"><input readonly type="text" class="form-control" id="txt_sustraendo" name="txt_sustraendo" placeholder="Sustraendo" style="text-align: center" value=""/></th>

</tr>
</table></div>
			<br>
<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_detalle(0)" ><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Agregar Retencion</button>			
</div>
	
	</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<div align="center" id="div3">			

	</div>
</div>

</form>
<script language="JavaScript">
document.form999.txt_porcentaje.disabled = true;
$('#div_islr').hide(); tabla2(); 
combo('<?php echo $_GET['id']; ?>','<?php echo $islr; ?>','<?php echo $iva; ?>');
document.form999.txt_islr.value='<?php echo $islr; ?>';
document.form999.txt_letra.value='<?php echo $letra; ?>';
//----------------- 
function montar_codigos(codigo)
	{
	$.ajax({  
	type : 'POST',
	url  : 'administracion/5i_buscar.php?codigo='+codigo,
	dataType:"json",
	//data:  parametros, 
	success:function(data) {  	
	if (data.tipo=="info")
		{	
		document.form999.txt_porcentaje.value = data.porcentaje;	
		document.form999.txt_porcentaje2.value = data.porcentaje;	
		document.form999.txt_sustraendo.value = data.sustraendo * document.form999.txt_cant_islr.value;
		document.form999.txt_monto.value = (document.form999.txt_monto_islr.value * document.form999.txt_porcentaje.value)/100;
		document.form999.txt_monto.value = document.form999.txt_monto.value - document.form999.txt_sustraendo.value;
		document.form999.txt_monto.value = number_format(document.form999.txt_monto.value,2);
		}
					}  
			});
	}

//----------------- 
function islr()
	{
	//document.form999.txt_sustraendo.value=valor;
	if (document.form999.txt_retencion.value==6)
		{
		$('#div_islr').show();
		$.ajax({
        type: "POST",
        url: 'administracion/5c1_combo.php?letra='+document.form999.txt_letra.value,
        success: function(resp){
            $('#txt_tipo_islr').html(resp);
				}
			});
		}
	else
		{$('#div_islr').hide();}
	}
//----------------- 
function combo(id,islr,iva)
{
	$.ajax({
        type: "POST",
        url: 'administracion/5c_combo.php?id='+id+'&islr='+islr+'&iva='+iva,
        success: function(resp){
            $('#txt_retencion').html(resp);
        }
    });
}
//--------------------------- PARA GUARDAR
function guardar_facturas(id)
 	 {
	$('#boton2').hide();
	//Obtenemos datos formulario.
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'administracion/5h_guardar.php?id='+id,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	
				alertify.success(data.msg);
				$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
				$('#modal_lg').load('administracion/5b_modal.php?id='+id);	
				$('#div_islr').hide();
				}
			else
				{	
				alertify.alert(data.msg);
				$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
				$('#modal_lg').load('administracion/5b_modal.php?id='+id);	
				$('#div_islr').hide();
				}
			}  
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
			url: "administracion/5g_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			combo('<?php echo $_GET['id']; ?>', document.form999.txt_islr.value, document.form999.oiva.value);
			tabla2();
			$('#div_islr').hide();
			}
			});
		});
	}
//----------------- PARA VALIDAR
function calcular()
	{
	document.form999.txt_porcentaje2.value=document.form999.txt_porcentaje.value;
	if (document.form999.txt_retencion.value==7)
		{
		if (document.form999.txt_porcentaje.value=='' || document.form999.txt_porcentaje.value=='1')
			{	document.form999.txt_porcentaje.disabled = false;
				document.form999.txt_porcentaje.value=75; 
			 	document.form999.txt_porcentaje2.value=75;	}
		else
			{	document.form999.txt_porcentaje.disabled = true;	}
				document.form999.txt_monto.value = (document.form999.oiva.value * document.form999.txt_porcentaje.value)/100;
		}
	else
		{
		if (document.form999.txt_retencion.value==6)
			{
			alertify.alert("Debe Seleccionar el Codigo del Tipo de Retencion de ISLR!");
			}
		else
			{
			if (document.form999.txt_retencion.value==8)
				{ document.form999.txt_porcentaje.value=1; 		
				 document.form999.txt_porcentaje2.value=1; 		
				 document.form999.txt_monto.value =(document.form999.osubtotal.value * document.form999.txt_porcentaje.value)/100;
				}
			else
				{ 
				if (document.form999.txt_porcentaje.value=='' || 	document.form999.txt_porcentaje.value==' ')
					{
					document.form999.txt_porcentaje.value=1; 		
					document.form999.txt_porcentaje2.value=1; 		
					}
				document.form999.txt_monto.value =(document.form999.osubtotal.value * document.form999.txt_porcentaje.value)/100;
				document.form999.txt_porcentaje.disabled = false;
				}
			}
		}
	//---------
	document.form999.txt_monto.value = number_format(document.form999.txt_monto.value,2);
	}
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_retencion.value=="0")	
		{	 document.form999.txt_retencion.focus(); 	alertify.alert("Debe Seleccionar la Retencion");	error = 1;  }
	if(document.form999.txt_porcentaje.value=="" || document.form999.txt_monto.value=="")		
		{	 //document.form999.txt_porcentaje.focus();		
			alertify.alert("Calcular la Retencion...");	error = 1;  }
	return error;
	}
//--------------------------- PARA GUARDAR
function guardar_detalle2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		guardar_detalle();
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
			url  : 'administracion/5f_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	tabla2(); $('#boton').show();
						document.form999.txt_monto.value = 0;
						document.form999.txt_porcentaje.value='';	
						document.form999.txt_porcentaje2.value='';	
						combo(data.id, document.form999.txt_islr.value, document.form999.oiva.value); 	
						document.form999.txt_retencion.focus();		
						$('#div_islr').hide();
					}
				else
					{	alertify.alert(data.msg);	}
				}  
			});
		}
	}
//--------------------------------
function tabla2(){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('administracion/5e_tabla.php?id='+document.form999.oid2.value);
}
//--------------------------------
$("#txt_monto").on({
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