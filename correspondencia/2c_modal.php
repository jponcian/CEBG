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
?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<form id="form888" name="form888" method="post" onSubmit="return evitar();" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Instrucciones
<button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button></h4>
</div>
<!-- Modal body -->
	<?php
//--------------------
$consult = "SELECT * FROM a_instrucciones ORDER BY id;"; // WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{ ?>
	<div class="input-group mb-1">
  <div class="input-group-prepend">
    <div class="input-group-text">
      <input type="checkbox" value="1" name="chk_<?php echo $registro_x->id; ?>" id="chk_<?php echo $registro_x->id; ?>" onClick="saltar2('txt_<?php echo $registro_x->id; ?>')">
    </div>
  </div>
  <input type="text" name="txt_<?php echo $registro_x->id; ?>" id="txt_<?php echo $registro_x->id; ?>" class="form-control" placeholder="<?php echo $registro_x->descripcion; ?>">
</div>
	<?php } ?>
		
</br>	
<div class="row">
	<div class="form-group col-sm-12">
<textarea id="txt_concepto" name="txt_concepto" placeholder="Observaciones" class="form-control" rows="2" ></textarea></div>
	</div>
</div>

<div align="center">			
<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar2('<?php echo $_GET['id'] ?>', '<?php echo $_GET['destino'] ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button></div>

</form>

<script language="JavaScript">
//------------------------------ PARA ELIMINAR
function guardar2(id, destino)
	{
	alertify.confirm("Estas seguro de Aprobar y Enviar la Correspondencia?",  
	function()
			{ 
			$('#boton').hide();
			var parametros = $("#form888").serialize();
			$.ajax({
			url: "correspondencia/2k_guardar.php?id="+id+'&destino='+destino,
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {  	
			if (data.tipo=="info")
				{	$('#modal_largo .close').click();	alertify.success(data.msg);	buscar(); 
					//window.open("correspondencia/formatos/memo_dir.php?p=1&origen="+data.origen+"&destino="+data.destino+"&estatus=0&id="+id,"_blank");
				}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			} 
			});
		});
	}
//--------------------------------
setTimeout(function()	{
	$('#txt_concepto').focus();
	},1000)	
//--------------------------------
</script>