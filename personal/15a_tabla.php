<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=101;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table id="tablan" class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Empleados </td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Empleado:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Nomina:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Ubicacion:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cargo:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha Ingreso:</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Estatus:</strong></td>-->
<td colspan="3" bgcolor="#CCCCCC" align="center"><strong>Gestion:</strong></td>
</tr>
<?php 	
$direccion = $_GET['dir'];
$area = $_GET['area'];
$filtro = $_GET['tipo'];

if ($direccion>0)
	{	$filtrar1 = " rac.id_div = $direccion AND ";	} else {	$filtrar1 = "";	}
if ($area>0)
	{	$filtrar2 = " rac.id_area = $area AND ";	} else {	$filtrar2 = "";	}
//-----------------------------------
if ($_SESSION["direccion"]==10 or $_SESSION['ADMINISTRADOR']==1)
	{	}
else
	{
	$filtrar1 = " rac.id_div = ".$_SESSION["direccion"]." AND ";
	}

switch ($filtro) {
    case 0:
        $filtrar = " estatus = 5 AND ";
        break;		
    case 1:
        $filtrar = " estatus = 7 AND ";
        break;
}
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT rac.*, eval_asignacion.id_evaluacion, eval_asignacion.estatus FROM rac, eval_asignacion WHERE $filtrar $filtrar1 $filtrar2 rac.cedula = eval_asignacion.cedula AND evaluar_odis=1 AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO' GROUP BY id_evaluacion, rac.cedula, rac.rac ORDER BY (rac.cedula+0)";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->rac; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><strong><?php echo ($registro->cedula); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo $registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->ubicacion); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha_ingreso); ?></div></td>
<!--<td ><div align="center" ><?php //echo //($registro->odis); ?></div></td>-->
<td valign="middle" align="center"><div><?php if ($registro->estatus==5) { ?><a href="" class="btn btn-outline-danger btn-rounded btn-sm font-weight-bold" onclick="asignar_odis('<?php echo encriptar($registro->cedula); ?>');" data-toggle="modal" data-target="#modal_extra" data-keyboard="false">Evaluar</a><?php } else { ?><a data-toggle="tooltip" title="Eliminar Evaluacion"><button type="button" class="btn btn-outline-danger waves-effect" onclick="eliminar('<?php echo encriptar($registro->cedula); ?>','<?php echo encriptar($registro->id_evaluacion); ?>');" ><i class="fa-regular fa-trash-can prefix grey-text mr-1"></i></button></a><a data-toggle="tooltip" title="Ver Evaluacion"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->cedula); ?>','<?php echo encriptar($registro->id_evaluacion); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a><?php } ?></div></td></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>

<script language="JavaScript">
//----------------
function eliminar(cedula, id_evaluacion){
	Swal.fire({
		title: 'Estas seguro de eliminar la evaluación?',
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
			var parametros = "cedula=" + cedula + "&id_evaluacion=" + id_evaluacion;
				$.ajax({
				url: "personal/15h_eliminar.php",
				type: "POST",
				data: parametros,
				success: function(r) {
					//Swal.fire('Borrado!', 'El registro fue borrado.', 'success');
					alertify.success('La evaluación fue reversada con Exito!');
					busca_empleados();
					}
				});
			//-----------------------
			}
		})
	}
//----------------
function asignar_odis(cedula){
	$('#modal_xl').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_xl').load('personal/15b_modal.php?cedula='+cedula);
	}
</script>