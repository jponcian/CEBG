<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$categoria = $_GET['id'];
$id_cont = $_GET['id_cont'];
$fecha = anno(voltea_fecha($_GET['fecha']));
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Partida:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Monto:</strong></td>
</tr>
<?php 	
	$i=0;
//------ MONTAJE DE LOS DATOS
$consultx = "DROP TABLE IF EXISTS aux;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------
$consultx = "CREATE TEMPORARY TABLE aux (SELECT * FROM orden WHERE orden.id_contribuyente = $id_cont AND orden.estatus = 0 AND orden.tipo_orden = 3);"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------
$consultx = "SELECT	a_presupuesto_$fecha.id, a_presupuesto_$fecha.codigo, a_presupuesto_$fecha.categoria, a_presupuesto_$fecha.descripcion, aux.total FROM a_presupuesto_$fecha LEFT JOIN aux ON a_presupuesto_$fecha.codigo = aux.partida WHERE a_presupuesto_$fecha.categoria = '$categoria' ORDER BY codigo ASC";
$tablx = $_SESSION['conexionsql']->query($consultx);
//echo $consultx;

while ($registro = $tablx->fetch_object())
	{
	$i++;
	$total = $total + ($registro->total/1000000);
	$monto=formato_moneda(($registro->total/1000000)); 
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><strong><?php echo formato_partida($registro->codigo); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="right"><input style="text-align:right" class="form-control" onchange="sumar();" onkeyup="saltar(event,'campo<?php echo ($i+1); ?>')" value="<?php echo ($monto); ?>" id="campo<?php echo ($i); ?>" name="<?php echo ($registro->id); ?>" type="text" /></div></td>
</tr>
 <?php 
 }
 ?>
<tr >
<td bgcolor="#CCCCCC"  colspan="7" ><div align="right" ><strong>Total de la Orden => <span id="spTotal"><?php echo formato_moneda($total); ?></span></strong></div></td>
</tr>
</table>
<br>
<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_solicitud()" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>	
<script language="JavaScript">
//---------------------------
function sumar()
	{
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'administracion/18c_sumar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	document.getElementById('spTotal').innerHTML = (data.total);	alertify.success(data.total);	}
			//--------------
			} 
		 
		});
	}
//---------------------------
<?php 	
	$i=0;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT id FROM a_presupuesto_$fecha WHERE categoria = '$categoria' ORDER BY codigo;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
{
	$i++;
?>
$("#campo<?php echo ($i); ?>").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    }
});
<?php
}
?>
</script>
