<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$buscar = trim($_GET['buscar']);
if ($buscar <>'')	
	{
	$filtro = " AND (dacs_atencion.cedula like '%$buscar%' OR dacs_atencion.organismo like '%$buscar%' OR dacs_atencion.tipo like '%$buscar%' OR rac_visita.nombre like '%$buscar%') ";	//$_SESSION['titulo'] = 'POR VERIFICAR';
	} 
?>
<table class="table table-striped table-hover" bgcolor="#FFFFFF" width="100%" border="0" align="center">
<!--
<tr>
<td class="TituloTablaP" colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr><tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">TICKETS ABIERTOS (ATENCIÓN TELEFONICA)</td>
</tr>
--><thead>
<tr>
<td  bgcolor="#CCCCCC" align="left"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Cedula</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Nombre y Apellido</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Organismo</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Cargo</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Inicio</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fin</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong></strong></td>-->
</tr></thead>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT
	dacs_atencion.id,
	dacs_atencion.cedula,
	dacs_atencion.organismo,
	dacs_atencion.cargo,
	dacs_atencion.tipo,
	dacs_atencion.fecha,
	dacs_atencion.comienzo,
	dacs_atencion.fin,
	dacs_atencion.observacion,
	dacs_atencion.estatus,
	dacs_atencion.edad,
	rac_visita.nombre 
FROM
	dacs_atencion,
	rac_visita 
WHERE
	tipo=2 AND dacs_atencion.cedula = rac_visita.cedula AND fecha='".date('Y/m/d')."' $filtro ORDER BY estatus, fecha DESC, comienzo DESC;";
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
<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
<!--<td ><div align="left" ><?php //echo voltea_fecha($registro->fecha); ?></div></td>-->
<td ><div align="left" ><?php echo hora_militar($registro->comienzo); ?></div></td>
<td ><div align="left" ><?php echo hora_militar($registro->fin); ?></div></td>
<!--	class="badge badge-<?php //if ($registro->tipo=='ENTRADA') {echo 'info';} else {echo 'info';} ?>" -->



	<td align="center"><div><h5><button <?php if ($registro->estatus==0) { ?> onclick="cerrar('<?php echo encriptar($registro->cedula); ?>', '<?php echo encriptar($registro->id); ?>');" <?php } ?> type="button" class="badge badge-<?php if ($registro->estatus=='2') {echo 'success';} else {echo 'warning';} ?>" ><i class="<?php if ($registro->estatus=='2') {echo 'fa-regular fa-thumbs-up';} else {echo 'fa-solid fa-user-gear';} ?>"></i> <?php if ($registro->estatus=='0') {echo 'ATENDIENDO';} else {echo 'ATENDIDO';} ?></button></h5></div></td>

	
<!--<td ><div align="center" ><?php if ($registro->estatus==0) { ?><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger waves-effect" onclick="borrar('<?php //echo encriptar($registro->id); ?>');" ><i class="fas fa-trash-alt prefix grey-text mr-1"></i></button></a></div><?php } ?></td>-->
	
	</tr>
 <?php 
 }
 ?>
</table>