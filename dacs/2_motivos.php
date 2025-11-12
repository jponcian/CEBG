<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=64;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();">
    <br>
    <div class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_normal" data-keyboard="false"><i class="fas fa-plus-circle"></i> Agregar Registro</a></div>
    <br>
     <input placeholder="Escriba aqui la informacion a buscar..." name="obuscar" id="obuscar" type="text" size="100" class="form-control" />
    <br>
	<div id="div2"></div>
</form>
<script language="JavaScript">
buscar();
//----------------
function eliminar(id) {
    alertify.confirm("Estas seguro de eliminar el Registro?",
        function() {
            var parametros = "id=" + id;
            $.ajax({
                url: "dacs/2c_eliminar.php",
                type: "POST",
                data: parametros,
                success: function(r) {
                    alertify.success('Registro Procesado Correctamente');
                    //--------------
                    buscar();
                }
            });
        });
}
//----------------
function guardar(tipo) {
    var parametros = $("#form999").serialize();
    $.ajax({
        type: 'POST',
        url: 'dacs/2d_guardar.php',
        dataType: "json",
        data: parametros,
        success: function(data) {
            if (data.tipo == "info") {
                alertify.success(data.msg);
                $('#modal_normal .close').click();
                buscar();
            } else { alertify.alert(data.msg); }
        }
    });
}
//----------------
function agregar() {
    $('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
    $('#modal_n').load('dacs/2b_modal.php');
}
//----------------
function buscar() {
    $('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
    $('#div2').load('dacs/2a_tabla.php');
}
</script>