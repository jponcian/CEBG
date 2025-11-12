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
//----------- PARA VALIDAR SI ESTAN LAS EVALUACIONES ABIERTAS
$consulta_x = "SELECT estatus FROM evaluaciones WHERE estatus IN (8)";
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
			  title: 'El Proceso de Evaluación no está abierto!',				
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
<div class="container">
	<br>
<?php
$consultx = "SELECT cedula FROM rac WHERE odis=8 AND cedula='$cedula'";//$filtrar.$_GET['valor'].";"; 
$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
if ($tablx->num_rows>0)
	{	?>	

<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="TituloTablaP btn-block text-center" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          SECCIÓN “B” EVALUACIÓN DE LOS OBJETIVOS DE DESEMPEÑO
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        <table class="formateada table" border="1" align="center" width="100%">
<thead>
	<tr>
		<th bgcolor="#CCCCCC" align="center"><strong>Item</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Descripción</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Peso</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Puntaje</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Total</strong></th>
	</tr>
</thead>
<tbody><?php 
$consultx = "SELECT eval_odis.descripcion, eval_asignacion.* FROM eval_odis, eval_asignacion WHERE eval_odis.id = eval_asignacion.id_odi AND (eval_asignacion.estatus=7 or eval_asignacion.estatus=8) AND cedula='$cedula' ORDER BY descripcion";//$filtrar.$_GET['valor'].";"; 
$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
if ($tablx->num_rows>0)
	{		}
else
	{	?><tr><td colspan="4"><div align="center" ><h4>No Existen ODIS Evaluados</h4></div></td></tr><?php }
//-------------
while ($registro = $tablx->fetch_object())
	{ 	
		$id_evaluacion = $registro->id_evaluacion;
		$i++;
		$totalB += $registro->total;
		?>
	<tr>
		<td style="vertical-align: middle"><strong><?php echo $i; ?></strong></td>
		<td style="vertical-align: middle"><strong><?php echo $registro->descripcion; ?></strong></td>
		<td align="center" style="vertical-align: middle"><strong><?php echo $registro->peso; ?></strong></td>
		<td align="center" style="vertical-align: middle"><strong><?php echo $registro->puntaje; ?></strong></td>
		<td align="center" style="vertical-align: middle"><strong><h4><?php echo $registro->total; ?></h4></strong></td>
<?php 
	 }
 ?>
	<tr>
		<th colspan="4" bgcolor="#CCCCCC" align="right"><strong>Sub-Total</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong><h3><?php echo $totalB; ?></h3></strong></th>
	</tr>
 </tbody>  
</table>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="TituloTablaP btn-block text-center" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          SECCIÓN “C” EVALUACIÓN DE LAS COMPETENCIAS
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
       <table class="formateada table" border="1" align="center" width="100%">
<thead>
	<tr>
		<th bgcolor="#CCCCCC" align="center"><strong>Item</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Descripción</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Peso</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Puntaje</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Total</strong></th>
	</tr>
</thead>
<tbody><?php 
$consultx = "SELECT eval_asignacion_comp.id, eval_asignacion_comp.id_evaluacion,	eval_asignacion_comp.id_comp,	eval_asignacion_comp.fecha_evaluados,	eval_asignacion_comp.peso, eval_asignacion_comp.puntaje, eval_asignacion_comp.total, eval_competencias.descripcion FROM eval_asignacion_comp, eval_competencias WHERE eval_asignacion_comp.cedula='$cedula' AND eval_asignacion_comp.id_comp = eval_competencias.id AND eval_asignacion_comp.id_evaluacion = 0$id_evaluacion AND (eval_asignacion_comp.estatus = 7 or eval_asignacion_comp.estatus = 8);";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
if ($tablx->num_rows>0)
	{		}
else
	{	?><tr><td colspan="4"><div align="center" ><h4>No Existen Competencias Evaluadas</h4></div></td></tr><?php }
//-------------
while ($registro = $tablx->fetch_object())
	{ 	
		$j++;
		$totalC += $registro->total;
		?>
	<tr>
		<td style="vertical-align: middle"><strong><?php echo $j; ?></strong></td>
		<td style="vertical-align: middle"><strong><?php echo $registro->descripcion; ?></strong></td>
		<td align="center" style="vertical-align: middle"><strong><?php echo $registro->peso; ?></strong></td>
		<td align="center" style="vertical-align: middle"><strong><?php echo $registro->puntaje; ?></strong></td>
		<td align="center" style="vertical-align: middle"><strong><h4><?php echo $registro->total; ?></h4></strong></td>
<?php 
	 }
 ?>
	<tr>
		<th colspan="4" bgcolor="#CCCCCC" align="right"><strong>Sub-Total</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong><h3><?php echo $totalC; ?></h3></strong></th>
	</tr>
 </tbody>  
</table>
</table>
      </div>
    </div>
  </div>
  
</div>
	
<div class="row">
	<div class="col-lg-6">
		<div class="card">
		  <div class="card-header btn-primary" style="background-color:mediumslateblue">
			 <h5 class="m-0">SECCIÓN “B”</h5>
		  </div>
		  <div class="card-body">
			<h4 class="card-title"><strong>Calificación Obtenida</strong></h4>

			<p class="card-text"></p>

				<div align="center" class="m-0 alert alert-primary" role="alert">
				  <h2><?php echo $totalB; ?></h2>
				</div>
			  </div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="card">
		  <div class="card-header btn-primary" style="background-color: steelblue">
			 <h5 class="m-0">SECCIÓN “C”</h5>
		  </div>
		  <div class="card-body">
			<h4 class="card-title"><strong>Calificación Obtenida</strong></h4>

			<p class="card-text"></p>

				<div align="center" class="m-0 alert alert-primary" role="alert">
				  <h2><?php echo $totalC; ?></h2>
				</div>
			  </div>
		</div>
	</div>
</div>
	
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		  <div class="card-header btn-primary" style="background-color: limegreen">
			 <h5 class="m-0">Calificación Total</h5>
		  </div>
		  <div class="card-body">
				<div align="center" class="m-0 alert alert-success" role="alert">
				  <strong>Calificación Total</strong> <h1><?php echo $totalB+$totalC; ?></h1>
				</div>
				<div align="center" class="m-0 alert alert-success" role="alert">
				  <strong>Rango de Actuación</strong> <h2><?php echo evaluacion($totalB + $totalC); ?></h2>
				</div>
			  </div>
		</div>
	</div>
</div>

<?php 
	}
else
	{	?>
	<div align="center" class="m-0 alert alert-success" role="alert">
				  <h2>Ya fue Aceptada la Evaluación</h2>
				</div>
	<?php } ?>
	
</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
<?php 
if ($tablx->num_rows>0)
	{	?><button id="botona" type="button" class="btn btn-outline-success waves-effect btn-lg" onClick="aceptar()"><i class="fa-regular fa-thumbs-up fa-lg mr-1"></i>Aceptar</button>	<?php } ?>
</div>
</form>
<script language="JavaScript">
//--------------------------------
function aceptar()
 {
Swal.fire({
	title: 'Acepta la Evaluación?',
	text: "",
	icon: 'question',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	confirmButtonText: 'Si, Acepto!',
	cancelButtonText: 'No'
	}).then((result) => {
  if (result.isConfirmed) { 
   		$('#botona').hide();
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'personal/26b_guardar.php',
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
  					}	
			})
 }
//----------------
</script>