<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=109;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="datatabla formateada" border="1" align="center" width="100%">
<thead>
<!--
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Bienes en Sistema</td>
</tr>
-->
<tr>
<th  bgcolor="#CCCCCC" align="center"><strong>N°:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Direccion:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Area:</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong>Opciones:</strong></th>
	</tr>
</thead>
<tbody>
<?php 	
$anno = $_GET['anno']; 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT a_direcciones.direccion, a_areas.area, a_areas.id FROM a_areas, a_direcciones WHERE a_areas.id_direccion = a_direcciones.id ORDER BY a_direcciones.direccion, a_areas.area";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->direccion); ?></div></td>
<td ><div align="left" ><?php echo ($registro->area); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Editar"><button data-toggle="modal" data-target="#modal_normal" data-keyboard="false" type="button" class="btn btn-outline-info btn-sm" onclick="cheques('<?php echo ($registro->id); ?>');">Jefe</button></a></div></td>
	</tr>
 <?php 
 }
 ?>
</tbody></table>
<script language="JavaScript" src="funciones/datatable.js"></script>