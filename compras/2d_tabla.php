<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$id_cont = $_GET['id'];
$iva = 0;
?>
<table class="table table-hover" width="100%" border="0" align="center">
    <tr>
        <td bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
        <td bgcolor="#CCCCCC" align="center"><strong>Factura:</strong></td>
        <td bgcolor="#CCCCCC" align="center"><strong>Fecha:</strong></td>
        <td bgcolor="#CCCCCC" align="center"><strong>Ejecucion:</strong></td>
        <td bgcolor="#CCCCCC" align="center"><strong>Cant:</strong></td>
        <td bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
        <td bgcolor="#CCCCCC" align="right"><strong>Unidad Medida</strong></td>
        <td bgcolor="#CCCCCC" align="right"><strong>Precio:</strong></td>
        <td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
        <td colspan="2" bgcolor="#CCCCCC" align="center"></td>
    </tr>
    <?php
    //------ MONTAJE DE LOS DATOS
    $consultx = "SELECT id, categoria, partida, cantidad, descripcion, precio_uni, total, exento, porcentaje_iva, factura, fecha_factura, medida FROM orden WHERE id_contribuyente=$id_cont AND estatus=0 ORDER BY id;";
    //echo $consultx;
    $tablx = $_SESSION['conexionsql']->query($consultx);

    while ($registro = $tablx->fetch_object()) {
        if (substr($registro->partida, 0, 7) == '4031801') {
            $iva = $registro->porcentaje_iva;
        }
        $i++;
        $total = $total + $registro->total;
        if ($registro->exento == 0) {
            $base = $base + $registro->total;
            $precio = formato_moneda($registro->precio_uni);
            $monto = formato_moneda($registro->total);
        } else {
            $precio = formato_moneda($registro->precio_uni) . '(e)';
            $monto = formato_moneda($registro->total) . '(e)';
        }
    ?>
        <tr>
            <td>
                <div align="center">
                    <?php echo ($i); ?>
                </div>
            </td>
            <td>
                <div align="right">
                    <input id="txt_factura<?php echo ($registro->id); ?>" placeholder="Factura" value="<?php echo ($registro->factura); ?>" name="txt_factura<?php echo ($registro->id); ?>" class="form-control" type="text" style="text-align:center" />
                </div>
            </td>
            <td>
                <div align="right">
                    <input size="60" type="text" style="text-align:center" class="form-control " name="txt_fecha<?php echo ($registro->id); ?>" id="txt_fecha<?php echo ($registro->id); ?>" placeholder="Fecha Factura" minlength="1" maxlength="10" value="<?php echo voltea_fecha($registro->fecha_factura); ?>" required>
                </div>
            </td>
            <td>
                <div align="left">
                    <?php echo ($registro->categoria . '-' . $registro->partida); ?>
                </div>
            </td>
            <td>
                <div align="center">
                    <input id="txt_cant<?php echo ($registro->id); ?>" placeholder="Cantidad" value="<?php echo ($registro->cantidad); ?>" name="txt_cant<?php echo ($registro->id); ?>" class="form-control" type="text" style="text-align:center" />
                </div>
            </td>
            <td>
                <div align="left">
                    <?php echo ($registro->descripcion); ?>
                </div>
            </td>
            <td>
                <div align="center">
                    <input id="txt_medida<?php echo ($registro->id); ?>" placeholder="Medida" value="<?php echo ($registro->medida); ?>" name="txt_medida<?php echo ($registro->id); ?>" class="form-control" type="text" style="text-align:center" />
                </div>
            </td>
            <td>
                <div align="right">
                    <input size="60" id="txt_precio<?php echo ($registro->id); ?>" placeholder="Precio" value="<?php echo formato_moneda($registro->precio_uni); ?>" name="txt_precio<?php echo ($registro->id); ?>" class="form-control" type="text" style="text-align:center" />
                </div>
            </td>
            <td>
                <div align="right">
                    <?php echo $monto; ?>
                </div>
            </td>
            <td>
                <div align="center"><a data-toggle="tooltip" title="Modificar"><button type="button" class="btn btn-outline-success btn-sm" onclick="modificar('<?php echo encriptar($registro->id); ?>');"><i class="fa-solid fa-floppy-disk"></i></button></a></div>

                <div align="center"><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div>
            </td>
        </tr>
    <?php
    }
    ?>
    <tr>
        <td bgcolor="#CCCCCC" colspan="11">
            <div align="right"><strong>Total de la Orden =>
                    <?php echo formato_moneda($total); ?></strong></div>
        </td>
    </tr>
</table>
<script language="JavaScript">
    <?php
    $consultx = "SELECT id FROM orden WHERE id_contribuyente=$id_cont AND estatus=0 ORDER BY id;";
    $tablx = $_SESSION['conexionsql']->query($consultx);
    while ($registro = $tablx->fetch_object()) {
    ?>$("#txt_fecha<?php echo $registro->id; ?>").datepicker();
    <?php
    ?>$("#txt_precio<?php echo $registro->id; ?>").on({
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
    <?php
    }
    ?>
    document.form999.txt_total.value = "<?php echo ($base); ?>";
    document.form999.txt_iva1.value = "<?php echo ($iva); ?>";
</script>