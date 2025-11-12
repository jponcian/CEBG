<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

?>
<form id="form999" name="form999" method="post" onsubmit="return evitar();" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Modificación
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
	<div class="p-1">
		<div class="row">
			<div class="form-group col-sm-6 mt-2">
				<div class="input-group ml-4">
				<h6>Sueldo Mensual =></h6>
				</div>
			</div>
			<div class="form-group col-sm-5">
				<div class="input-group">
					<input <?php if ($_GET['tipo']=='001' or $_GET['tipo']=='002') { echo 'readonly' ;} ?>  onkeyup="saltar(event,'txt_antiguedad')" style="text-align: right" value="<?php echo $_GET['sueldo']; ?>" id="txt_sueldo" placeholder="Monto Bs" name="txt_sueldo" class="form-control moneda" type="text" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6 mt-2">
				<div class="input-group ml-4">
				<h6>Prima Antiguedad =></h6>
				</div>
			</div>
			<div class="form-group col-sm-5">
				<div class="input-group">
					<input <?php if ($_GET['tipo']=='001' or $_GET['tipo']=='002') { echo 'readonly' ;} ?> onkeyup="saltar(event,'txt_hijos')" style="text-align: right" value="<?php echo $_GET['antiguedad']; ?>" id="txt_antiguedad" placeholder="Monto Bs" name="txt_antiguedad" class="form-control moneda" type="text" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6 mt-2">
				<div class="input-group ml-4">
				<h6>Prima Hijos =></h6>
				</div>
			</div>
			<div class="form-group col-sm-5">
				<div class="input-group">
					<input <?php if ($_GET['tipo']=='001' or $_GET['tipo']=='002') { echo 'readonly' ;} ?> onkeyup="saltar(event,'txt_prof')" style="text-align: right" value="<?php echo $_GET['hijos']; ?>" id="txt_hijos" placeholder="Monto Bs" name="txt_hijos" class="form-control moneda" type="text" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6 mt-2">
				<div class="input-group ml-4">
				<h6>Prima Profesionalizacion =></h6>
				</div>
			</div>
			<div class="form-group col-sm-5">
				<div class="input-group">
					<input <?php if ($_GET['tipo']=='001' or $_GET['tipo']=='002') { echo 'readonly' ;} ?> onkeyup="saltar(event,'txt_bono')" style="text-align: right" value="<?php echo $_GET['prof']; ?>" id="txt_prof" placeholder="Monto Bs" name="txt_prof" class="form-control moneda" type="text" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6 mt-2">
				<div class="input-group ml-4">
				<h6>Bono =></h6>
				</div>
			</div>
			<div class="form-group col-sm-5">
				<div class="input-group">
					<input <?php if ($_GET['bono']>0) {} else {echo "readonly";} ?> onkeyup="saltar(event,'txt_dias')" style="text-align: right" value="<?php echo $_GET['bono']; ?>" id="txt_bono" placeholder="Monto Bs" name="txt_bono" class="form-control moneda" type="text" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6 mt-2">
				<div class="input-group ml-4">
				<h6>Dias Adicionales =></h6>
				</div>
			</div>
			<div class="form-group col-sm-5">
				<div class="input-group">
					<input <?php if ($_GET['tipo']=='002') { echo 'readonly' ;} ?> style="text-align: right" value="<?php echo $_GET['dias']; ?>" id="txt_dias" placeholder="Monto Bs" name="txt_dias" class="form-control moneda" type="text" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6 mt-2">
				<div class="input-group ml-4">
				<h6>Diferencia =></h6>
				</div>
			</div>
			<div class="form-group col-sm-5">
				<div class="input-group">
					<input <?php if ($_GET['tipo']=='002') { echo 'readonly' ;} ?> style="text-align: right" value="<?php echo $_GET['diferencia']; ?>" id="txt_dif" placeholder="Monto Bs" name="txt_dif" class="form-control moneda" type="text" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6 mt-2">
				<div class="input-group ml-4">
				<h6><?php if ($_GET['tipo']=='001') { echo 'Quincena =>' ;} elseif ($_GET['tipo']=='002') { echo 'CestaTickets =>' ;} elseif ($_GET['tipo']=='003') { echo 'Total Mensual (30 dias) =>' ;} ?></h6>
				</div>
			</div>
			<div class="form-group col-sm-5">
				<div class="input-group">
					<input <?php if ($_GET['tipo']<>'002') { echo 'readonly' ;} ?> style="text-align: right" value="<?php echo $_GET['tickets']; ?>" id="txt_tickets" placeholder="Monto Bs" name="txt_tickets" class="form-control moneda" type="text" />
				</div>
			</div>
		</div>


	</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar('<?php echo $_GET['id']; ?>','<?php echo $_GET['tipo']; ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
</div>
</form>
<script language="JavaScript">
//--------------------------------
setTimeout(function()	{
		$('#txt_sueldo').focus();
		},500)	
//--------------------------------
$(".moneda").on({
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
</script>