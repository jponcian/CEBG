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
	<td class="TituloTablaP" height="41" colspan="10" align="center">Gestion Registrada</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N°</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Codigo Meta</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Mes Meta - (Mes Gestión)</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Meta</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Indicador</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Gestion</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Programado</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Ejecutado</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Cumplimiento</strong></td>
</tr>
<?php 	
$anno = $_GET['anno']; 
$responsable = $_GET['rep']; 
$desde = voltea_fecha($_GET['s1']); 
$hasta = voltea_fecha($_GET['s2']); 
//$id_meta = decriptar($_GET['id']); 
//------ MONTAJE DE LOS DATOS
$consultxg = "SELECT poa_metas_gestion.*, poa_metas.codigo, poa_metas.indicador, poa_metas_frecuencia.detalle as meta, poa_metas_frecuencia.cantidad as metac FROM poa_proyecto_responsable, poa_metas, poa_metas_frecuencia, poa_metas_gestion, poa_proyecto WHERE poa_metas_gestion.fecha_gestion>='$desde' AND poa_metas_gestion.fecha_gestion<='$hasta' AND poa_proyecto_responsable.anno='$anno' AND poa_proyecto_responsable.id_direccion=$responsable AND (poa_proyecto.id= poa_proyecto_responsable.id_proyecto AND poa_proyecto_responsable.id_proyecto = poa_metas.id_proyecto AND poa_metas.id = poa_metas_frecuencia.id_meta AND poa_metas_frecuencia.id_meta = poa_metas_gestion.id_meta AND poa_proyecto_responsable.id = poa_metas.id_responsable ) GROUP BY poa_metas_gestion.id ORDER BY poa_metas.id_proyecto, poa_metas.codigo";//$filtrar.$_GET['valor'].";"; 
//echo $consultxg;
$tablxg = $_SESSION['conexionsql']->query($consultxg);
if ($tablxg->num_rows>0)
		{	$resp = 1;	}
	else
		{	?>
	<tr >
<td colspan="9" ><div align="center" class="alert alert-warning" role="alert"><strong>NO EXISTE INFORMACION REGISTRADA!</strong></div></td>
</tr>
	<?php
}
	
while ($registrog = $tablxg->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registrog->id; ?>">
<td ><div align="left" ><strong><?php echo ($i); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Agregar o Eliminar"><button data-toggle="modal" data-target="#modal_extra" data-keyboard="false" type="button" class="btn btn-outline-danger btn-sm" onClick="ver_meta('<?php echo encriptar($registrog->id_meta); ?>')"><?php echo ($registrog->codigo); ?></button></a></div></td>
<td ><div align="left" ><strong><?php echo ($registrog->mes_meta) . ' -(' .($registrog->mes_gestion) .')'; ?></strong></div></td>
<td ><div align="left" ><?php echo ($registrog->meta); ?></div></td>
<td ><div align="left" ><?php echo ($registrog->indicador); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registrog->detalle); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo ($registrog->metac); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo ($registrog->cantidad); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo formato_moneda(($registrog->cantidad*100)/$registrog->metac); ?> %</strong></div></td>
</tr>
	<?php 
	}
	?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>