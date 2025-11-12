<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=8;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Verificar Ingreso</div>
		<br >
	
	<diw class="row ml-3">
        <table><tr>
			<td><div class="form-check ml-12">
                <input placeholder="Escanear CARNET..." name="ocedula" id="ocedula" type="text" size="15" class="form-control" onkeyup="verificar(event,this.value)" onFocus="this.select()" style="text-decoration-color: aqua" />
            </div></td>
		</tr></table>    
        
        <table><tr>
			<td><div class="form-check ml-12">
                <input placeholder="Consultar aqui..." name="obuscar" id="obuscar" type="text" size="40" class="form-control" onkeyup="buscar(event)" onFocus="this.select()" />
            </div></td>
			<td><div class="form-check ml-4">
                <strong><div id="personas"></div></strong>
            </div></td>
		</tr></table>    
        </diw>
<br>
 <div id="div1"></div>
</form>
<script language="JavaScript">
setTimeout(function()	{
		$('#ocedula').focus(); listar_tabla();personas();
		},500);	//document.form1.ocedula.focus;
//--------------------------------------------
function agregar(cedula)
	{
	if (validar_detalle()=='0')
		{
		var parametros = $("#form999").serialize();
		$.ajax({
		url: "seguridad/6b_revisar.php?tabla="+cedula,
		dataType:"json",
		type: "POST",
		data: parametros,
		success: function(data) {
		//Swal.fire(data.msg, '', data.tipo)
			Swal.fire({
			  title: data.msg,
			  icon: data.tipo,				
			  text: '',				
			  timer: 1000,				
			  timerProgressBar: true,				
			  showDenyButton: false,
			  showCancelButton: false
			})
		//	alertify.success(data.msg);
		$('#modal_largo .close').click(); 
		//--------------
		document.form1.ocedula.value='';
		document.form1.ocedula.focus;
		listar_tabla(); personas();
		}
		});
		}
	}
//-----------------------
function personas()
	{
	$('#personas').load('seguridad/6d_personas.php');
	//$('#modal_n').load('seguridad/6c_modal.php?id='+id);
	}
//-----------------------
function motivo(id)
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('seguridad/6c_modal.php?id='+id);
	}
//---------------------
function borrar(id)
	{
		Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id;
					$.ajax({
					url: "seguridad/6g_eliminar.php",
					type: "POST",
					data: parametros,
					success: function(r) {
						alertify.success("El registro fue borrado...");
						listar_tabla(); personas();
						}
					});
			//-----------------------
			}
		})
	}
//---------------------
function buscar(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{listar_tabla();}
	}
//--------------------- PARA BUSCAR
function listar_tabla(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('seguridad/6a_tabla.php?buscar='+cambia(document.form1.obuscar.value));
}
//----------------------------
function salida(id)
	{
	Swal.fire({
	  title: '¿Registrar la Salida?',
	  icon: 'question',				
	  //text: "¿Registrar la Salida?",				
	  showDenyButton: true,
	  showCancelButton: false,
	  confirmButtonText: 'SALIDA',
	  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id;
				$.ajax({
				url: "seguridad/6c_salida.php",
				type: "POST",
				data: parametros,
				success: function(r) {
					//Swal.fire('Borrado!', 'El registro fue borrado.', 'success');
					Swal.fire({
							  title: 'SALIDA!',
							  icon: 'success',				
							  text: 'El registro fue actualizado.',				
							  timer: 1500,				
							  timerProgressBar: true,				
							  showDenyButton: false,
							  showCancelButton: false
							})
					document.form1.ocedula.value=''; 
					$('#ocedula').focus(); 
					listar_tabla();
					personas();
					}
				});
				//-----------------------
		  } else if (result.isDenied) {
			 procesar=2; //Swal.fire('Changes are not saved', '', 'info')
		  }
			})
	}
//----------------------------
function verificar(e,id)
	{
	//var procesar=0;
	(e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13 && id!='')
		{
		var parametros = "id=" + id;
		$.ajax({
		dataType:"json",
		url: "seguridad/6e_chequeo.php?id="+ id,
		type: "POST",
		data: parametros,
		success: function(r) { Swal.fire(r.tipo, '', 'info');
			if (r.tipo=="ENTRADA")
				{	
				Swal.fire({
					  title: 'Ingrese el N° de Cédula del Visitante',
					  input: 'text',
						icon: 'info',
						inputPlaceholder: 'Ingrese el número de cédula',
					  inputAttributes: {
						maxlength: 8,
						autocapitalize: 'off'
					  },
					  showCancelButton: false,
					  confirmButtonText: 'CONTINUAR'
					}).then((result) => {
					  if (result.isConfirmed && result.value.trim()!='') {
							$('#modal_lg').load('seguridad/6b_modal.php?id='+result.value+'&carnet='+id);
							$('#modal_largo').modal('show'); 
						}
					});
				}	
			if (r.tipo=="SALIDA")
				{	salida(id);	 }	
				}
			});		
		}
	}
</script>