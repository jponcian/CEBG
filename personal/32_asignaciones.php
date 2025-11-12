<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=116;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();">
<!--
    <br>
    <div class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_normal" data-keyboard="false"><i class="fas fa-plus-circle"></i> Agregar Valor CestaTickets</a></div>
-->
    <br>
    <div id="div2"></div>
</form>
<script language="JavaScript">
buscar();
//----------------
function asignar(id) {
    var parametros = $("#form1").serialize();
    $.ajax({
        type: 'POST',
        url: 'personal/32a_guardar.php?id='+id,
        dataType: "json",
        data: parametros,
        success: function(data) {
            if (data.tipo == "info") {
                alertify.success(data.msg);
                buscar();
            } else { alertify.alert(data.msg); }
        }
    });
}
//----------------
function buscar() {
    $('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
    $('#div2').load('personal/32a_tabla.php');
}
</script>