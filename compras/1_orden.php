<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
    header("Location: ../validacion.php?opcion=val");
    exit();
}

$acceso = 19;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();">
    <div align="center" class="TituloP">Relaci&oacute;n de Presupuestos</div>
    <br>
    <div class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Agregar Presupuesto</a></div>
    <diw class="row ml-3">
        <strong>Opciones de Busqueda:</strong>
        <!--<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" >
                N&uacute;mero</label>
            </div>-->
        <div class="form-check ml-3">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="optradio" value="2" checked="checked">
                Descripcion
            </label>
        </div>
        <div class="form-check ml-3">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="optradio" value="3" onclick="buscar();">
                Pendiente
            </label>
        </div>
        <!--<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar()" >
                   Ver Todos
                </label>
            </div>-->
    </diw>
    <input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" />
    <br>
    <div id="div1"></div>
</form>
<script language="JavaScript">
    //-----------------------
    function asignar_numero(id) {
        $('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
        $('#modal_n').load('compras/1_modal.php?id=' + id);
    }
    //------------------
    function generar_solicitud(id) {
        Swal.fire({
            title: "¿Estas seguro de generar el Presupuesto?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, generar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                var parametros = "id=" + id;
                $.ajax({
                    type: 'POST',
                    url: 'compras/1j_guardar.php?id=' + document.form888.txt_nuevo.value,
                    dataType: "json",
                    data: parametros,
                    success: function(data) {
                        if (data.tipo == "info") {
                            $('#modal_normal .close').click();
                            Swal.fire("Éxito", data.msg, "success");
                            buscar();
                            //
                            window.open("compras/formatos/1_caratula.php?p=1&id=" + data.id, "_blank");
                            window.open("compras/formatos/2_presupuesto.php?p=1&id=" + data.id, "_blank");
                            window.open("compras/formatos/3_disponibilidad.php?p=1&id=" + data.id, "_blank");
                            //
                            window.open("compras/formatos/3_certificacion.php?p=1&id=" + data.id, "_blank");
                            window.open("compras/formatos/4_punto.php?p=1&id=" + data.id, "_blank");
                            window.open("compras/formatos/5_oferta.php?p=1&id=" + data.id, "_blank");
                            //
                            window.open("compras/formatos/6_adjudicacion.php?p=1&id=" + data.id, "_blank");
                            //
                            window.open("compras/formatos/7_notificacion.php?p=1&id=" + data.id, "_blank");
                        } else {
                            Swal.fire("Aviso", data.msg, "info");
                        }
                        //--------------
                    }

                });
            }
        });
    }
    //-----------------------
    function agregar() {
        $('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
        $('#modal_lg').load('compras/1b_modal.php');
    }
    //----------------
    function buscar2() {
        document.form1.optradio.value = 3;
        $('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
        $('#div1').load('compras/1a_tabla.php?valor=' + cambia(document.form1.obuscar.value) + '&tipo=3');
    }
    //----------------
    function buscar() {
        if ((document.form1.obuscar.value == "  " || document.form1.obuscar.value == " " || document.form1.obuscar.value == "") && document.form1.optradio.value < 3) {} else {
            //valor = document.form1.obuscar.value; 
            //valor = valor.replace(/ /g, '_');
            $('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
            $('#div1').load('compras/1a_tabla.php?valor=' + cambia(document.form1.obuscar.value) + '&tipo=' + document.form1.optradio.value);
        }
    }
    //---------------------
    function imprimir(id) {
        // 
        window.open("compras/formatos/1_caratula.php?p=0&id=" + id, "_blank");
        window.open("compras/formatos/2_presupuesto.php?p=0&id=" + id, "_blank");
        window.open("compras/formatos/3_disponibilidad.php?p=0&id=" + id, "_blank");
        //
        window.open("compras/formatos/3_certificacion.php?p=0&id=" + id, "_blank");
        window.open("compras/formatos/4_punto.php?p=0&id=" + id, "_blank");
        window.open("compras/formatos/5_oferta.php?p=0&id=" + id, "_blank");
        //
        window.open("compras/formatos/6_adjudicacion.php?p=0&id=" + id, "_blank");
        //
        window.open("compras/formatos/7_notificacion.php?p=0&id=" + id, "_blank");
        //window.open("compras/formatos/8_recepcion.php?p=0&id="+id,"_blank");
    }
</script>