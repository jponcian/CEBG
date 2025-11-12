<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=50;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br><div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Bien</a></div>

 <br>  
	<diw class="row ml-3">
            <strong>Dependencia:</strong>
			<div class="form-check ml-3">
                <div class="form-group col-sm-8">
			<select style="width: 700px" class="select2" style="font-size: 14px" name="txt_dependencia" id="txt_dependencia" onchange="busca_empleados();">
			<option value="0">Todas</option>
<?php
//--------------------
$consult = "SELECT bn_dependencias.* FROM bn_dependencias, bn_bienes WHERE bn_bienes.id_dependencia=bn_dependencias.id GROUP BY bn_dependencias.id ORDER BY division;";
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	if ($partida==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->division;
	echo '</option>';
	}
?>
					</select>
				</div>	
            </div>			
        </diw>
        <diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="busca_empleados()" >
                   Ver Todos
                </label>
            </div>			
        </diw>
  <br>          		

 <input placeholder="Escriba aqui la informacion a buscar..." name="obuscar" id="obuscar" type="text" size="100" class="form-control" />

<div id="div2"></div><br>
</form>
<script language="JavaScript">
$(document).ready(function() {
    $('.select2').select2();
});
//---------------------------
function ficha(id)
 	{
		window.open("bienes/formatos/ficha.php?id="+id,"_blank");
	}
//---------------------------
function rep()
 	{
	if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value!=4 && document.form1.optradio.value!=7){}
	else	
		{
		window.open("bienes/reporte/1_bienes.php","_blank");
		}
	}
//----------------
function eliminar(id)
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
			url: "bienes/4c_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					busca_empleados();
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
			//-----------------------
			}
		})
}
//--------------------------------
function guardar()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'bienes/4e_guardar.php?id='+ document.form999.oid.value,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					$('#modal_largo .close').click(); 
					busca_empleados();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//--------------------------------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('bienes/4b_modal.php');
	}
//----------------
function basicos(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('bienes/4b_modal.php?id='+id);
	}
//----------------
function busca_empleados()
	{
	if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value!=4 && document.form1.optradio.value!=7){}
	else	{
		$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div2').load('bienes/4f_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&dep='+document.form1.txt_dependencia.value+'&tipo='+document.form1.optradio.value);
			}
	}
</script>