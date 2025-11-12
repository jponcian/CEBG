<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
if ($_GET['tipo']=='1')	
	{
	$filtro = " numero='".($_GET['valor'])."' AND estatus=0 AND ";	
	} 
elseif ($_GET['tipo']=='2')	 
	{
	$filtro = " concepto like '%".($_GET['valor'])."%' AND estatus=0 AND ";	
	}
	elseif ($_GET['tipo']=='3')	 
		{	
		$filtro = " estatus=0 AND ";	
		}
		elseif ($_GET['tipo']=='4')	 
			{	
			$filtro = " estatus=5 AND ";	
			}
			elseif ($_GET['tipo']=='5')	 
				{	
				$filtro = " estatus>5 AND ";	
				}
				elseif ($_GET['tipo']=='8')	 
					{	
					$fecha1 = voltea_fecha($_GET['fecha1']);
					$fecha2 = voltea_fecha($_GET['fecha2']);
					$filtro = " fecha>='$fecha1' AND fecha<='$fecha2' AND ";
					}
?>

<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="11" align="center">Correspondencia Generadas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Destinatario</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Organismo</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Asunto</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td colspan="4" bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT cr_memos_dir_ext.*, a_direcciones.direccion as dir FROM	cr_memos_dir_ext, a_direcciones WHERE $filtro a_direcciones.id = cr_memos_dir_ext.direccion_origen ORDER BY	fecha DESC,	numero DESC;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->destinatario); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo ($registro->instituto); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo ($registro->asunto); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo rellena_cero($registro->numero,4); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo estatus_memo_div($registro->estatus); ?></strong></div></td>
	<td ><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Editar Correspondencia"><button data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"  type="button" class="btn btn-outline-warning waves-effect" onclick="editar('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-edit prefix grey-text mr-1"></i></button></a><?php } ?></div></td>

	<td ><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Eliminar Correspondencia"><button type="button" class="btn btn-outline-danger waves-effect" onclick="borrar('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-trash-alt prefix grey-text mr-1"></i></button></a><?php } ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Preliminar"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->estatus==0) { ?><button onclick="generar_memo('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($registro->direccion_origen); ?>','<?php echo encriptar($registro->anno); ?>','<?php echo encriptar($registro->firma_contralor); ?>');" type="button" id="boton<?php echo ($registro->id); ?>" class="btn btn-outline-success waves-effect"><i class="fa-regular fa-circle-check prefix grey-text mr-1"></i> Aprobar</button><?php } ?>
	<?php if ($registro->estatus>=5) { ?><button onclick="enviar_memo('<?php echo encriptar($registro->id); ?>');" type="button" id="boton<?php echo ($registro->id); ?>" class="btn btn-outline-success waves-effect"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Enviar</button><?php } ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="11" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>