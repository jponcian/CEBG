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
<table class="formateada datatabla" border="1" align="center" width="100%">
<!--
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Usuarios en Sistema</td>
</tr>
-->
<!--
<tr>
<td colspan="5" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
<td colspan="4" align="center"><button type="button" id="botonb2" class="btn btn-lg btn-block btn-info" onClick="reph();"><i class="fas fa-search mr-2"></i>Ver Pdf Hijos</button></td>
</tr>
-->
<thead><tr>
<th  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Empleado:</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong>Usuario:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Correo:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Tipo Acceso:</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong>Opciones:</strong></th>
</tr></thead>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT usuarios.id, usuarios.id_contribuyente, usuarios.nombre_usuario, usuarios.user, usuarios.password, usuarios.email, tipo_acceso.descripcion, usuarios.acceso, usuarios.usuario FROM usuarios INNER JOIN tipo_acceso ON tipo_acceso.acceso = usuarios.acceso WHERE tipo_acceso.acceso <> 99 AND usuarios.acceso >= 0 and usuarios.acceso < 200;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->rac; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre_usuario); ?></div></td>
<td ><div align="left" ><?php echo ($registro->user); ?></div></td>
<td ><div align="left" ><?php echo ($registro->email); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Modificar"><button type="button" class="btn btn-outline-success light-3 btn-sm" data-toggle="modal" data-target="#modal_normal" onclick="agregar(<?php echo ($registro->id); ?>);" data-keyboard="false"><i class="fas fa-user-edit"></i></button></a>
	<a data-toggle="tooltip" title="Accesos Individuales"><button type="button" class="btn btn-outline-danger light-3 btn-sm" data-toggle="modal" data-target="#modal_normal" onclick="acceso('<?php echo encriptar($registro->usuario); ?>');" data-keyboard="false"><i class="fa-solid fa-user-lock"></i></button></a>
<!--<a data-toggle="tooltip" title="Ficha"><button type="button" class="btn btn-outline-danger light-3 btn-sm" onclick="ficha('<?php //echo encriptar($registro->rac); ?>');"><i class="fa-regular fa-file-pdf fa-2xl"></i></button></a>-->
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