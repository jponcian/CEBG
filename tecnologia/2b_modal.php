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
$consultx = "SELECT * FROM a_direcciones WHERE id = ".$_GET['id'].";";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			

      <!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Datos del Director
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0<?php echo $_GET['id']; ?>"/>
</div>
	
		<div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-user-check"></i></div>
            </div>
				<select class="select2" style="width: 420px" name="txt_empleado" id="txt_empleado" >
					<?php
					//--------------------
					$consult = "SELECT cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre FROM rac WHERE nomina <> 'EGRESADOS' ORDER BY (cedula+1);";// WHERE id_direccion='$desde' id_div=".$_GET['id']." AND 
					$tablx = $_SESSION['conexionsql']->query($consult);
					while ($registro_x = $tablx->fetch_object())
					//-------------
						{
						echo '<option value="';
						echo $registro_x->cedula;
						echo '" ';
						if ($registro_x->cedula==$registro->cedula) {echo 'selected="selected"';}
						echo ' >';
						echo ($registro_x->cedula).' - '.$registro_x->nombre;
						echo '</option>';
						}
					?></select>	          
			</div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
            </div>
            <input type="text" value="<?php echo $registro->cargo; ?>" class="form-control " name="txt_cargo" id="txt_cargo" placeholder="Cargo" minlength="5" maxlength="50" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fa-regular fa-newspaper"></i></div>
            </div>
            <input type="text" value="<?php echo $registro->gaceta; ?>" class="form-control " name="txt_gaceta" id="txt_gaceta" placeholder="Gaceta Oficial" minlength="5" maxlength="150" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
            </div>
            <input type="text" value="<?php echo voltea_fecha($registro->fecha_gaceta); ?>" class="form-control " name="txt_fecha_gaceta" id="txt_fecha_gaceta" placeholder="Fecha Gaceta Oficial" minlength="5" maxlength="10" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fa-regular fa-newspaper"></i></div>
            </div>
            <input type="text" value="<?php echo $registro->providencia; ?>" class="form-control " name="txt_prov" id="txt_prov" placeholder="Providencia" minlength="5" maxlength="150" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
            </div>
            <input type="text" value="<?php echo voltea_fecha($registro->fecha_prov); ?>" class="form-control " name="txt_fecha_prov" id="txt_fecha_prov" placeholder="Fecha Providencia" minlength="5" maxlength="10" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
            </div>
            <input type="text" value="<?php echo voltea_fecha($registro->fecha_not); ?>" class="form-control " name="txt_fecha_not" id="txt_fecha_not" placeholder="Fecha Notificación" minlength="5" maxlength="10" required>
          </div>
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
	$("#txt_fecha_gaceta").datepicker();
	$("#txt_fecha_prov").datepicker();
	$("#txt_fecha_not").datepicker();
	//----------------
});
//--------------------------------
function guardar()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'tecnologia/2c_guardar.php',
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
//--------------------------------
</script>