<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//-----------
$tabla = "";
$campo = "";
//-----------
if ($_GET['tipo']=='1')	
	{
	$filtro = " numero='".($_GET['valor'])."' AND estatus=0 AND a_direcciones.id = cr_memos_ext.direccion_destino ";	
	} 
elseif ($_GET['tipo']=='2')	 
	{
	$filtro = " (instituto like '%".($_GET['valor'])."%' or observacion like '%".($_GET['valor'])."%' or a_direcciones.direccion like '%".($_GET['valor'])."%' or origen like '%".($_GET['valor'])."%' or asunto like '%".($_GET['valor'])."%' or numero like '%".($_GET['valor'])."%') AND a_direcciones.id = cr_memos_ext.direccion_destino ";	
	}
	elseif ($_GET['tipo']=='3')	 
		{	
		$filtro = " estatus=0 AND a_direcciones.id = cr_memos_ext.direccion_destino ";	
		}
		elseif ($_GET['tipo']=='4')	 
			{	
			$filtro = " (estatus_recepcion=7) AND cr_memos_ext_destino.id_correspondencia=cr_memos_ext.id AND a_direcciones.id = cr_memos_ext_destino.direccion_destino ";
			$tabla = " , cr_memos_ext_destino ";
			$campo = " cr_memos_ext_destino.id as id_detalle, estatus_recepcion, ";
			}
//			elseif ($_GET['tipo']=='5')	 
//				{	
//				$filtro = " estatus=7 AND ";	
//				}
				elseif ($_GET['tipo']=='6')	 
					{	
					$filtro = " (estatus_recepcion=7) AND cr_memos_ext_destino.id_correspondencia=cr_memos_ext.id AND a_direcciones.id = cr_memos_ext_destino.direccion_destino ";
					$tabla = " , cr_memos_ext_destino ";	
					$campo = " cr_memos_ext_destino.id as id_detalle, estatus_recepcion, ";
					}
				elseif ($_GET['tipo']=='7')	 
					{	
					$filtro = " (estatus_recepcion>=10) AND cr_memos_ext_destino.id_correspondencia=cr_memos_ext.id AND a_direcciones.id = cr_memos_ext_destino.direccion_destino ";
					$tabla = " , cr_memos_ext_destino ";
					$campo = " cr_memos_ext_destino.id as id_detalle, estatus_recepcion, ";
					}
					elseif ($_GET['tipo']=='8')	 
						{	
						$fecha1 = voltea_fecha($_GET['fecha1']);
						$fecha2 = voltea_fecha($_GET['fecha2']);
						$filtro = " fecha>='$fecha1' AND fecha<='$fecha2' AND a_direcciones.id = cr_memos_ext.direccion_destino ";
						}
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="12" align="center">Correspondencia Generadas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Remitente</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Direccion Destino</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Asunto</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td colspan="5" bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT $campo cr_memos_ext.direccion_destino, cr_memos_ext.instituto, cr_memos_ext.asunto, cr_memos_ext.origen, cr_memos_ext.observacion, cr_memos_ext.id, cr_memos_ext.estatus, cr_memos_ext.numero, cr_memos_ext.anno,	cr_memos_ext.fecha, cr_memos_ext.direccion_destino as id_destino, a_direcciones.direccion as destino FROM cr_memos_ext, a_direcciones $tabla WHERE $filtro ORDER BY fecha DESC, numero DESC;";
//echo $consultx; //cr_memos_ext.direccion_destino='".$_SESSION["direccion"]."' AND
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++; ?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><strong><?php echo ($registro->numero); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->origen); ?> (<?php echo ($registro->instituto); ?>)</div></td>
<td ><div align="left" ><strong><?php echo ($registro->destino); ?></strong></div></td>
<td ><div align="left" ><a data-toggle="tooltip" title="<?php echo ($registro->observacion); ?>"><?php echo ($registro->asunto); ?></div></a></td>
<td ><div align="center" ><h5><span class="badge bg-primary"><strong><?php if ($registro->estatus_recepcion>0) { echo estatus_memo_ext($registro->estatus_recepcion); } else { echo estatus_memo_ext($registro->estatus); } ?></strong></span></h5></div></td>
<!--<td ><div align="center" ><?php //if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Editar Correspondencia"><button type="button" class="btn btn-outline-warning waves-effect" onclick="agregar('<?php //echo encriptar($registro->id); ?>');" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-edit prefix grey-text mr-1"></i></button></a><?php //} ?></div></td>
-->
	<td ><div align="center" ><?php if ($registro->estatus==0 and $registro->direccion_destino==99) { ?><a data-toggle="tooltip" title="Agregar Direcciones"><button onclick="direccion('<?php echo encriptar($registro->id); ?>');" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false" type="button" class="btn btn-outline-info waves-effect"><i class="fa-regular fa-building prefix grey-text mr-1"></i></button></a><?php } ?></div></td>
	
	<td ><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Editar Correspondencia"><button data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"  type="button" class="btn btn-outline-warning waves-effect" onclick="editar('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-edit prefix grey-text mr-1"></i></button></a><?php } ?></div></td>
	
	<td ><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Eliminar Correspondencia"><button type="button" class="btn btn-outline-danger waves-effect" onclick="borrar('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-trash-alt prefix grey-text mr-1"></i></button></a><?php } ?></div></td>
	
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Correspondencia"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo ($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
	
<td ><div align="center" ><?php if ($registro->estatus_recepcion==0) { ?><button onclick="aprobar_memo('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($registro->direccion_destino); ?>');" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false" type="button" class="btn btn-outline-success waves-effect"><i class="fa-solid fa-check prefix grey-text mr-1"></i> Aprobar</button><?php } ?>
	<?php if ($registro->estatus_recepcion>=5999 and $registro->estatus_recepcion<7) { ?><button onclick="enviar_memo('<?php echo encriptar($registro->id); ?>');" type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Enviar</button><?php } ?><?php if ($registro->estatus_recepcion==7999) { ?><button onclick="recibir_memo('<?php echo encriptar($registro->id); ?>', '<?php echo encriptar($registro->id_detalle); ?>');" type="button" class="btn btn-outline-success waves-effect"><i class="fa-solid fa-download prefix grey-text mr-1"></i> Recibir</button><?php } ?></div></td>
</tr>
 <?php 
 
	if ($registro->estatus>0 and $registro->estatus<99) { 
	
	?>
	<td colspan="12" ><div align="left" ><strong><?php echo '* '.($registro->observacion).' '; //----------
	$consultx1 = "SELECT * FROM cr_memos_ext_instruccion WHERE id_correspondencia = '".$registro->id."';";
	//echo $consultx1; //cr_memos_ext.direccion_destino='".$_SESSION["direccion"]."' AND
	$tablx1 = $_SESSION['conexionsql']->query($consultx1);
	//-------------
	while ($registro1 = $tablx1->fetch_object())
		{ echo '- '.($registro1->descripcion.' '.$registro1->complemento).' '; } ?></strong></div></td>
</tr>
<?php }} ?>
 <tr>
<td colspan="12" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>