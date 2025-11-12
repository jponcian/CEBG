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
$consultx = "SELECT * FROM usuarios WHERE id = ".$_GET['id'].";";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			

      <!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Datos del Usuario
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0<?php echo $_GET['id']; ?>"/>
</div>
	
<!--
        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
            </div>
            <input type="text" class="form-control " name="nombre" id="nombre" placeholder="Nombre del Usuario" minlength="4" maxlength="255" required mayusculastodo>
          </div>
        </div>
-->

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
            </div>
            <input type="text" value="<?php echo $registro->usuario; ?>" class="form-control " name="txt_cedula" id="txt_cedula" placeholder="Cedula del Usuario" minlength="5" maxlength="10" required entero>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
<!--
                  <strong class="text-danger stretched-link text-right" ng-show="usuario_ang.cedula.length > 5 && cedula_existe === true" role="alert">
                    Usuario ya registrado
                  </strong>
-->
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-user-check"></i></div>
            </div>
            <input value="<?php echo $registro->user; ?>" type="text" class="form-control " name="user" id="user"  placeholder="Username" minlength="4" maxlength="16" required>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
<!--
                  <strong class="text-danger stretched-link text-right" ng-show="usuario_ang.user.length > 3 && usuario_existe === true" role="alert">
                    Usuario ya registrado
                  </strong>
-->
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-key"></i></div>
            </div>
            <input value="<?php echo decriptar($registro->password); ?>" type="password" class="form-control " id="password" name="password" placeholder="Contraseña" minlength="4" maxlength="12" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-envelope-open"></i></div>
            </div>
            <input type="email" value="<?php echo $registro->email; ?>" class="form-control " placeholder="Correo Electrónico" name="correo" id="correo" placeholder="Correo electronico" required mayusculastodo>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-lock-open"></i></div>
            </div>
				<select class="select2" style="width: 420px" placeholder="Tipo de Acceso" name="tipo_acceso" id="tipo_acceso" >
					<?php
					$consultx = "SELECT * FROM tipo_acceso ORDER BY descripcion"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_array())
						{
						echo '<option value='.$registro_x['acceso'];
						if ($registro->acceso == $registro_x['acceso']) {echo ' selected="selected" ';}
						echo '>'.$registro_x['descripcion'].'</option>';
						}
					?></select>	          
			</div>
        </div>


      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar(0)" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>

      </div>

    </div>
  </div>
</div>
	
</form>
<script language="JavaScript">
$(document).ready(function() {
    $('.select2').select2();
	//----------------
});
//--------------------------------
function guardar()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'tecnologia/1c_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					$('#modal_normal .close').click(); 
					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//---------------------
function validar_cedula(cedula)
 	 {
//	 (e.keyCode)?k=e.keyCode:k=e.which;
//	// Si la tecla pulsada es enter (codigo ascii 13)
//	if(k==13)
//		{
		var parametros = "cedula" + cedula;
		$.ajax({  
			type : 'POST',
			url  : 'funciones/buscar_empleado.php?cedula='+cedula,
			data: parametros,
			dataType:"json",
			success:function(data) {  
				if (data.tipo=="alerta")
					{	
					alertify.alert(data.msg);
					document.form999.txt_cedula.value='';
					document.form999.txt_cedula.focus();
					}
				}  
			});
//		}
	}
//--------------------------------
setTimeout(function()	{
		$('#txt_cedula').focus();
		},500)	
//--------------------------------
</script>