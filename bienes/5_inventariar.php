<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=51;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Verificar Inventario</div>
		<br >
<div  class="text-right mb-3"><a class="btn btn-outline-danger btn-rounded btn-sm font-weight-bold" onclick="limpiar();" ><i class="fas fa-history" ></i> Reiniciar Listado</a></div>
	
	<div class="row ml-3">
				
		<div class="form-group col-sm-8">
			<div class="input-group-text"><strong>Dependencia =></strong> <select style="width: 700px" class="select2" style="font-size: 14px" name="txt_dependencia" id="txt_dependencia" onchange="listar_bienes();">
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
		</div>
	
	<diw class="row ml-3">
            <strong>Opciones de Filtrado:</strong>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" onclick="listar_bienes();" checked >
                   Pendiente
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" onclick="listar_bienes();" >
                   Revisados
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="listar_bienes();" >
                   Ver Todos
                </label>
            </div>
        </diw>
 <br>

	<diw class="row ml-3">
        <table><tr>
			<td><div class="form-check ml-4">
                <strong>Verificar Bien Nacional => </strong>
            </div></td>
			<td><div class="form-check ml-8">
                <input name="obien" id="obien" type="text" size="10" class="form-control" onkeyup="verificar(event,this.value)" onFocus="this.select()" />
            </div></td>
		</tr></table>    
        </diw>
<br>
 <div id="div1"></div>
</form>
<script language="JavaScript">
$(document).ready(function() {
    $('.select2').select2();
});
function rep()
 	{
	if (document.form1.optradio.value>0)
		{
			//{
			window.open("bienes/reporte/2_verificados.php","_blank");
			//}
		}
	}
//--------------------- PARA BUSCAR
function listar_bienes(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('bienes/5a_tabla.php?id=' + document.form1.txt_dependencia.value + '&tipo=' + document.form1.optradio.value);
	document.form1.obien.value='';
	$('#obien').focus();
}
//----------------------------
function cambiar(id, revisado)
	{
	alertify.confirm("Estas seguro de Cambiar el Estatus?",  
	function()
			{ 
			var parametros = "id=" + id + "&revisado=" +revisado;
			$.ajax({
			url: "bienes/5c_reiniciar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Bien Nacional procesado correctamente');
			//--------------
			listar_bienes();
			//document.form1.obien.value='';
			//$('#obien').focus();
			}
			});
		});
	}
//----------------------------
function limpiar()
	{
	alertify.confirm("Estas seguro de Reiniciar la Dependencia?",  
	function()
			{ 
			var parametros = "id=" + document.form1.txt_dependencia.value ;
			$.ajax({
			url: "bienes/5b_reiniciar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Listado Reiniciado Correctamente');
			//--------------
			listar_bienes();
			document.form1.obien.value='';
			$('#obien').focus();
			}
			});
		});
	}
//----------------------------
function verificar(e,id)
	{
	(e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		alertify.notify('Procesando...');
		//----------
		var parametros = "id=" + id ;
			$.ajax({
			url: "bienes/5f_reasignar.php?dir="+document.form1.txt_dependencia.value,
			dataType:"json",
			type: "POST",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	}
			else
				{	alertify.alert(data.msg);	}
			//	alertify.success(data.msg);
			//--------------
			listar_bienes();
			document.form1.obien.value='';
			document.form1.obien.focus;
			}
			});
		}
					
	}
</script>