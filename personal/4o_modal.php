<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=15;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$_SESSION['id'] = decriptar($_GET['id']);
$consultx = "SELECT * FROM rac WHERE cedula = 0".decriptar($_GET['id']).";";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2"><?php echo $registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?>
    <button type="button" class="close" data-dismiss="modal" >&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">

		<div align="center" >
			<img src="personal/funcionarios/<?php echo $_SESSION['id']; ?>_0.jpg" class="rounded-circle img-fluid" alt="No ha Cargado la Foto..." height="250" width="250"> 
		</div>

	<div class="container">
      <div class="row">
	  </div>
        </div>
			</br>
        <div class="row">
          <div class="col-12">
                <div class="form-group">
                    <?php if (decriptar($_GET['id'])<>'0') { ?>
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
    //-------------------
	respuestaRaw = await fetch("./personal/4f_guardar.php", {
        method: "POST",
        body: formData,
    });
    respuesta = await respuestaRaw.json();
    // Puedes manejar la respuesta como tú quieras
		alertify.success("Archivo Cargado");
		$('#modal_normal .close').click();
    // Finalmente limpiamos el campo
    $inputArchivos.value = null;
    $estado.textContent = "Archivo Cargado";
});
</script>