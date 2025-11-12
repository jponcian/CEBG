<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//-----------
$tabla = "";
$campo = "";
//-----------
	
$filtro = " (estatus_recepcion=7) AND cr_memos_ext_destino.id_correspondencia=cr_memos_ext.id AND a_direcciones.id = cr_memos_ext_destino.direccion_destino ";
$tabla = " , cr_memos_ext_destino ";	
$campo = " cr_memos_ext_destino.id as id_detalle, estatus_recepcion, ";
				
?>
<table class="table table-hover" style="width: 90%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="12" align="center">CORRESPONDENCIA</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Remitente</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Asunto</strong></td>
<td colspan="5" bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT $campo cr_memos_ext.direccion_destino, cr_memos_ext.instituto, cr_memos_ext.asunto, cr_memos_ext.origen, cr_memos_ext.observacion, cr_memos_ext.id, cr_memos_ext.estatus, cr_memos_ext.numero, cr_memos_ext.anno,	cr_memos_ext.fecha, cr_memos_ext.direccion_destino as id_destino, a_direcciones.direccion as destino FROM cr_memos_ext, a_direcciones $tabla WHERE $filtro AND cr_memos_ext_destino.direccion_destino='".$_SESSION["direccion"]."' ORDER BY fecha DESC, numero DESC;";
//echo $consultx; //cr_memos_ext.direccion_destino='".$_SESSION["direccion"]."' AND
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++; ?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->origen); ?> (<?php echo ($registro->instituto); ?>)</div></td>
<td ><div align="center" ><strong><?php echo ($registro->numero); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="left" ><a data-toggle="tooltip" title="<?php echo ($registro->observacion); ?>"><strong><?php echo ($registro->asunto); ?></strong></div></a></td>

	<td ><div align="center" ><?php if ($registro->estatus==0 and $registro->direccion_destino==99) { ?><a data-toggle="tooltip" title="Agregar Direcciones"><button onclick="direccion('<?php echo encriptar($registro->id); ?>');" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false" type="button" class="btn btn-outline-info waves-effect"><i class="fa-regular fa-building prefix grey-text mr-1"></i></button></a><?php } ?></div></td>
	
	<td ><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Editar Correspondencia"><button data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"  type="button" class="btn btn-outline-warning waves-effect" onclick="editar('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-edit prefix grey-text mr-1"></i></button></a><?php } ?></div></td>
	
	<td ><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Eliminar Correspondencia"><button type="button" class="btn btn-outline-danger waves-effect" onclick="borrar('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-trash-alt prefix grey-text mr-1"></i></button></a><?php } ?></div></td>
	
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Correspondencia"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo ($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->estatus_recepcion==9990) { ?><button onclick="aprobar_memo('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($registro->direccion_destino); ?>');" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false" type="button" class="btn btn-outline-success waves-effect"><i class="fa-solid fa-check prefix grey-text mr-1"></i> Aprobar</button><?php } ?>
	<?php if ($registro->estatus_recepcion>=5999 and $registro->estatus_recepcion<7) { ?><button onclick="enviar_memo('<?php echo encriptar($registro->id); ?>');" type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Enviar</button><?php } ?><?php if ($registro->estatus_recepcion==7999) { ?><button onclick="recibir_memo('<?php echo encriptar($registro->id); ?>', '<?php echo encriptar($registro->id_detalle); ?>');" type="button" class="btn btn-outline-success waves-effect"><i class="fa-solid fa-download prefix grey-text mr-1"></i> Recibir</button><?php } ?></div></td>
</tr>
 <?php 
 
//	if ($registro->estatus>0 and $registro->estatus<99) { 
	
	?>
<!--	<tr>
	<td colspan="12" ><div align="left" ><strong>--><?php //echo '* '.($registro->observacion).' '; //----------
//	$consultx1 = "SELECT * FROM cr_memos_ext_instruccion WHERE id_correspondencia = '".$registro->id."';";
	//echo $consultx1; //cr_memos_ext.direccion_destino='".$_SESSION["direccion"]."' AND
//	$tablx1 = $_SESSION['conexionsql']->query($consultx1);
	//-------------
//	while ($registro1 = $tablx1->fetch_object())
		//{ echo '- '.($registro1->descripcion.' '.$registro1->complemento).' '; } ?><!--</strong></div></td>
</tr>-->
<?php } ?>
<!--
</table>
<table class="table table-hover" style="width: 90%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="13" align="center">Correspondencia Interna</td>
</tr>
-->
<!--
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Remitente</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Asunto</strong></td>
<td colspan="5" bgcolor="#CCCCCC" align="center"></td>
</tr>
-->
<?php //$i=0;	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT cr_memos_div.asunto, cr_memos_div.id, cr_memos_div.estatus, 	cr_memos_div.numero, cr_memos_div.anno,	cr_memos_div.fecha, cr_memos_div.direccion_origen as id_origen, cr_memos_div.direccion_destino as id_destino, a_direcciones2.direccion as destino, a_direcciones.direccion as origen FROM 	cr_memos_div, a_direcciones, a_direcciones AS a_direcciones2, cr_memos_div_destino WHERE estatus=10 AND cr_memos_div_destino.estatus_recepcion=0 AND  cr_memos_div_destino.id_correspondencia=cr_memos_div.id AND cr_memos_div.direccion_destino='".$_SESSION["direccion"]."' AND a_direcciones.id = cr_memos_div.direccion_origen AND cr_memos_div.direccion_destino = a_direcciones2.id ORDER BY fecha DESC, numero DESC;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->origen); ?></div></td>
<td ><div align="center" ><strong><?php echo rellena_cero($registro->numero,5); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo ($registro->asunto); ?></strong></div></td>

<td ><div align="center" ><?php if ($registro->estatus==0 and $registro->id_destino==99) { ?><a data-toggle="tooltip" title="Agregar Direcciones"><button onclick="direccion('<?php echo encriptar($registro->id); ?>');" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false" type="button" class="btn btn-outline-info waves-effect"><i class="fa-regular fa-building prefix grey-text mr-1"></i></button></a><?php } ?></div></td>

<td><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Editar Correspondencia"><button data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"  type="button" class="btn btn-outline-warning waves-effect" onclick="editar('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-edit prefix grey-text mr-1"></i></button></a><?php } ?></div></td>

<td ><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Eliminar Correspondencia"><button type="button" class="btn btn-outline-danger waves-effect" onclick="borrar('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-trash-alt prefix grey-text mr-1"></i></button></a><?php } ?></div></td>

<td ><div align="center" ><a data-toggle="tooltip" title="Preliminar"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir2('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
	
<td ><div align="center" ><?php if ($registro->estatus==0) { ?><button onclick="generar_memo('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($registro->id_origen); ?>','<?php echo encriptar($registro->anno); ?>');" type="button" id="boton<?php echo ($registro->id); ?>" class="btn btn-outline-success waves-effect"><i class="fa-regular fa-circle-check prefix grey-text mr-1"></i> Aprobar</button><?php } ?>
	<?php if ($registro->estatus>=5 and $registro->estatus<10) { ?><button onclick="enviar_memo('<?php echo encriptar($registro->id); ?>');" type="button" id="boton<?php echo ($registro->id); ?>" class="btn btn-outline-success waves-effect"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Enviar</button><?php } ?></div></td>
</tr>
 <?php 
 }
 ?>
</table>
<?php 	$ii=0;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT asistencia_diaria.id, asistencia_diaria.cedula, asistencia_diaria.direccion, asistencia_diaria.tipo, asistencia_diaria.fecha, asistencia_diaria.hora, asistencia_diaria.horario, asistencia_diaria.observacion, asistencia_diaria.estatus, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre FROM asistencia_diaria, rac WHERE  asistencia_diaria.id_direccion='".$_SESSION["direccion"]."' and estatus=1 AND asistencia_diaria.cedula = rac.cedula AND fecha='".date('Y/m/d')."' ORDER BY fecha DESC, hora DESC;";
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	{ 
?>
<table class="table table-striped table-hover" bgcolor="#FFFFFF" style="width: 90%" border="0" align="center">
<thead><tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">ASISTENCIA DIARIA</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="left"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Cedula</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Funcionario</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Direccion</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Hora</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Horario</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
</tr></thead>
<?php  $ii=0;
while ($registro = $tablx->fetch_object())
	{
	$ii++;
	?>
<tr >
<td><div align="center" ><?php echo ($ii); ?></div><?php if ($registro->observacion<>'') { ?><div class="spinner-grow spinner-grow-sm" role="status"></div><?php } ?></td>
<td ><div align="left" ><?php echo ($registro->cedula); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->nombre); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->direccion); ?></div></td>
<!--<td ><div align="left" ><?php //echo voltea_fecha($registro->fecha); ?></div></td>-->
<td ><div align="left" ><?php echo hora_militar($registro->hora); ?></div></td>
<!--	class="badge badge-<?php //if ($registro->tipo=='ENTRADA') {echo 'info';} else {echo 'info';} ?>" -->

	
<td align="center"><div>
 <?php if ($registro->observacion<>'') { ?> <a data-toggle="tooltip" title="<?php echo ($registro->observacion); ?>"> <?php } ?><h5><i class="<?php if ($registro->tipo=='ENTRADA') {echo 'fa-solid fa-person-arrow-down-to-line';} else {echo 'fa-solid fa-person-arrow-up-from-line';} ?>"></i> <?php if ($registro->tipo=='ENTRADA') {echo 'ENTRADA';} else {echo 'SALIDA';} ?></h5>
	<?php if ($registro->observacion<>'') { ?> </a> <?php } ?>
</div></td>

<td ><div align="left" ><?php echo hora_militar($registro->horario); ?></div></td>
	
<td align="center"><div><h5><button <?php if ($registro->estatus>0) { ?> data-toggle="modal" data-target="#modal_normal" onclick="motivo('<?php echo ($registro->id); ?>')" <?php } ?> type="button" class="badge badge-<?php if ($registro->estatus=='0') {echo 'success';} else {echo 'danger';} ?>" ><i class="<?php if ($registro->estatus=='0') {echo 'fa-regular fa-thumbs-up';} else {echo 'fa-solid fa-triangle-exclamation';} ?>"></i> <?php echo $_SESSION['asistencia'][$registro->estatus] ?></button></h5></div></td>

	</tr>
 <?php 
 }
 ?>
<!--
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
-->
</table>
<?php //---------- POR SI HAY DATOS 
}
?>
<script language="JavaScript">
	mensaje();
//-------------------------
function mensaje()
	{
	<?php if ($i>0 or $ii>0) { ?>
	Swal.fire({
//		  title: 'Importante!',
		  icon: 'warning',				
		<?php if ($i>0 and $ii>0) { echo "title: 'Posee $i archivos de Correspondencia por Leer y hay $ii Funcionario(s) con ingreso a las instalaciones fuera del horario...',";	} elseif ($i>0) { echo "title: 'Posee $i archivos de Correspondencia por Leer!!!',";	} elseif ($ii>0) { echo "title: 'Hay $ii Funcionario(s) con ingreso a las instalaciones fuera del horario...',";	} ?>
		  //timer: 5500,				
		  timerProgressBar: true,				
		  showDenyButton: false,
		  showCancelButton: false
		})
	<?php } ?>		
	}
</script>