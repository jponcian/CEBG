<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=16;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<div align="center" class="TituloP">Expedientes de los Empleados</div><br>

        <diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" >
                    Rac</label>
            </div>
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" checked="checked" >
                    Cedula</label>
            </div>
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3">
                    Nombre o Apellido
                </label>
            </div>
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" >
                    Ubicaci&oacute;n
                </label>
            </div>
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="6"  >
                    Cargo</label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="busca_empleados()" >
                   Ver Todas
                </label>
            </div>			
        </diw>

 <input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="busca_empleados()" />

 <br>
<div id="div2"></div>
</form>
<script language="JavaScript">
//---------------------------
function permiso(id, tipo)
 	{
	if (tipo=='PERMISO')
		{	window.open("personal/formatos/13_permiso.php?id="+id,"_blank");	}
	if (tipo=='VACACIONES')
		{	window.open("personal/formatos/14_vacaciones.php?id="+id,"_blank");	}
	}
//---------------------------
function recibo(id)
 	{
	window.open("personal/formatos/6_recibo.php?id="+id,"_blank");
	}
//---------------------------
function trabajo(id)
 	{
	window.open("personal/formatos/5_cons_trabajo.php?id="+id,"_blank");
	}
//---------------------------
function arc(id)
 	{
	window.open("personal/formatos/12_arc.php?id="+id,"_blank");
	}
//---------------------------
function busca_empleados()
	{
	if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value!=4){}
	else	{
		$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div2').load('personal/6a_tabla.php?tipo='+document.form1.optradio.value+'&valor='+document.form1.obuscar.value);
			}
	}
</script>