<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=107;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<div align="center" class="TituloP">PERMISOS Y/O REPOSOS</div><br>
	<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> GENERAR</a></div>

        <diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
<!--
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" >
                    Funcionario
                </label>
            </div>

            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="6" onclick="busca_empleados()" >
                    Personal con Vacaciones Vencidas</label>-->
            </div>
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" checked >
                    Funcionario</label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="busca_empleados()" >
                   Personal con Permisos Generadas
                </label>
            </div>			
        </diw>

 <input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onKeyPress="buscar2(event,this);" />

 <br>
<div id="div2"></div>
</form>
<script language="JavaScript">
//---------------------------
function vacaciones(id, tipo)
 	{
	if (tipo=='PERMISO')	{window.open("personal/formatos/13_permiso.php?id="+id,"_blank");}
		else {window.open("personal/formatos/15_reposo.php?id="+id,"_blank");}
	}
//--------------------------------------------
function historial(id)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/10c_tabla.php?id='+id);
	}
//--------------------------------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/10b_modal.php');
	}
//---------------------
function buscar2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{busca_empleados();}
	}
//---------------------------
function busca_empleados()
	{
	if(document.form1.optradio.value==1 || document.form1.optradio.value==4 || document.form1.optradio.value==6){
		$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div2').load('jefatura/10a_tabla.php?tipo='+document.form1.optradio.value+'&valor='+document.form1.obuscar.value);
			}
	}
</script>