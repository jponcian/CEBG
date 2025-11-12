<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$tipo = $_GET['tipo']; 
$valor = $_GET['valor']; 
$movimiento = $_GET['movimiento']; 
$orden = $_GET['orden']; 
?>
<form id="form888" name="form888" method="post" onsubmit="return evitar();" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Buscar<?php if ($tipo==1) {echo ' el monto '. formato_moneda($valor); } elseif ($tipo==2) {echo ' la referencia '. ($valor); }?> en las Ordenes de Pago 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
</div>

<?php if ($tipo>2) { ?>

	<input name="obuscar2" id="obuscar2" type="text" size="100" class="form-control" onKeyPress="buscar_op(event,this);" placeholder="Escriba para buscar por Contribuyente, Orden, Referencia o Monto" />
	
<?php } ?>
<div id="div3">
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="11" align="center">Ordenes de Pago en Sistema</td>
</tr>
<tr>
<td bgcolor="#CCCCCC" align="center"><strong>N</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong># Orden Pago</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Rif</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Contribuyente</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Referencia</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>OP</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>CP</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong></strong></td>
</tr><?php 	
////------ MONTAJE DE LOS DATOS
if 	($tipo==1)
	{
	$consultx = "SELECT ordenes_pago.id AS idop, ordenes_pago.estatus, ordenes_pago.tipo_solicitud, ordenes_pago.descripcion, ordenes_pago.id_contribuyente, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.iva, ordenes_pago.islr, ordenes_pago.total, ordenes_pago_pagos.monto, ordenes_pago_pagos.tipo_pago, ordenes_pago_pagos.banco, ordenes_pago_pagos.banco2, ordenes_pago_pagos.cuenta, ordenes_pago_pagos.cuenta2, ordenes_pago_pagos.chequera, ordenes_pago_pagos.num_pago, ordenes_pago_pagos.fecha_pago, ordenes_pago_pagos.id_chequera, ordenes_pago_pagos.id_cheque, ordenes_pago_pagos.contabilidad, contribuyente.rif, contribuyente.nombre FROM ordenes_pago, ordenes_pago_pagos, contribuyente WHERE ordenes_pago.id = ordenes_pago_pagos.id_orden AND	ordenes_pago.id_contribuyente = contribuyente.id AND  ordenes_pago.total='$valor' ORDER BY numero, LEFT(rif,1), total DESC;";
	}
	elseif 	($tipo==2)
	{
	$consultx = "SELECT ordenes_pago.id AS idop, ordenes_pago.estatus, ordenes_pago.tipo_solicitud, ordenes_pago.descripcion, ordenes_pago.id_contribuyente, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.iva, ordenes_pago.islr, ordenes_pago.total, ordenes_pago_pagos.monto, ordenes_pago_pagos.tipo_pago, ordenes_pago_pagos.banco, ordenes_pago_pagos.banco2, ordenes_pago_pagos.cuenta, ordenes_pago_pagos.cuenta2, ordenes_pago_pagos.chequera, ordenes_pago_pagos.num_pago, ordenes_pago_pagos.fecha_pago, ordenes_pago_pagos.id_chequera, ordenes_pago_pagos.id_cheque, ordenes_pago_pagos.contabilidad, contribuyente.rif, contribuyente.nombre FROM ordenes_pago, ordenes_pago_pagos, contribuyente WHERE ordenes_pago.id = ordenes_pago_pagos.id_orden AND	ordenes_pago.id_contribuyente = contribuyente.id AND ordenes_pago_pagos.num_pago LIKE '%$valor%' ORDER BY ordenes_pago_pagos.num_pago ASC, LEFT(rif,1) ASC, total DESC;";
	}
	else
	{
	$consultx = "SELECT ordenes_pago.*, ordenes_pago.id as idop, contribuyente.* FROM ordenes_pago, contribuyente WHERE ordenes_pago.id_contribuyente = contribuyente.id AND 1=2 ORDER BY numero, LEFT(rif,1), total DESC;";
	}
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0){} else {echo '<tr><td colspan="10" height="35" align="center" ><strong>No hay Resultados...</strong></td></tr>';}
while ($registro = $tablx->fetch_object())
	{
	$i++;
	$total += $registro->total;
	if ($registro->estatus<99 and $registro->estatus>=10) 
		{	$fecha_pago=voltea_fecha($registro->fecha_pago); 
		$num_pago=rellena_cero($registro->num_pago,8); 	}
	else
		{	$fecha_pago= ''; 
		$num_pago= ''; 	}
	if ($registro->estatus==99) 
		{ $num_pago= 'A.N.U.L.A.D.A'; }
	elseif ($registro->estatus<10) 
		{ $num_pago= 'SIN PAGO'; }
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><strong><?php echo rellena_cero($registro->numero,8); ?></strong></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><?php echo ($registro->rif); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="right" ><strong><?php echo $num_pago; ?></strong></div></td>
<td ><div align="center" ><?php echo $fecha_pago; ?></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->total); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Orden de Pago"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->idop); ?>','<?php echo ($registro->tipo_solicitud); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->estatus>=10 and $registro->estatus<99) { ?><a data-toggle="tooltip" title="Ver Comprobante de Pago"><button type="button" class="btn btn-outline-success waves-effect" onclick="imprimir2('<?php echo encriptar($registro->idop); ?>','<?php echo ($registro->tipo_solicitud); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a><?php } ?></div></td>
<td ><div align="center" ><?php if ($orden==$registro->idop) {echo '<div class="badge badge-success">Asignada</div>';} else {?><a data-toggle="tooltip" title="Asignar Orden de Pago"><button type="button" class="btn btn-outline-warning waves-effect" onclick="asignar_op('<?php echo encriptar($registro->idop); ?>');" >Asignar</button></a><?php } ?></div></td>
</tr>
 <?php 
 }
?>
<tr>
<td colspan="11" class="PieTabla">Alcaldia del Municipio Francisco de Miranda</td>
</tr>
</table>

</div>
	
</form>

<script language="JavaScript">
//---------------------------
function buscar_op(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		$('#div3').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
		$('#div3').load('contabilidad/9c1_tabla.php?valor='+cambia(document.form888.obuscar2.value));
		}
	}
//---------------------------
function asignar_op(id)
	{
	var parametros = $("#form1").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'contabilidad/9d_asignar.php?op='+id+'&movimiento=<?php echo encriptar($movimiento); ?>',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	busca_lista(); $('#modal_largo .close').click(); }
			else
				{	alertify.alert(data.msg);	}
			//--------------
			} 
		 
		});
	}				
</script>