<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

//------- VALIDACION ACCESO USUARIO
//include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT id_contribuyente FROM ordenes_pago WHERE id= '".$_GET['id']."'"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();
$contribuyente = $registro_x->id_contribuyente;
//---------------
$consultx = "SELECT id_contribuyente, tipo_pago, banco, cuenta, banco2, cuenta2, chequera, num_pago, fecha_pago, id_chequera, id_cheque FROM ordenes_pago WHERE tipo_pago>0 AND id= '".$_GET['id']."'"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{	
	$registro_x = $tablx->fetch_object();
	//------
	$tipo = $registro_x->tipo_pago;
	$banco = $registro_x->banco;
	$cuenta = $registro_x->cuenta;
	$banco2 = $registro_x->banco2;
	$cuenta2 = $registro_x->cuenta2;
	$chequera = $registro_x->chequera;
	$num_pago = $registro_x->num_pago;
	$fecha_pago = voltea_fecha2($registro_x->fecha_pago);
	}
else
	{	
	$tipo = 2;
	$fecha_pago = date('d/m/Y');
	}
//---------
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Informaci&oacute;n del Pago 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-university mr-2"></i>Banco</div>
						<select class="custom-select" style="font-size: 14px" name="txt_banco" id="txt_banco" onchange="combo1();" >
						<option value="SELECCIONE"> -SELECCIONE- </option>
						<?php
						//--------------------
						$consultx = "SELECT * FROM a_cuentas ;"; 
						$tablx = $_SESSION['conexionsql']->query($consultx);
						while ($registro_x = $tablx->fetch_object())
						//-------------
						{
						echo '<option ';
						if ($registro_x->cuenta==$cuenta)	{echo ' selected="selected" ';}
						echo ' value="';
						echo $registro_x->id;
						echo '">';
						echo ($registro_x->banco).' '.($registro_x->cuenta).' '.($registro_x->descripcion);
						echo '</option>';
						}
						?>
						</select>
					</div>
				</div>

			</div>

<?php if ($contribuyente==1000 and 1==2) { ?>
		<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-university mr-2"></i>Banco</div>
						<select class="custom-select" style="font-size: 14px" name="txt_banco2" id="txt_banco2" onchange="combo1();" >
						<option value="SELECCIONE"> -SELECCIONE- </option>
						<?php
						//--------------------
						$consultx = "SELECT * FROM a_cuentas ;"; 
						$tablx = $_SESSION['conexionsql']->query($consultx);
						while ($registro_x = $tablx->fetch_object())
						//-------------
						{
						echo '<option ';
						if ($registro_x->cuenta==$cuenta2)	{echo ' selected="selected" ';}
						echo ' value="';
						echo $registro_x->id;
						echo '">';
						echo ($registro_x->banco).' '.($registro_x->cuenta).' '.($registro_x->descripcion);
						echo '</option>';
						}
						?>
						</select>
					</div>
				</div>

			</div>
<?php } ?>
			
		<div class="row">
			<div class="form-group col-sm-6">
				<div class="input-group">
				<input <?php if ($tipo==1) {echo 'checked="checked"';}?>  name="opcion" id="opcion" type="radio" value="1" class="form-control" onchange="tipo(this.value);"/> 
				<div class="input-group-text"><i class="fas fa-money-bill-alt mr-2"></i>Cheque</div>
				<input <?php if ($tipo==2) {echo 'checked="checked"';}?>  name="opcion" id="opcion" type="radio" value="2" class="form-control" onchange="tipo(this.value);"/>
				<div class="input-group-text"><i class="fas fa-money-bill-alt mr-2"></i>Transferencia</div>
				</div>	
			</div>
		</div>
			
			<div class="row" id="transferencia">
				<div class="form-group col-sm-12">
					<div class="input-group">
					<div class="input-group-text"><i class="fas fa-money-bill-alt mr-2"></i>Numero:</div>
					<input value="<?php echo $num_pago;	?>" id="txt_operacion" placeholder="Transferencia" name="txt_operacion" class="form-control" type="text" style="text-align:right" />
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Fecha:</div>
						<input value="<?php echo $fecha_pago;	?>" type="text" style="text-align:center" class="form-control " name="txt_fechat" id="txt_fechat" placeholder="Fecha de la Transferencia" minlength="1" maxlength="10" readonly="" required>
					</div>
				</div>	

			</div>
			
			<div class="row" id="cheque">
				<div class="form-group col-sm-12">
					<div class="input-group">
					<div class="input-group-text"><i class="fas fa-money-bill-alt mr-2"></i>Chequera:</div>
					<select class="custom-select" style="font-size: 14px" name="txt_chequera" id="txt_chequera" onchange="combo2();" >
						<option value="0"> Seleccione la Cuenta</option>
						</select>
					<div class="input-group-text"><i class="fas fa-money-bill-alt mr-2"></i>Cheque:</div>
					<select class="custom-select" style="font-size: 14px" name="txt_cheque" id="txt_cheque" >
						<option value="0"> Seleccione la Chequera</option>
						</select>
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Fecha:</div>
						<input type="text" style="text-align:center" class="form-control " name="txt_fechac" id="txt_fechac" placeholder="Fecha de la Transferencia" minlength="1" maxlength="10" value="<?php echo $fecha_pago;	?>" readonly="" required>
					</div>
				</div>	

		</div>
			<div class="row" >
				<div class="form-group col-sm-5">
					<div class="input-group">
					<div class="input-group-text"><i class="fas fa-money-bill-alt mr-2"></i>Monto:</div>
					<input value="0" id="txt_monto" placeholder="Monto Pagado" name="txt_monto" class="form-control" type="text" style="text-align:right" />
					</div>
				</div>	
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar('<?php echo $_GET['id']; ?>','<?php echo $_GET['tipo']; ?>')" ><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar Informaci&oacute;n</button>
</div>
<div id="div3"></div>	
</form>

<script language="JavaScript">
//--------------------------------
$("#txt_monto").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    }
});
//--------------------------------
combo1();
combo2();
//---------
<?php echo 'tipo('.$tipo.');';	?>
<?php echo 'listar_pagos('.$_GET['id'].');';	?>
$("#txt_fechat").datepicker();
$("#txt_fechac").datepicker();
//----------------
function eliminarp(id)
	{
	alertify.confirm("Estas seguro de eliminar el Pago Registrado?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "administracion/6h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			listar_pagos(<?php echo $_GET['id']; ?>);
			}
			});
		});
}
//-------------
function listar_pagos(id)
	{
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('administracion/6f_tabla.php?id='+id);
	}
//-------------
function combo1()
{
	$.ajax({
        type: "POST",
        url: 'administracion/6d_combo.php?banco='+document.form999.txt_banco.value+'&tipo=1&o1=<?php echo $chequera; ?>',
        success: function(resp){
            $('#txt_chequera').html(resp);
        }
    });
}
//-------------
function combo2()
{
	$.ajax({
		type: "POST",
		url: 'administracion/6d_combo.php?banco='+document.form999.txt_banco.value+'&chequera='+document.form999.txt_chequera.value+'&tipo=2&o1=<?php echo $chequera; ?>&o2=<?php echo $num_pago; ?>',
		success: function(resp){
			$('#txt_cheque').html(resp);
		}
	});
}
//--------------------------------
function tipo(id){
	$('#cheque').hide();
	$('#transferencia').hide();
	//-------
	if (id=='1')
		{ $('#cheque').show(); $('#transferencia').hide();	}	
	if (id=='2') 
		{ $('#cheque').hide(); $('#transferencia').show();	}	
}
//---------------------
</script>