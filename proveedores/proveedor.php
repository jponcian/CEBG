<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
    header("Location: ../validacion.php?opcion=val");
    exit();
}

$acceso = 48;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();"><br>
    <div class="text-right mb-3">
        <a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar(0);" data-toggle="modal" data-target="#modal_normal" data-keyboard="false">
            <i class="fas fa-plus-circle"></i> Agregar Proveedor
        </a>
    </div>

    <input placeholder="Escriba aquí la información a buscar..." name="obuscar" id="obuscar" type="text" size="100" class="form-control" onFocus="this.select()" />

    <br>
    <div id="div2"></div>
    <br>
</form>
<script language="JavaScript">
    //---------------------
    buscar();
    //---------------------
    function buscar() {
        $('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
        $('#div2').load('proveedores/1a_tabla.php');
    }
    //--------------------------------------------
    function agregar(id) {
        $('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
        $('#modal_n').load('proveedores/1b_modal.php?id=' + id);
    }
</script>