<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso='111';
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$cedula = decriptar($_GET['id']);
?>
<button type="button" class="close" data-dismiss="modal">&times;</button>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Comisiones Registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Tipo:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Desde:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Hasta:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Incorporación:</strong></td>
<td colspan="3" bgcolor="#CCCCCC" align="center"><strong>Opciones:</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM rrhh_permisos WHERE cedula = $cedula AND tipo='COMISION' ORDER BY fecha;";//.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	if ($registro->nomina<>'005 JUBILADOS' and $registro->nomina<>'006 PENSIONADO' and $registro->nomina<>'0700 CUERPO DE BOMBEROS' and $registro->nomina<>'0800 EGRESADOS') { $constancia='si';} else { $constancia='no';}
	if ($registro->nomina<>'0700 CUERPO DE BOMBEROS' and $registro->nomina<>'0800 EGRESADOS') { $recibo='si'; $arc='si';} else { $recibo='no'; $arc='no';}
	?>
<tr id="fila<?php echo $registro->rac; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><strong><?php echo ($registro->tipo); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo ($registro->numero); ?></strong></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->desde); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->hasta); ?></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->incorporacion); ?></strong></div></td>
<!--<td valign="middle" align="center"><div><a href="" class="btn btn-outline-success btn-rounded btn-sm font-weight-bold" onclick="vacaciones('<?php //echo encriptar($registro->id); ?>','<?php //echo ($registro->tipo); ?>');" data-toggle="modal" data-target="#modal_largo" ><i class="fa-regular fa-file-pdf fa-2x"></i></a></div></td>-->
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->id); ?>', '<?php echo $id; ?>');"><i class="fas fa-trash-alt"></i></button></a></div></tr>
</tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
<script language="JavaScript">
//----------------
function eliminar(id, id2)
	{
	Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id + "&id2=" + id2;
			$.ajax({
			url: "personal/9h_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
				 	$('#modal_largo .close').click();
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
			//-----------------------
			}
		})
}
//----------------
</script>