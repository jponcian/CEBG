<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=1;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT * FROM cr_memos_ext WHERE id = 0".decriptar($_GET['id']).";";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Recepción de Correspondencia 
	  <button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
<div class="row">
			<div class="form-group col-sm-6">
				<input onkeyup="saltar(event,'txt_fecha')" type="text" id="txt_numero" name="txt_numero" placeholder="Numero del Oficio" class="form-control" value="<?php if (decriptar($_GET['id'])<>'0') { echo ($registro->numero); } ?>">
			</div>
			<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
				<input onkeyup="saltar(event,'txt_origen')" type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha"  minlength="1" maxlength="10" value="<?php if (decriptar($_GET['id'])<>'0') { echo voltea_fecha($registro->fecha); } else { echo date('d/m/Y'); } ?>" required></div>
		</div>	
			</div>

			<div class="row">
	<div class="form-group col-sm-8">
			<input type="text" id="txt_origen" name="txt_origen" placeholder="Remitente" class="form-control" value="<?php if (decriptar($_GET['id'])<>'0') { echo ($registro->origen); } ?>">
	</div>
</div>
<div class="row">
	<div class="form-group col-sm-10">
			<input type="text" id="txt_instituto" name="txt_instituto" placeholder="Organismo" value="<?php if (decriptar($_GET['id'])<>'0') { echo ($registro->instituto); } ?>" class="form-control">
	</div>
</div>

<div class="row">

	<div class="form-group col-sm-11">
		<select class="custom-select" style="font-size: 14px" name="txt_destino" id="txt_destino" onchange="">
<!--					<option value="0">Seleccione la Direccion Destino</option>-->
<?php
//--------------------
$consult = "SELECT * FROM a_direcciones where (id<50 or id>=90) ORDER BY direccion;"; // WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
{
echo '<option value="';
echo $registro_x->id;
echo '" ';
if ($registro->direccion_destino==$registro_x->id) {echo 'selected="selected"';}
echo ' >';
echo $registro_x->direccion;
echo '</option>';
}
?>
	</select>

	</div>			
</div>

<div class="row">
	<div class="form-group col-sm-12">
		<input type="text" id="txt_asunto" value="<?php if (decriptar($_GET['id'])<>'0') { echo ($registro->asunto); } ?>" name="txt_asunto" placeholder="Asunto" class="form-control">
	</div>
</div>

	<div class="container">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <?php if (decriptar($_GET['id'])<>'0') { ?>
	<h5>Subir Archivo de nuevo? <input type="checkbox" id="ch_estado" name="ch_estado" value="1" checked></h5>
	<?php } ?><input multiple type="file" class="form-control" id="inputArchivos">
                </div>
                <div class="alert alert-info" id="estado"></div>
            </div>
        </div>
    </div>
			
		</div>

	</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="btnEnviar" class="btn btn-outline-success waves-effect" onclick="" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
</div>
</div>
</div>

</form>
<script language="JavaScript">
//----------------
$inputArchivos = document.querySelector("#inputArchivos"),
    $btnEnviar = document.querySelector("#btnEnviar"),
    $estado = document.querySelector("#estado");
$btnEnviar.addEventListener("click", async () => {
    archivosParaSubir = $inputArchivos.files;
    if (archivosParaSubir.length <= 0) {
        // Si no hay archivos, no continuamos
        Swal.fire('No ha seleccionado el archivo adjunto!', '', 'error')
		return;
    }
    // Preparamos el formdata
    formData = new FormData();
    // Agregamos cada archivo a "archivos[]". Los corchetes son importantes
    for (archivo of archivosParaSubir) {
        formData.append("archivos[]", archivo);
    }
    // Los enviamos
    $estado.textContent = "Enviando archivos..."; 
	//------- PARA GUARDAR LA INFORMACION
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'correspondencia/2f_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	tipo=data.tipo;
				 	mensaje=data.msg;	
				 	$('#btnEnviar').hide();
				}
			else
				{	alertify.alert(data.msg);	
					document.form999.txt_numero.focus();
				}
		 	}
		});
    //-------------------
	respuestaRaw = await fetch("./correspondencia/2f_guardar2.php", {
        method: "POST",
        body: formData,
    });
    respuesta = await respuestaRaw.json();
    // Puedes manejar la respuesta como tú quieras
	if (tipo=="info")
		{	alertify.success(mensaje);
		 	$('#modal_largo .close').click();
		}
//    console.log({ respuesta });
    // Finalmente limpiamos el campo
    $inputArchivos.value = null;
    $estado.textContent = "Archivos enviados";
});
//----------------
function guardar2()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'correspondencia/2f_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
//				 	$('#txt_proyecto').value='';
				 	document.form999.txt_proyecto.value='';
				 	document.form999.txt_objetivo.value='';
				 	document.form999.txt_supuesto.value='';
					document.form999.txt_proyecto.focus();
				}
			else
				{	alertify.alert(data.msg);	
					document.form999.txt_proyecto.focus();
				}
		 	}
		});
 }
</script>