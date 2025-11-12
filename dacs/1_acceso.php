       <?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=28;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Verificar Ingreso</div>
		<br >
	
	<diw class="row ml-3">
        
        <table border="1"><tr>
<!--
			<td><div class="form-check ml-4">
                <strong>Capturar => </strong>
            </div></td>
-->
			<td><div class="form-check ml-12">
                <input placeholder="Consultar aqui..." name="obuscar" id="obuscar" type="text" size="40" class="form-control" onkeyup="buscar(event)" onFocus="this.select()" />
            </div></td>
			<td><div class="form-check ml-4">
                <strong><div id="personas"></div></strong>
            </div></td>
			<td align="right"><div class="form-check ml-4"><a class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="telf(0);" ><i class="fas fa-plus-circle"></i> Atención Telefonica</a></div></td>
		</tr></table>   
        </diw>
<br>
	
	<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="TituloTablaP btn-block text-center" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          TICKETS ABIERTOS (ATENCIÓN TELEFONICA)
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        <div id="div3"></div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="TituloTablaP btn-block text-center" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          TICKETS ABIERTOS (ATENCIÓN PERSONAL)
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
       <div id="div2"></div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="TituloTablaP btn-block text-center" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
          VISITAS DIARIA
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
       <div id="div1"></div>
      </div>
    </div>
  </div>
</div>
 
</form>
<script language="JavaScript">
setTimeout(function()	{
		$('#obuscar').focus(); listar_tabla(); personas();
		},500);	//document.form1.obuscar.focus;
//----------------------------
function telf(id)
	{	
	Swal.fire({
		  title: 'Cédula',
		  input: 'text',
			icon: 'info',
			inputPlaceholder: 'Ingrese su número de cédula',
		  inputAttributes: {
			maxlength: 8,
			autocapitalize: 'off'
		  },
		  showCancelButton: false,
		  confirmButtonText: 'CONTINUAR'
		}).then((result) => {
		  if (result.isConfirmed) {
//				$('#modal_lg').load('seguridad/6b_modal.php?id='+result.value+'&carnet='+id);
			  datos(result.value,2);
			}
		});	
	}
//--------------------------------------------
function cerrar_ticket(id, cedula)
	{
		var parametros = $("#form999").serialize();
		$.ajax({
		url: "dacs/1f_revisar.php?cedula="+cedula+"&id="+id,
		dataType:"json",
		type: "POST",
		data: parametros,
		success: function(data) {
		//Swal.fire(data.msg, '', data.tipo)
			Swal.fire({
			  title: data.msg,
			  icon: data.tipo,				
			  text: '',				
			  timer: 2000,				
			  timerProgressBar: true,				
			  showDenyButton: false,
			  showCancelButton: false
			})
		//	alertify.success(data.msg);
		$('#modal_largo .close').click(); 
		//--------------
		document.form1.obuscar.value='';
		document.form1.obuscar.focus;
		listar_tabla(); personas();
		}
		});
	}
//--------------------------------------------
function agregar(cedula,atencion)
	{
	if (validar_detalle()=='0')
		{
		var parametros = $("#form999").serialize();
		$.ajax({
		url: "dacs/1b_revisar.php?tabla="+cedula+"&atencion="+atencion,
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
		document.form1.obuscar.value='';
		document.form1.obuscar.focus;
		listar_tabla(); personas();
		}
		});
		}
	}
//-----------------------
function personas()
	{
	$('#personas').load('dacs/1d_personas.php');
	//$('#modal_n').load('dacs/1c_modal.php?id='+id);
	}
//-----------------------
function motivo(id)
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('dacs/1c_modal.php?id='+id);
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
					url: "dacs/1g_eliminar.php",
					type: "POST",
					data: parametros,
					success: function(r) {
						//Swal.fire('Borrado!', 'El registro fue borrado.', 'success');
//						Swal.fire({
//								  title: 'Borrado!',
//								  icon: 'success',				
//								  text: 'El registro fue borrado.',				
//								  timer: 1500,				
//								  timerProgressBar: true,				
//								  showDenyButton: false,
//								  showCancelButton: false
//								})
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
	$('#div1').load('dacs/1a_tabla.php?buscar='+cambia(document.form1.obuscar.value));
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('dacs/1c_tabla.php?buscar='+cambia(document.form1.obuscar.value));
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('dacs/1c_telf.php?buscar='+cambia(document.form1.obuscar.value));
}
//----------------------------
function cerrar(cedula, id)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('dacs/1e_modal.php?id='+id+'&cedula='+cedula);
	$('#modal_largo').modal('show');		
	}
//----------------------------
function datos(id,tipo)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('dacs/1b_modal.php?id='+id+'&tipo='+tipo);
	$('#modal_largo').modal('show');		
	}
</script>