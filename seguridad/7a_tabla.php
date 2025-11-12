<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//-----------
?>
<table class="table table-striped table-hover" bgcolor="#FFFFFF" width="100%" border="0" align="center">
<thead><tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Bienes en Calidad de Préstamo</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="left"><strong>N:</strong></td>
<!--<td  bgcolor="#CCCCCC" align="left"><strong>Cedula</strong></td>-->
<td  bgcolor="#CCCCCC" align="left"><strong>Funcionario</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>N°_Bien</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Bien Nacional</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fecha / Hora</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>-->
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Horario</strong></td>-->
<td  bgcolor="#CCCCCC" align="center"><strong></strong></td>
<!--<td bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>-->
</tr></thead>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT	bn_prestamos.tipo, bn_prestamos.id, bn_bienes.id_bien,	bn_bienes.numero_bien,	bn_bienes.descripcion_bien,	asistencia_diaria.fecha,	asistencia_diaria.hora,	rac.cedula,	CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre,	asistencia_diaria.id as ida,	asistencia_diaria.cargo FROM 	asistencia_diaria,	bn_prestamos, bn_bienes, rac WHERE bn_bienes.prestamo = 1 AND bn_prestamos.id_bien = bn_bienes.id_bien AND asistencia_diaria.id = bn_prestamos.id_asistencia AND asistencia_diaria.cedula = rac.cedula  ORDER BY bn_prestamos.id DESC ;";//DESC AND asistencia_diaria.fecha = '".date('Y/m/d')."'
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div><?php if ($registro->observacion<>'') { ?><a data-toggle="tooltip" title="<?php echo ($registro->observacion); ?>"><div class="spinner-grow spinner-grow-sm" role="status"></div></a> <?php } ?></td>
<!--<td ><div align="left" ><?php //echo ($registro->cedula); ?></div></td>-->
<td ><strong><?php echo ($registro->nombre); ?></strong></td>
<td ><div align="left" ><?php echo ($registro->numero_bien); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion_bien); ?></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha). ' ' .hora_militar($registro->hora); ?></div></td>
	
<!--<td align="center"><div><h5 ><?php //if ($registro->tipo=='1') {echo 'ENTRADA';} else {echo 'SALIDA';} ?></h5></div></td>-->

<td ><div align="center" ><a data-toggle="tooltip" title="Ingresar"><button type="button" class="btn btn-outline-success waves-effect" onclick="devolver('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($registro->id_bien); ?>','<?php echo encriptar($registro->numero_bien); ?>');" >Ingresar</button></a></div></td>
	
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
<table class="table table-striped table-hover" bgcolor="#FFFFFF" width="100%" border="0" align="center">
<thead><tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Movimientos</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="left"><strong>N:</strong></td>
<!--<td  bgcolor="#CCCCCC" align="left"><strong>Cedula</strong></td>-->
<td  bgcolor="#CCCCCC" align="left"><strong>Funcionario</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>N°_Bien</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Bien Nacional</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fecha / Hora</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Horario</strong></td>-->
<td  bgcolor="#CCCCCC" align="center"><strong></strong></td>
<!--<td bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>-->
</tr></thead>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT	bn_prestamos.tipo, bn_prestamos.id, bn_bienes.id_bien,	bn_bienes.numero_bien,	bn_bienes.descripcion_bien,	asistencia_diaria.fecha,	asistencia_diaria.hora,	rac.cedula,	CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre,	asistencia_diaria.id as ida,	asistencia_diaria.cargo FROM 	asistencia_diaria,	bn_prestamos, bn_bienes, rac WHERE bn_prestamos.id_bien = bn_bienes.id_bien AND asistencia_diaria.id = bn_prestamos.id_asistencia AND asistencia_diaria.cedula = rac.cedula ORDER BY bn_prestamos.id DESC ;";//DESC AND asistencia_diaria.fecha = '".date('Y/m/d')."'
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div><?php if ($registro->observacion<>'') { ?><a data-toggle="tooltip" title="<?php echo ($registro->observacion); ?>"><div class="spinner-grow spinner-grow-sm" role="status"></div></a> <?php } ?></td>
<!--<td ><div align="left" ><?php //echo ($registro->cedula); ?></div></td>-->
<td ><strong><?php echo ($registro->nombre); ?></strong></td>
<td ><div align="left" ><?php echo ($registro->numero_bien); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion_bien); ?></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha). ' ' .hora_militar($registro->hora); ?></div></td>
	
<td align="center"><div><h5 ><?php if ($registro->tipo=='1') {echo 'ENTRADA';} else {echo 'SALIDA';} ?></h5>
</div></td>

<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger waves-effect" onclick="borrarb2('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($registro->id_bien); ?>','<?php echo ($registro->ida); ?>');" ><i class="fas fa-trash-alt prefix grey-text mr-1"></i></button></a></div></td>
	
	</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>