<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$buscar = trim($_GET['buscar']);
if ($buscar <>'')	
	{
	$filtro = " AND (asistencia_diaria_visita.cedula like '%$buscar%' OR asistencia_diaria_visita.organismo like '%$buscar%' OR asistencia_diaria_visita.tipo like '%$buscar%' OR rac_visita.nombre like '%$buscar%') ";	//$_SESSION['titulo'] = 'POR VERIFICAR';
	} 
?>
<table class="table table-striped table-hover" bgcolor="#FFFFFF" width="100%" border="0" align="center">
<!--
<tr>
<td class="TituloTablaP" colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr>
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">VISITAS DIARIA</td>
</tr>--><thead>
<tr>
<td  bgcolor="#CCCCCC" align="left"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Cedula</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Nombre y Apellido</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Organismo</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Direccion</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Ingreso</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Salida</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong></strong></td>-->
</tr></thead>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT
	asistencia_diaria_visita.id,
	asistencia_diaria_visita.cedula,
	asistencia_diaria_visita.organismo,
	asistencia_diaria_visita.direccion,
	asistencia_diaria_visita.tipo,
	asistencia_diaria_visita.fecha,
	asistencia_diaria_visita.ingreso,
	asistencia_diaria_visita.salida,
	asistencia_diaria_visita.observacion,
	asistencia_diaria_visita.estatus,
	asistencia_diaria_visita.carnet,
	rac_visita.nombre 
FROM
	asistencia_diaria_visita,
	rac_visita 
WHERE
	asistencia_diaria_visita.id_direccion=4 and asistencia_diaria_visita.cedula = rac_visita.cedula AND fecha='".date('Y/m/d')."' $filtro ORDER BY estatus, fecha DESC, ingreso DESC;";
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div><?php //if ($registro->observacion<>'') { ?>
<!--	<div class="spinner-grow spinner-grow-sm" role="status"></div><?php //} ?></td>-->
<td ><div align="left" ><?php echo ($registro->cedula); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->nombre); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->organismo); ?></div></td>
<td ><div align="left" ><?php echo ($registro->direccion); ?></div></td>
<!--<td ><div align="left" ><?php //echo voltea_fecha($registro->fecha); ?></div></td>-->
<td ><div align="left" ><?php echo hora_militar($registro->ingreso); ?></div></td>
<td ><div align="left" ><?php echo hora_militar($registro->salida); ?></div></td>
<!--	class="badge badge-<?php //if ($registro->tipo=='ENTRADA') {echo 'info';} else {echo 'info';} ?>" -->



	<td align="center"><div><h5><button <?php if ($registro->estatus==0) { ?> onclick="datos('<?php echo encriptar($registro->cedula); ?>',1);" <?php } ?> type="button" class="badge badge-<?php if ($registro->estatus=='4') {echo 'success';} elseif ($registro->estatus=='1' or $registro->estatus=='2') {echo 'warning';} else {echo 'danger';} ?>" ><i class="<?php if ($registro->estatus=='4') {echo 'fa-regular fa-thumbs-up';} elseif ($registro->estatus=='1') {echo 'fa-solid fa-user-gear';} else {echo 'fa-solid fa-person-arrow-down-to-line';} ?>"></i> <?php if ($registro->estatus=='0') {echo 'INGRESÓ';} elseif ($registro->estatus=='1') {echo 'ATENDIENDO';} elseif ($registro->estatus=='2') {echo 'SALIENDO';} else {echo 'SALIÓ';} ?></button></h5></div></td>

	
<!--<td ><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger waves-effect" onclick="borrar('<?php //echo encriptar($registro->id); ?>');" ><i class="fas fa-trash-alt prefix grey-text mr-1"></i></button></a></div><?php } ?></td>-->
	
	</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>