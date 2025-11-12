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
<form id="form999" name="form999" method="post">
    <!-- Modal Header -->
    <div class="modal-header bg-fondo text-center">
        <input type="hidden" id="oid" name="oid" value="0" />
        <input type="hidden" id="txt_iva" name="txt_iva" value="0" />
        <input type="hidden" id="txt_iva1" name="txt_iva1" value="0" />
        <input type="hidden" id="txt_total" name="txt_total" value="" />
        <h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nuevo Presupuesto
            <button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button>
        </h4>
    </div>
    <!-- Modal body -->
    <div class="p-1">

        <div class="row">
            <div class="form-group col-sm-12">
                <div class="input-group">
                    <div class="input-group-text">Proveedor</div>
                    <select class="select2" style="width: 600px" multiple="multiple" placeholder="Seleccione el(los) Proveedor(es)" name="txt_rif[]" id="txt_rif" onchange="buscar_orden();">
                        <?php
                        $consultx = "SELECT id, rif, nombre FROM contribuyente ORDER BY nombre";
                        $tablx = $_SESSION['conexionsql']->query($consultx);
                        while ($registro_x = $tablx->fetch_array()) {
                            echo '<option value=' . $registro_x['id'] . '/' . $registro_x['rif'];
                            //if ($id_categoria==$registro_x['id']) {echo ' selected="selected" ';}
                            echo '>' . $registro_x['rif'] . ' - ' . $registro_x['nombre'] . '</option>';
                        }
                        ?></select>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="form-group col-sm-8 ml-0">
                <select class="select2" name="txt_area" id="txt_area" style="width: 600px">
                    <option value="0">Seleccione el Area Solicitante</option>
                    <?php
                    //--------------------
                    $consultx = "SELECT * FROM a_areas WHERE id_direccion > 0 AND id <> 42 ORDER BY area;";
                    $tablx = $_SESSION['conexionsql']->query($consultx);
                    while ($registro_x = $tablx->fetch_object())
                    //-------------
                    {
                        echo '<option ';
                        echo ' value="';
                        echo $registro_x->id;
                        echo '">';
                        echo ($registro_x->area);
                        echo '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="form-group col-sm-4">
            <div class="input-group">
                <div class="input-group-text"><i class="fas fa-file-invoice"></i></div>
                <input onkeyup="saltar(event,'txt_area');" type="text" style="text-align:center" class="form-control " name="txt_memo" id="txt_memo" placeholder="Memo Solicitud" minlength="1" maxlength="20" required>
            </div>
        </div>

        <div class="form-group col-sm-4">
            <div class="input-group">
                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                <input onkeyup="saltar(event,'txt_fecha')" type="text" style="text-align:center" class="form-control " name="txt_fechas" id="txt_fechas" placeholder="Fecha Memo" minlength="1" maxlength="10" value="<?php echo date('d/m/Y'); ?>" required>
            </div>
        </div>

        <div class="form-group col-sm-4">
            <div class="input-group">
                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                <input onkeyup="saltar(event,'txt_concepto')" type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha Presupuesto" minlength="1" maxlength="10" onchange="combo0(this.value);" value="<?php echo date('d/m/Y'); ?>" required>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="form-group col-sm-12">
            <textarea id="txt_concepto" name="txt_concepto" placeholder="Escribe aqui el Concepto" class="form-control" rows="4"></textarea>
        </div>
    </div>
    <div class="row">

        <div class="form-group col-sm-5">
            <select class="select2" style="width: 320px" name="txt_categoria" id="txt_categoria" onchange="combo(this.value);">
                <option value="0">Seleccione la Actividad</option>
            </select>
        </div>

        <div class="form-group col-sm-7">
            <select class="select2" style="width: 430px" name="txt_partida" id="txt_partida" onchange="cargar_iva();">
                <option value="0">Espere miestras se cargan las partidas...</option>
            </select>
        </div>
    </div>

    <table width="100%" border="1">
        <tr>
            <th scope="col"><input onkeydown="puro_numero('txt_cantidad');" onkeyup="saltar(event,'txt_detalle')" id="txt_cantidad" name="txt_cantidad" placeholder="Cant" class="form-control" type="text" style="text-align:center" /></th>
            <th width="55%" scope="col"><input onkeyup="saltar(event,'txt_medida')" id="txt_detalle" name="txt_detalle" placeholder="Detalle" class="form-control" type="text" style="text-align:center" /></th>
            <th width="12%" scope="col"><input onkeyup="saltar(event,'txt_precio')" id="txt_medida" name="txt_medida" placeholder="Medida" class="form-control" type="text" style="text-align:center" /></th>
            <th width="14%" scope="col"><input onkeyup="guardar_detalle2(event)" id="txt_precio" name="txt_precio" placeholder="Precio" class="form-control" type="text" style="text-align:center" /></th>
            <th scope="col">Exento<input id="txt_exento" name="txt_exento" type="checkbox" class="switch_new" value="1" /><label for="txt_exento" class="lbl_switch"></label></th>
        </tr>
    </table>

    <br>
    <div align="center">
        <button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_detalle(0)"><i class="fas fa-save prefix grey-text mr-1"></i> Agregar Detalle</button>
    </div>

    </div>
    <!-- Modal footer -->
    <div class="modal-footer justify-content-center">
        <div align="center" id="div3">

        </div>
    </div>

</form>
<script language="JavaScript">
    // PARA EL SELECT2
    $(document).ready(function() {
        $('.select2').select2({
            maximumSelectionLength: 5
        });
        setTimeout(function() {
            $('#txt_rif').focus();
        }, 1000)
        $("#txt_fecha").datepicker();
        $("#txt_fechas").datepicker();
        combo0('<?php echo date('d/m/Y'); ?>');
    });
    //----------------- PARA VALIDAR
    function validar_detalle() {
        error = 0;
        if (document.form999.txt_rif.value == "" || document.form999.txt_rif.value == "0") {
            Swal.fire("Debe Indicar el Rif");
            error = 1;
        }
        if (document.form999.txt_concepto.value == "") {
            document.form999.txt_concepto.focus();
            Swal.fire("Debe Indicar el Concepto");
            error = 1;
        }
        if (document.form999.txt_partida.value == "0") {
            document.form999.txt_partida.focus();
            Swal.fire("Debe Seleccionar la Partida");
            error = 1;
        }
        if (document.form999.txt_categoria.value == "0") {
            document.form999.txt_categoria.focus();
            Swal.fire("Debe Seleccionar la Categoria");
            error = 1;
        }
        if (document.form999.txt_cantidad.value == "") {
            document.form999.txt_cantidad.focus();
            Swal.fire("Debe Indicar la Cantidad");
            error = 1;
        }
        if (document.form999.txt_medida.value == "") {
            document.form999.txt_medida.focus();
            Swal.fire("Debe Indicar la Unidad de Medida");
            error = 1;
        }
        //    if (document.form999.txt_detalle.value == "") { document.form999.txt_detalle.focus();
        //        Swal.fire("Debe Indicar la Descripcion");
        //        error = 1; }
        if (document.form999.txt_precio.value == "") {
            document.form999.txt_precio.focus();
            Swal.fire("Debe Indicar el Precio Unitario");
            error = 1;
        }
        return error;
    }
    //--------------------------- PARA GUARDAR
    function guardar_detalle() {
        if (validar_detalle() == 0) {
            $('#boton').hide();
            var parametros = $("#form999").serialize();
            $.ajax({
                type: 'POST',
                url: 'compras/1e_guardar.php',
                dataType: "json",
                data: parametros,
                success: function(data) {
                    if (data.tipo == "info") {
                        Swal.fire("Éxito", data.msg, "success");
                        tabla(data.id);
                        document.form999.txt_cantidad.value = '';
                        document.form999.txt_detalle.value = '';
                        document.form999.txt_medida.value = '';
                        document.form999.txt_precio.value = '';
                        document.form999.txt_exento.checked = 0;
                        combo(document.form999.txt_categoria.value);
                        document.form999.txt_cantidad.focus();
                        $('#boton').show();
                    } else {
                        Swal.fire("Aviso", data.msg, "info");
                    }
                }
            });
        }
    }
    //-------------
    function combo0(fecha) {
        $.ajax({
            type: "POST",
            url: 'compras/1f_combo.php?fecha=' + fecha,
            success: function(resp) {
                $('#txt_categoria').html(resp);
            }
        });
    }
    //-------------
    function combo(categoria) {
        var parametros = $("#form999").serialize();
        $.ajax({
            type: 'POST',
            url: 'compras/1c_combo.php?categoria=' + categoria + '&partida=0&fecha=' + document.form999.txt_fecha.value,
            //dataType: "json",
            //data: parametros,
            success: function(resp) {
                $('#txt_partida').html(resp);
            }
        });
    }
    //-------------
    function direccion_from_area(oficina) {
        $.ajax({
            type: "POST",
            url: 'compras/1g_combo.php?oficina=' + oficina,
            success: function(resp) {
                $('#txt_area').html(resp);
            }
        });
    }
    //------------------------------ PARA ELIMINAR
    function eliminar(id, id_cont) {
        Swal.fire({
            title: "¿Estas seguro de eliminar el Registro?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                var parametros = "id=" + id;
                $.ajax({
                    url: "compras/1h_eliminar.php",
                    type: "POST",
                    data: parametros,
                    success: function(r) {
                        Swal.fire("Registro Eliminado Correctamente", "", "success");
                        tabla(id_cont);
                    }
                });
            }
        });
    }
    //--------------------- PARA BUSCAR
    function tabla(id) {
        $('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
        $('#div3').load('compras/1d_tabla.php?id=' + id);
    }
    //--------------------- PARA BUSCAR
    function buscar_orden() {
        var parametros = $("#form999").serialize();
        $.ajax({
            type: 'POST',
            url: 'compras/1i_buscar.php',
            data: parametros,
            dataType: "json",
            success: function(data) {
                if (data.tipo == "alerta") {
                    alertify.alert(data.msg);
                } else {
                    document.form999.txt_fecha.value = data.fecha_factura;
                    document.form999.txt_memo.value = data.memo;
                    document.form999.txt_fechas.value = data.fecha_memo;
                    document.form999.txt_concepto.value = data.concepto;
                    direccion_from_area(data.oficina);
                    document.form999.txt_memo.focus();
                    tabla(data.id_rif);
                }
            }
        });
    }
    //----------------- PARA VALIDAR
    function cargar_iva() {
        if (
            document.form999.txt_partida.value == "403180100000" ||
            document.form999.txt_partida.value == "403180100" ||
            document.form999.txt_partida.value == "403.18.01.00." ||
            document.form999.txt_partida.value == "403.18.01.00.000" ||
            document.form999.txt_partida.value == "403.180.100.000" ||
            document.form999.txt_partida.value == "403.180.100.001"
        ) {
            Swal.fire({
                title: "Ingrese el porcentaje del Impuesto al Valor Agregado",
                input: "text",
                inputValue: "16",
                showCancelButton: true,
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    var valor = result.value;
                    document.form999.txt_cantidad.value = 1;
                    document.form999.txt_detalle.value = 'IMPUESTO AL VALOR AGREGADO';
                    document.form999.txt_medida.value = 'IVA';
                    document.form999.txt_iva.value = valor;
                    document.form999.txt_precio.value = number_format(document.form999.txt_total.value * valor / 100, 2);
                    setTimeout(function() {
                        $('#txt_precio').focus();
                    }, 500);
                }
            });
        }
    }
    //--------------------------------
    $("#txt_precio").on({
        "focus": function(event) {
            $(event.target).select();
        },
        "keyup": function(event) {
            $(event.target).val(function(index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
            });
        }
    });
</script>