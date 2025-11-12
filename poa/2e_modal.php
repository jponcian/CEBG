<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=63;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$id_proyecto = decriptar($_GET['id']); 
$unidad = ($_GET['unidad']); 
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Registrar Meta 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
    <input type="hidden" id="oid" name="oid" value="<?php echo encriptar($id_proyecto); ?>"/>
    <input type="hidden" id="oidD" name="oidD" value="0"/>
<!-- Modal body -->
	
<br>
<div class="p-1">

<div id="basicos">
<div class="row">

<div class="form-group col-sm-12">
	<div class="input-group">
		<div class="input-group-text">Unidad Ejecutora:</div>
		<select class="custom-select" style="font-size: 14px" name="txt_unidad" id="txt_unidad" onchange="listar_metas('<?php echo encriptar($id_proyecto); ?>'); validar_campo_entero('txt_unidad');">
		<option value="0">--- Seleccione ---</option>
			<?php
			//--------------------
			$consultx = "SELECT poa_proyecto_responsable.id, poa_proyecto_responsable.id_direccion, bn_dependencias.division FROM	poa_proyecto_responsable, bn_dependencias WHERE poa_proyecto_responsable.id_direccion = bn_dependencias.id AND poa_proyecto_responsable.id_proyecto = '$id_proyecto' ORDER BY division;"; 
			$tablx = $_SESSION['conexionsql']->query($consultx);
			while ($registro_x = $tablx->fetch_object())
			//-------------
			{
			echo '<option ';
				if ($unidad == $registro_x->id_direccion) { echo 'selected';}
			echo ' value="';
			echo $registro_x->id.'/'.$registro_x->id_direccion;
			echo '">';
			echo ($registro_x->division);
			echo '</option>';
			}
			?>
		</select>
	</div>
</div>
	
<div class="form-group col-sm-5">
	<div class="input-group">
		<div class="input-group-text">Código de la Meta:</div>
		<input onkeyup="saltar(event,'txt_costo')" onChange="validar_campo('txt_codigo');" placeholder="Código de la Meta" id="txt_codigo" name="txt_codigo" class="form-control" type="text" style="text-align:center" />
	</div>
</div>
	
<div class="form-group col-sm-4">
	<div class="input-group">
		<div class="input-group-text">Costo:</div>
		<input onkeyup="saltar(event,'txt_meta')" onChange="validar_campo_entero('txt_costo');" placeholder="Costo" id="txt_costo" name="txt_costo" class="form-control" type="text" style="text-align:center" />
	</div>
</div>
	
<div class="form-group col-sm-3" id="div_modificacion">
	<div class="input-group">
		<div class="input-group-text">Modificación:</div>
		<input onkeyup="saltar(event,'txt_meta')" value="<?php echo date('d/m/Y'); ?>" placeholder="Fecha" id="ofecha" name="ofecha" class="form-control" type="text" style="text-align:center" />
	</div>
</div>
	
	<div class="form-group col-sm-12">
<textarea id="txt_meta" name="txt_meta" placeholder="META PROGRAMADA" onChange="validar_campo('txt_meta');" class="form-control" rows="4" ></textarea></div>
	<div class="form-group col-sm-6">
<textarea id="txt_actividad" name="txt_actividad" placeholder="ACTIVIDADES" onChange="validar_campo('txt_actividad');" class="form-control" rows="4" ></textarea></div>
	<div class="form-group col-sm-6">
<textarea id="txt_indicador" name="txt_indicador" placeholder="INDICADORES" onChange="validar_campo('txt_indicador');" class="form-control" rows="4" ></textarea></div>
</div>

	<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar2('<?php echo encriptar($id_proyecto); ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
	<button type="button" id="boton2" class="btn btn-outline-success waves-effect" onclick="guardar3('<?php echo encriptar($id_proyecto); ?>')" ><i class="fa-solid fa-arrow-up-right-dots prefix grey-text mr-1"></i> Generar Modificacion</button>
</div>
	
</div>
</div>
	

</div>
</div>
<div id="div3"></div>
</form>
<script language="JavaScript">
$("#ofecha").datepicker();
$('#boton2').hide();
$('#div_modificacion').hide();
//listar_metas('<?php //echo $_GET['id']; ?>','<?php //echo $_GET['anno']; ?>');
//----------------
<?php if ($unidad > 0 ) { ?> listar_metas('<?php echo encriptar($id_proyecto); ?>'); <?php } ?>
//----------------
function programacion(id, anno, unidad)
	{
	$('#modal_xl').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_xl').load('poa/2i_programacion.php?id='+id+'&anno='+anno+'&id_proyecto=<?php echo $_GET['id']; ?>'+'&unidad='+unidad);
	}
//----------------
function listar_metas(id)
	{
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('poa/2f_tabla.php?id='+id+'&unidad='+document.form999.txt_unidad.value);
	}
//----------------
function editar(id)
 {
	 Swal.fire({
		title: '¿Desea generar una Modificación?',
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, Generar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {	  	
		$('#div_modificacion').show();
		//-----------
	  	var parametros = "id=" + id + "&fecha="+result.value;
		$.ajax({
		url: "poa/2g_buscar.php",
		type: "POST",
		dataType:"json",
		data: parametros,
		success: function(data) {
		if (data.tipo=="info")
			{	
				$('#boton2').show('slow');
				document.form999.oidD.value=id;
				document.form999.txt_codigo.value=data.codigo;
				document.form999.txt_costo.value=data.costo;
				document.form999.txt_meta.value=data.meta;
				document.form999.txt_actividad.value=data.actividad;
				document.form999.txt_indicador.value=data.indicador;
				document.form999.ofecha.value=data.fecha;
				document.form999.txt_codigo.focus();
			}
		else
			{	alertify.alert(data.msg);	}
		}
		});
		//------------
  				}
			})
 }
//----------------
function validar()
 {
	//---------------- validacion
	if (parseInt(document.form999.txt_unidad.value) > 0 && document.form999.txt_codigo.value.length > 0 && parseInt(document.form999.txt_costo.value) > 0 && document.form999.txt_meta.value.length > 0 && document.form999.txt_actividad.value.length > 0 && document.form999.txt_indicador.value.length > 0 )
		{ error = 0; }
	else
		{ error = 1; validar_campo_entero('txt_unidad'); validar_campo('txt_codigo'); validar_campo_entero('txt_costo'); validar_campo('txt_meta'); validar_campo('txt_actividad'); validar_campo('txt_indicador'); }

	//----------------
	return error;	
 }
//----------------
function guardar3(id, anno)
 {
	 Swal.fire({
		title: 'Estas seguro de Generar la modificación?',
		text: "Esta acción no se puede revertir!",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, Generar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			if (validar()==0)
				{
				var parametros = $("#form999").serialize(); 
				$.ajax({  
					type : 'POST',
					url  : 'poa/2g_guardarb.php?id='+id,
					dataType:"json",
					data:  parametros, 
					success:function(data) {  	
						if (data.tipo=="info")
							{	alertify.success(data.msg);	
								$('#boton2').hide();
			//				 	$('#txt_proyecto').value='';
								document.form999.txt_codigo.value='';
								document.form999.txt_costo.value='0';
								document.form999.txt_meta.value='';
								document.form999.txt_actividad.value='';
								document.form999.txt_indicador.value='';
								document.form999.txt_codigo.focus();
								listar_metas(id);
							 	$('#div_modificacion').hide();
							}
						else
							{	
								Swal.fire({
							//		  title: 'Informacion!',
									  icon: 'info',				
									  text: data.msg,				
									  timer: 4500,				
									  timerProgressBar: true,				
									  showDenyButton: false,
									  showCancelButton: false
									})
			//					alertify.alert(data.msg);	
								document.form999.txt_codigo.focus();
								listar_metas(id);}
						}  
					});
				}
			//-----------------------
			}
		})
 }
//----------------
function guardar2(id, anno)
 {
if (validar()==0)
	{
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'poa/2g_guardar.php?id='+id,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
				 	$('#boton2').hide();
//				 	$('#txt_proyecto').value='';
				 	document.form999.txt_codigo.value='';
				 	document.form999.txt_costo.value='0';
				 	document.form999.txt_meta.value='';
				 	document.form999.txt_actividad.value='';
				 	document.form999.txt_indicador.value='';
					document.form999.txt_codigo.focus();
					listar_metas(id);
				}
			else
				{	
					Swal.fire({
				//		  title: 'Informacion!',
						  icon: 'info',				
						  text: data.msg,				
						  timer: 4500,				
						  timerProgressBar: true,				
						  showDenyButton: false,
						  showCancelButton: false
						})
//					alertify.alert(data.msg);	
					document.form999.txt_codigo.focus();
					listar_metas(id);}
			}  
		});
 	}
 }
//----------------
function eliminar2(id, id2)
	{
	Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id ;
			$.ajax({
			url: "poa/2h_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					listar_metas(id2);
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
			//-----------------------
			}
		})
}
//--------------------------------
$("#txt_costo").on({
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