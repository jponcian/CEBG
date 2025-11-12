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
//$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Agregar Destinatarios 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">
				
				<div class="row">
				
				<div class="form-group col-sm-9 ml-5">
					<select class="custom-select" style="font-size: 14px" name="txt_destino" id="txt_destino" onchange="">
					<option value="0">Seleccione la Direccion Destino</option>
	<?php
//--------------------
$consult = "SELECT * FROM a_direcciones WHERE id<50 and id not in (SELECT direccion_destino FROM cr_memos_div_destino WHERE id_correspondencia = '".decriptar($_GET['id'])."') ORDER BY direccion;"; // WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	//if ($partida==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->direccion;
	echo '</option>';
	}
?>
				</select>
					
				</div>			
		</div>
				
				<div class="form-group col-sm-3">
					<button id="boton" type="button" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar_hijo('<?php echo $_GET['id']; ?>');"><i class="fas fa-plus prefix grey-text mr-1"></i> Agregar</button>
				</div>
				
			</div>
			
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
			
			<div id="div2">
			<?php //include_once "38b_tabla.php"; ?> 
			</div>
</div>

</form>
<script language="JavaScript">
tabla1('<?php echo $_GET['id']; ?>');
//------------------------
function tabla1(id)
{ $('#div2').load('correspondencia/3i_tabla.php?id='+id); }
//------------------------------ PARA ELIMINAR
function eliminar_hijo(id,reg)
	{
	alertify.confirm("Estas seguro de eliminar el Registro?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "correspondencia/3j_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			tabla1(reg);
			}
			});
		});
}
//------------------
function agregar_hijo(id_rep)
{
//	if (validar()==0)
//		{
		$('#boton').hide();
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'correspondencia/3h_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	tabla1(id_rep); $('#boton').show();}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
			});
//		}
}
//------------------
</script>