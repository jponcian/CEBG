<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=96;
//----VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$id_proyecto = decriptar($_GET['id']); 
$estatus = ($_GET['estatus']); 
//-----------------------------------
$consultx = "SELECT cedula FROM rac WHERE evaluar_odis=1 AND odis>$estatus AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO';";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$proceso = $tablx->num_rows;
//-----------------------------------
$consultx = "SELECT cedula FROM rac WHERE evaluar_odis=1 AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO';";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$total = $tablx->num_rows;
//------------
if ($estatus==10) {$proceso = $total;}
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Estatus de las Evaluaciones 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
    <input type="hidden" id="oid" name="oid" value="<?php echo encriptar($id_proyecto); ?>"/>
<!-- Modal body -->
	
<br><?php //echo $estatus; ?>
<div class="container-fluid">
	
<div class="row">
	<div class="col-lg-5 ml-2">
		<div class="card">
		  <div class="card-header btn-primary" style="background-color: limegreen">
			 <h5 class="m-0">Estatus: <?php echo $_SESSION['estatus_odi'][abs($estatus)+1]; ?></h5>
		  </div>
		  <div class="card-body">
				<div align="center" class="m-0 alert alert-success" role="alert">
				  <strong>Funcionarios Listos</strong> <h1><?php echo $proceso; ?></h1>
				</div>
				<div align="center" class="m-0 alert alert-success" role="alert">
				  <strong>Funcionarios Faltantes</strong> <h2><?php echo $total-$proceso; ?></h2>
				</div>
			  </div>
		</div>
	</div>
	<div class="col-lg-6">
		  <div id="piechart"></div>
	</div>
</div>
	
</div>
    
<table id="tablan" class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Empleados </td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Empleado:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Ubicacion:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong></strong></td>
<td colspan="2" bgcolor="#CCCCCC" align="center"><strong>Pdf:</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT rac.* FROM rac WHERE evaluar_odis=1 AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO' ORDER BY odis, (rac.cedula+0);";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++; 
	?>
<tr id="fila<?php echo $registro->rac; ?>" <?php if ($registro->odis==abs($estatus)+1) {?>style="background-color:#88B98C"<?php }?>>
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><strong><?php echo ($registro->cedula); ?></strong></div></td>
<td ><div align="left" ><strong><?php echo $registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->ubicacion); ?></div></td>
<td ><div align="center" ><strong><?php echo $_SESSION['estatus_odi'][($registro->odis)]; ?></strong></div></td>
<td valign="middle" align="center"><div><a data-toggle="tooltip" title="Excluir"><button type="button" class="btn btn-outline-danger btn-sm" onclick="sacar('<?php echo encriptar($registro->cedula); ?>');" ><i class="fa-solid fa-triangle-exclamation"></i></button></a></div></td>
<td valign="middle" align="center"><div><a data-toggle="tooltip" title="Ver Evaluacion"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->cedula); ?>','<?php echo encriptar($id_proyecto); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
	
</form>
<script language="JavaScript">
//----------------
function sacar(id)
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
			var parametros = "id=" + id;
			$.ajax({
			url: "personal/3h_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					//buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
			//-----------------------
			}
		})
}
//-----------------------
google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['En Proceso', <?php echo $proceso; ?>],
          ['Faltante',    <?php echo $total-$proceso; ?>]
        ]);

        var options = {
          title: '<?php echo $_SESSION['estatus_odi'][abs($estatus)+1]; ?>',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
//---------------------------
</script>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
});
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