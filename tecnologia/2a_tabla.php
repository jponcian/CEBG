<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=93;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<thead><tr>
<th  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong>Empleado:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Cargo:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Direccion:</strong></th>
<th colspan="2" bgcolor="#CCCCCC" align="center"><strong>Opciones:</strong></th>
</tr></thead>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT a_direcciones.id, a_direcciones.direccion, rac.cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre, a_direcciones.cargo FROM a_direcciones, rac WHERE a_direcciones.cedula = rac.cedula;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->rac; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cedula); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->nombre); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
<td ><div align="left" ><?php echo ($registro->direccion); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Cambiar Coordinador"><button type="button" class="btn btn-outline-primary light-3 btn-sm" data-toggle="modal" data-target="#modal_normal" onclick="cambiarc(<?php echo ($registro->id); ?>);" data-keyboard="false"><i class="fas fa-user-edit"></i></button></a>
<td ><div align="center" ><a data-toggle="tooltip" title="Cambiar Director"><button type="button" class="btn btn-outline-success light-3 btn-sm" data-toggle="modal" data-target="#modal_normal" onclick="cambiar(<?php echo ($registro->id); ?>);" data-keyboard="false"><i class="fas fa-user-edit"></i></button></a>
</div></td>
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
<script language="JavaScript" src="funciones/datatable.js"></script>