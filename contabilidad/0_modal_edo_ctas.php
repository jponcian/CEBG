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
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2"><?php if ($_GET[tipo]==1) {echo 'Buscar el monto '. formato_moneda($_GET['monto']); } else {echo 'Buscar la referencia '. ($_GET['ref']); }?> en el Estado de Cuenta 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
</div>
<!-- Modal body -->
<!--		<div class="p-1">
			
<div class="row">
	<div class="form-group col-sm-3">
		<input id="txt_original" placeholder="Monto" onkeyup="listar_monto2(event,this.value)" value="<?php //echo $_GET['monto']; ?>" maxlength="30" name="txt_original" class="form-control" type="text" style="text-align:right"/>
	</div>
</div>
			
		</div>-->
<!-- Modal footer -->
<div id="div3"></div>
</form>
<script language="JavaScript">
//----------------
function marcar_conciliacion(id, valor)
	{
	var parametros = "id=" + id + "&valor=" + valor;
	$.ajax({  
	type : 'POST',
	url  : 'tesoreria/0b_actualizar.php',
	dataType:"json",
	data:  parametros, 
	success:function(data) {  	
		if (data.tipo=="info")
			{	alertify.success(data.msg);	$('#modal_lg .close').click();	}
		else
			{	alertify.alert(data.msg);	}
		//--------------
		
		}  
	});	
	}
//----------------
<?php if ($_GET[tipo]==1) 
	{echo "listar_monto(". $_GET['monto'].');'; } 
else {echo "listar_ref(". $_GET['monto'].');'; } ?>
//----------------
function listar_ref(id)
	{
	$('#div3').html('<div align="center"><img src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#div3').load('tesoreria/0_tabla_monto.php?id='+id+'&tipo='+<?php echo $_GET[tipo]; ?>);
	}
//----------------
function listar_monto(id)
	{
	$('#div3').html('<div align="center"><img src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#div3').load('tesoreria/0_tabla_monto.php?id='+id+'&tipo='+<?php echo $_GET[tipo]; ?>);
	}
//--------------------------------
//$("#txt_original").on({
//    "focus": function (event) {
//        $(event.target).select();
//    },
//    "keyup": function (event) {
//        $(event.target).val(function (index, value ) {
//            return value.replace(/\D/g, "")
//                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
//                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
//        });
//    }
//});
</script>