<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=73;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Proyectos Establecidos con Responsables Asignados</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Denominacion</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Objetivo</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Supuesto</strong></td>-->
<td bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
$anno = $_GET['anno']; 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM poa_proyecto WHERE anno = '$anno' AND id IN ( SELECT id_proyecto FROM poa_proyecto_responsable WHERE id_direccion )ORDER BY numero";//$filtrar.$_GET['valor'].";"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//list($banco,$cuenta)=explode(' ', $registro->codigo);
	?>
<tr id="fila<?php echo $registro->id; ?>">
<!--<td><div align="center" ><?php echo ($i); ?></div></td>-->
<td><div align="center" ><?php echo ($registro->numero); ?></div></td>
<td ><div align="left" ><?php echo ($registro->tipo); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="left" ><?php echo ($registro->objetivo); ?></div></td>
<!--<td ><div align="left" ><?php //echo ($registro->supuestos); ?></div></td>-->

	<td ><div align="center" ><?php echo $_SESSION['estatus_poa'][($registro->estatus)]; ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Agregar o Eliminar"><button data-toggle="modal" data-target="#modal_extra" data-keyboard="false" type="button" class="btn btn-outline-info btn-sm" onclick="modal_meta('<?php echo encriptar($registro->id); ?>');">Metas</button></a></div></td>
</tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>