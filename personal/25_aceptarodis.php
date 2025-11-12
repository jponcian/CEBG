<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }

//$acceso=98;
//------- VALIDACION ACCESO USUARIO
//include_once "../validacion_usuario.php";
//-----------------------------------
//----------- PARA VALIDAR SI ESTAN LAS EVALUACIONES ABIERTAS
$consulta_x = "SELECT estatus FROM evaluaciones WHERE estatus IN (4)";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows>0)
//-------------
	{
	}
else
	{
	//header ("Location: ../principal.php?opcion=no"); 
	?>
	<script language="JavaScript">
	Swal.fire({
//					  title: '',
			  icon: 'error',				
			  title: 'El Proceso para Aceptar los Odi no está abierto!',				
			  timer: 2000,				
			  timerProgressBar: true,				
			  showDenyButton: false,
			  showCancelButton: false
			})
	</script>
	<?php
	exit();
	}

$cedula = $_SESSION['CEDULA_USUARIO'];
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">VALIDAR ODI
	  </h4>
</div>
<!-- Modal body -->
<div class="p-1">
			
<!--<br>-->
<table class="formateada table" border="1" align="center" width="100%">
<thead>
	<tr>
		<th bgcolor="#CCCCCC" align="center"><strong>Item</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Descripción</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></th>
	</tr>
</thead>
<tbody><?php 
$consultx = "SELECT eval_odis.descripcion, eval_asignacion.id, eval_asignacion.estatus FROM eval_odis, eval_asignacion WHERE eval_odis.id = eval_asignacion.id_odi AND eval_asignacion.estatus=3 AND cedula='$cedula' ORDER BY descripcion";//$filtrar.$_GET['valor'].";"; 
$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
if ($tablx->num_rows>0)
	{		}
else
	{	?><tr><td colspan="4"><div align="center" ><h4>No Existen ODIS Asignados</h4></div></td></tr><?php }
//-------------
while ($registro = $tablx->fetch_object())
	{ 	
		$i++;
		?>
	<tr>
		<td style="vertical-align: middle"><strong><?php echo $i; ?></strong></td>
		<td style="vertical-align: middle"><strong><?php echo $registro->descripcion; ?></strong></td>
		<td valign="middle" >
			<input  placeholder="OBSERVACION" id="txt_observacion<?php echo ($registro->id); ?>" onchange="validar_campo('txt_observacion<?php echo ($registro->id); ?>');"  name="txt_observacion<?php echo ($registro->id); ?>" class="form-control" type="text" onFocus="this.select()" value="<?php echo ('ACEPTADO'); ?>" />
			</td> 
<?php 
	 }
 ?>
 </tbody>  
</table>

</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
<?php 
if ($tablx->num_rows>0)
	{	?><button id="botona" type="button" class="btn btn-outline-primary waves-effect" onClick="aceptar()"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Aceptar</button>	<?php } ?>
</div>
</form>
<script language="JavaScript">
//--------------------------------
function aceptar()
 {
Swal.fire({
	title: 'Desea Guardar la Información?',
	text: "",
	icon: 'question',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	confirmButtonText: 'Si, Guardar!',
	cancelButtonText: 'Cancelar'
	}).then((result) => {
  if (result.isConfirmed) { 
   		$('#botona').hide();
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'personal/25a_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	
					Swal.fire('Guardado con Exito!', data.msg, 'success');
					bandeja();
					}
				else
					{	alertify.alert(data.msg);	}
				}  
			});
  				}
			else if (result.isDenied) {
//					tabla_bienes(id); 
//					$('#modal_largo').modal('show');
  					}	
			})
 }
//----------------
</script>