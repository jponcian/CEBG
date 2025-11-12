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
if ($_SESSION["direccion"]==10 or $_SESSION['ADMINISTRADOR']==1)
	{
	$id = ' ';
	}
else
	{
	$id = ' AND id = '.$_SESSION["direccion"];
	}
//----------- PARA VALIDAR SI ESTAN LAS EVALUACIONES ABIERTAS
$consulta_x = "SELECT estatus FROM evaluaciones WHERE estatus IN (6)";
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
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<div align="center" class="TituloP">EVALUACION</div><br>
<!--	<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> GENERAR</a></div>-->

	<diw class="row ml-3">
            <strong>Opciones de Filtrado:</strong>
<br><br>

            <div class="form-group col-sm-12">
				<div class="input-group">
					<div class="input-group-text">Dirección:</div>
					<select class="select2" style="width: 600px" style="font-size: 14px" name="txt_direccion" id="txt_direccion" onchange="listar_areas(this.value); validar_campo('txt_direccion'); ">
					<option value="0">--- Todas ---</option>
						<?php
						//--------------------
						$consultx = "SELECT id, direccion FROM	a_direcciones WHERE id<50 $id ORDER BY direccion;"; 
						$tablx = $_SESSION['conexionsql']->query($consultx);
						while ($registro_x = $tablx->fetch_object())
						//-------------
						{
						echo '<option ';
			//				if ($unidad == $registro_x->id_direccion) { echo 'selected';}
						echo ' value="';
						echo $registro_x->id;
						echo '">';
						echo ($registro_x->direccion);
						echo '</option>';
						}
						?>
					</select>
				</div>
			</div>
	
<div class="form-group col-sm-12">
	<div class="input-group">
		<div class="input-group-text">Area:</div>
		<select class="select2" style="width: 635px" style="font-size: 14px" name="txt_area" id="txt_area" onchange="busca_empleados(); validar_campo('txt_area');">
		<option value="0">--- Todas ---</option>
		</select>
	</div>
</div>
			
        </diw>
	
        <diw class="row ml-3">
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input checked type="radio" class="form-check-input" name="optradio" value="0" onclick="busca_empleados()" >
                    <strong>Personal por Evaluar</strong></label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" onclick="busca_empleados()" >
                   <strong>Personal Evaluado</strong>
                </label>
            </div>			
        </diw>

 <br>
<div id="div2"></div>
</form>
<script language="JavaScript">
$(document).ready(function(){  
//		busca_empleados();	
		$('table#tablan').styleTable({  
			th_bgcolor: '#3E83C9',  
			th_color: '#ffffff',  
			th_border_color: '#333333',  
			tr_odd_bgcolor: '#ECF6FC',  
			tr_even_bgcolor: '#ffffff',  
			tr_border_color: '#95BCE2',  
			tr_hover_bgcolor: '#BCD4EC'  
		});  
    }); 	
//----------------
function listar_areas(id) {
    $.ajax({
        type: "POST",
        url: 'personal/12b_combo.php?id=' + id,
        success: function(resp) {
            $('#txt_area').html(resp);
			busca_empleados();
        }
    });
}
//---------------------
function imprimir(ci, id)
	{	
	window.open("personal/reporte/4_evaluacion.php?p=1&id="+id+"&ci="+ci,"_blank");
	}
//---------------------------
function busca_empleados()
	{
	if(document.form1.optradio.value==0 || document.form1.optradio.value==1){
		$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div2').load('personal/15a_tabla.php?tipo='+document.form1.optradio.value+'&dir='+document.form1.txt_direccion.value+'&area='+document.form1.txt_area.value);
			}
	}
</script>