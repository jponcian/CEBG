<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//-----------
$id = ($_GET['id']);
?>
<table class="table table-striped table-hover" bgcolor="#FFFFFF" width="100%" border="0" align="center">
<!--
<tr>
<td class="TituloTablaP" colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr>
--><thead><tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">BIENES NACIONALES</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="left"><strong>N:</strong></td>
<!--<td  bgcolor="#CCCCCC" align="left"><strong>Cedula</strong></td>-->
<td  bgcolor="#CCCCCC" align="left"><strong>Funcionario</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>N° Bien</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripcion</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Hora</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong></strong></td>
</tr></thead>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT	bn_prestamos.id, bn_bienes.id_bien,	bn_bienes.numero_bien,	bn_bienes.descripcion_bien,	asistencia_diaria.fecha,	asistencia_diaria.hora,	rac.cedula,	CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre,	asistencia_diaria.id as ida,	asistencia_diaria.cargo, a_direcciones.direccion FROM 	asistencia_diaria,	bn_prestamos, a_direcciones, bn_bienes, rac WHERE bn_prestamos.id_bien = bn_bienes.id_bien AND asistencia_diaria.id = bn_prestamos.id_asistencia AND asistencia_diaria.cedula = rac.cedula AND asistencia_diaria.id_direccion = a_direcciones.id AND asistencia_diaria.id = $id ORDER BY asistencia_diaria.hora ;";//DESC
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<!--<td ><div align="left" ><?php //echo ($registro->cedula); ?></div></td>-->
<td ><div align="left" ><strong><?php echo ($registro->nombre); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->numero_bien); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion_bien); ?></div></td>
<td ><div align="left" ><?php echo hora_militar($registro->hora); ?></div></td>

<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger waves-effect" onclick="borrarb('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($registro->id_bien); ?>','<?php echo ($registro->ida); ?>');" ><i class="fas fa-trash-alt prefix grey-text mr-1"></i></button></a></div></td>
	
	</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>