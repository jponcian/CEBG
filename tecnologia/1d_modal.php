<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=93;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//$consultx = "SELECT * FROM usuarios WHERE id = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
$cedula = ($_GET['id']);
?>
<form id="form999" name="form999" method="post" >
			

      <!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Permisos Individuales
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $cedula; ?>"/>
</div>
	
        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fa-solid fa-building-lock"></i></div>
            </div>
				<select class="select2" style="width: 420px" onChange="listar(this.value)" placeholder="Tipo de Acceso" name="tipo_acceso" id="tipo_acceso" >
					<option value='0'>Seleccione el Módulo</option>
					<?php
					$consultx = "SELECT modulo FROM accesos_individual GROUP BY modulo ORDER BY modulo"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_array())
						{
						echo '<option value='.encriptar($registro_x['modulo']);
//						if ($registro->acceso == $registro_x['acceso']) {echo ' selected="selected" ';}
						echo '>'.$registro_x['modulo'].'</option>';
						}
					?></select>	          
			</div>
        </div>

 <br>
<div id="div3"></div>
 <br>

</form>
<script language="JavaScript">
$(document).ready(function() {
    $('.select2').select2();
	listar();
});
//---------------------
function listar(modulo)
 	 {
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('tecnologia/1e_tabla.php?modulo='+modulo+'&cedula='+document.form999.oid.value);
	}
//--------------------------------
function asignar(id, cedula, tipo)
 {
	var parametros = "id="+id+"&cedula="+cedula+"&tipo="+tipo;
	$.ajax({  
		type : 'POST',
		url  : 'tecnologia/1f_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
//					$('#modal_normal .close').click(); 
//					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
</script>