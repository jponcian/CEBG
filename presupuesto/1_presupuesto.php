<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=66;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" >
<div align="center" class="TituloP">Presupuesto Anual</div>
<br >
<table width="350">
<tr>
<td>
<div class="input-group-prepend">
<span class="input-group-text"><i class="far fa-calendar-alt mr-2"></i> A&ntilde;o:</span>
<select class="form-control" name="oanno" id="oanno" onchange="comboC(this.value);">
  <!--<option value="0" > Seleccione </option>-->
<?php
//--------------------
$anno = date('Y');
while ($anno >= 2022)
//-------------
	{
	echo '<option value="';
	echo $anno;
	echo '" ';
	if ($_GET['anno']==$anno) {echo 'selected="selected"';}
	echo ' >';
	echo $anno;
	echo '</option>';
	$anno--;
	}
?> 
</select>
</div>
</td><td align="left" valign="top">
<button type="button" id="boton" class="btn btn-success btn-sm" onClick="actualizar(1);">Recargar Partidas</button>
</td>
</tr>
</table>
<div id="espera" class="btn btn-outline-info waves-effect" onclick="" >Espere unos minutos para recalcular de nuevo las partidas...</div>
<table width="400">
<tr><td>
<div class="input-group-prepend">
<span class="input-group-text"><i class="fas fa-users mr-2"></i> Actividad:</span>
<!--<input name="ocategoria" id="ocategoria" type="text" size="40" maxlength="10" class="form-control" onkeyup="buscar(event)" />-->
	<select class="select2" name="ocategoria" id="ocategoria" style="width: 600px" onChange="buscar2();">
		<option value="">Todas las Actividades</option>	
			<?php
			//--------------------
			$consultx = "SELECT * FROM a_presupuesto_2025 WHERE categoria is null and descripcion<>'PATRIA' ORDER BY codigo;"; 
			$tablx = $_SESSION['conexionsql']->query($consultx);
			while ($registro_x = $tablx->fetch_object())
			//-------------
			{
			echo '<option ';
			echo ' value="';
			echo $registro_x->codigo;
			echo '">';
			echo ($registro_x->codigo).' '.$registro_x->descripcion;
			echo '</option>';
			}
			?>
	</select>
</div>
</td></tr>
</table>
<table width="500">
<tr>
<td>
<div class="input-group-prepend">
<span class="input-group-text"><i class="fas fa-users mr-2"></i> Partida:</span>
<input name="opartida" id="opartida" type="text" size="40" maxlength="12" class="form-control" onkeyup="buscar(event)" /></div>
</td><td><div class="input-group-prepend">
<span class="input-group-text"><strong>Resumen-></strong></span><input id="oresumen" name="oresumen" type="checkbox" class="switch_new" value="1" /><label for="oresumen" class="lbl_switch"></label></div></td>
</tr>
</table>
<table width="450"><tr>
<td align="left" valign="top"><div class="input-group-prepend">
<input class="form-control" type="text" name="OFECHA1" id="OFECHA1" size="15" placeholder="Desde" value="<?php //echo '01'.date('/m/Y'); ?>" style="text-align:center" /> 
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php //echo date('d/m/Y'); ?>" style="text-align:center" />
</div></td>
<td width="200" align="left" valign="top">
<button type="button" id="boton2" class="btn btn-warning btn-sm" onClick="actualizar(2);">Calcular segun Fechas</button>
</td>
</tr></table>
 <div id="div1"></div>
</form>
<br>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2({});
});
//---------------------
$('#OFECHA1').datepicker();
$('#OFECHA2').datepicker();
//-------------
function comboC(anno)
{
	$.ajax({
        type: "POST",
        url: 'presupuesto/1d_combo.php?anno='+anno,
        success: function(resp){
            $('#ocategoria').html(resp);
        }
    });
}
//---------------------
function imprimir()
	{	
	window.open("presupuesto/formatos/4_pdf.php","_blank");
	}
//----------------
function generar_excel()
	{
	window.open('presupuesto/1a_generar.php', '_blank');
	}
<?php
//-------------	
$consultax = "SELECT * FROM a_actualizacion LIMIT 1;";
$tablax = $_SESSION['conexionsql']->query($consultax);
if ($tablax->num_rows>0)	
	{
	$registro = $tablax->fetch_object();
	$fechayhora_presupuesto = strtotime(date('Y-m-d H:i:s',strtotime($registro->presupuesto)));
	$actual = strtotime(date('Y-m-d H:i:00'));
	$minutos = (($actual-$fechayhora_presupuesto)/60); 
	if (abs($minutos)>4)	
		{	
		?>
		$('#boton').show("slow"); $('#espera').hide("slow");
		<?php	
		}
	else
		{	
		?>
		setTimeout(function()	{
		$('#boton').show("slow"); $('#espera').hide("slow");
		},<?php echo ($minutos*60); ?>000);
		$('#boton').hide("slow"); $('#espera').show();
		<?php	
		}
	}
?>
//-----------
function buscar2()
 	 {
	$('#boton').hide(); $('#boton2').hide();
	//Obtenemos datos formulario.
	var parametros = $("#form1").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'funciones/session.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {}
		});
	tabla(); $('#boton2').show();//$('#boton').show();   
	}
//-----------
function buscar(e)
 	 {
	$('#boton').hide(); $('#boton2').hide();
	(e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		//Obtenemos datos formulario.
		var parametros = $("#form1").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'funciones/session.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {}
			});
		tabla(); $('#boton2').show();//$('#boton').show();   
		}
	}
//--------------------
function actualizar(tipo)
{
	$('#boton').hide(); $('#boton2').hide();
	alertify.alert('Espere mientras se recalculan las partidas...');
	var parametros = $("#form1").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'presupuesto/1c_guardar.php?tipo='+tipo,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);		tabla();	
				setTimeout(function()	{$('#boton').show("slow"); $('#espera').hide("slow");},240000);
				$('#espera').show("slow"); $('#boton2').show();	}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			} 
		 
		});
}
//--------------------
function tabla(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('presupuesto/1b_tabla.php');
}
//-------------
</script>