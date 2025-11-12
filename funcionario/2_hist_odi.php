<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

?>
<form id="form999" name="form999" method="post" >
    
<table id="tablan" class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Historial </td>
</tr>
<tr>
<!--
<td  bgcolor="#CCCCCC" align="center"><strong>Año:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Semestre:</strong></td>
-->
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Descripción:</strong></td>
<td colspan="3" bgcolor="#CCCCCC" align="center"><strong>Pdf:</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT evaluaciones.* FROM evaluaciones INNER JOIN eval_asignacion ON evaluaciones.id = eval_asignacion.id_evaluacion WHERE eval_asignacion.cedula = '".$_SESSION['CEDULA_USUARIO']."' GROUP BY id ORDER BY anno DESC, semestre DESC";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++; 
	?>
<tr id="fila<?php echo $registro->id; ?>" >
<!--<td><div align="center" ><?php //echo ($registro->anno); ?></div></td>-->
<!--<td ><div align="left" ><?php //echo ($registro->semestre); ?></div></td>-->
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td valign="middle" align="center"><div><a data-toggle="tooltip" title="Ver Evaluacion"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($_SESSION['CEDULA_USUARIO']); ?>','<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
	
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
});
//---------------------
function imprimir(ci, id)
	{	
	window.open("personal/reporte/4_evaluacion.php?p=1&id="+id+"&ci="+ci,"_blank");
	}
//----------------
function guardar_estatus()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/3f_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
				 	$('#modal_normal .close').click(); 
					buscar();
				}
			else
				{	
					Swal.fire({
				//		  title: 'Informacion!',
						  icon: 'info',				
						  text: data.msg,				
						  timer: 4500,				
						  timerProgressBar: true,				
						  showDenyButton: false,
						  showCancelButton: false
						})
					document.form999.txt_codigo.focus();
			}  
			}  
		});
 }
</script>