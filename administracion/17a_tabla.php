<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
//$institutos = $_GET['institutos'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
$cuenta = ($_GET['cuenta']);
$anno = ($_GET['anno']);
$tabla_adicional = "";
//---------
if ($cuenta==0)
	{
	$filtrar_f = " YEAR(ordenes_pago.fecha)=$anno AND ";
	$_SESSION['titulo'] = "";
	$campos='';
	}
else
	{
	$consultx = "SELECT * FROM a_cuentas WHERE id=$cuenta;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro_x = $tablx->fetch_object();
	$banco = mayuscula($registro_x->banco);
	$cuenta = mayuscula($registro_x->cuenta);
	$_SESSION['filtro'] = "FILTRO: POR CTA PAGADORA $banco $cuenta";
	$filtrar_f = " ordenes_pago.id = ordenes_pago_pagos.id_orden AND ordenes_pago_pagos.banco='$banco' and ordenes_pago_pagos.cuenta='$cuenta' AND YEAR(ordenes_pago.fecha)=$anno AND ";
	$tabla_adicional = ", ordenes_pago_pagos";
	$campos = ' ordenes_pago_pagos.monto, 
	ordenes_pago_pagos.tipo_pago, 
	ordenes_pago_pagos.banco, 
	ordenes_pago_pagos.cuenta, 
	ordenes_pago_pagos.chequera, 
	ordenes_pago_pagos.num_pago, 
	ordenes_pago_pagos.fecha_pago, ';
	}
//---------

switch ($filtro) {
    case 1:
        $filtrar = " AND ordenes_pago.estatus<>99 AND ordenes_pago.tipo_solicitud='FINANCIERA' AND ordenes_pago.numero = '$dato_buscar' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.numero DESC";
        $_SESSION['titulo'] = "POR CONSULTA (numero: $dato_buscar)";
		break;
    case 2:
        $filtrar = " AND ordenes_pago.estatus<>99 AND ordenes_pago.tipo_solicitud='FINANCIERA' AND ordenes_pago.descripcion LIKE '%$dato_buscar%' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.numero DESC";
        $_SESSION['titulo'] = "POR CONSULTA (descripcion: $dato_buscar)";
		break;
    case 3:
        $filtrar = " AND ordenes_pago.estatus<>99 AND ordenes_pago.tipo_solicitud='FINANCIERA' AND ordenes_pago.fecha >= '$fecha1' AND ordenes_pago.fecha <= '$fecha2' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.numero";
        $_SESSION['titulo'] = "POR FECHA (desde el ".voltea_fecha($fecha1)." al ".voltea_fecha($fecha2).")";
		break;
    case 4:
        $filtrar = " AND ordenes_pago.estatus<>99 AND ordenes_pago.tipo_solicitud='FINANCIERA' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.numero";
        $_SESSION['titulo'] = "TODAS";
		break;
    case 5:
        $filtrar = " AND ordenes_pago.estatus<>99 AND ordenes_pago.tipo_solicitud='FINANCIERA' AND (contribuyente.rif LIKE '%$dato_buscar%' or contribuyente.nombre LIKE '%$dato_buscar%') GROUP BY ordenes_pago.id ORDER BY ordenes_pago.numero ";
        $_SESSION['titulo'] = "POR CONSULTA (contribuyente: $dato_buscar)";
		break;
    case 6:
        $filtrar = " AND ordenes_pago.estatus<>99 AND ordenes_pago.tipo_solicitud='FINANCIERA' AND ordenes_pago.fecha = '".date('Y/m/d')."' GROUP BY ordenes_pago.id ORDER BY ordenes_pago.numero";
        $_SESSION['titulo'] = "POR CONSULTA (dia actual: ".date('d/m/Y').")";
		break;
    case 7:
        $filtrar = " AND tipo_solicitud='FINANCIERA' AND ordenes_pago.estatus=99 GROUP BY ordenes_pago.id ORDER BY ordenes_pago.numero";
        $_SESSION['titulo'] = "POR CONSULTA (Anuladas)";
		break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Orden Financiera en Sistema</td>
</tr>
<tr>
<td colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" align="center"><strong>N</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Rif</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Contribuyente</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Orden</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Concepto</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT $campos ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.tipo_solicitud, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.total, ordenes_pago.estatus, contribuyente.rif, contribuyente.nombre FROM ordenes_pago , contribuyente $tabla_adicional WHERE $filtrar_f (ordenes_pago.estatus>=0) AND contribuyente.id = ordenes_pago.id_contribuyente $filtrar;";
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	if ($registro->descripcion=='A.P.A.R.T.A.D.A')	{$descripcion=$registro->descripcion;}	
		elseif ($registro->estatus==99)	{$descripcion='A.N.U.L.A.D.A';}	
			else	{$descripcion=$registro->descripcion;}
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->rif); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><strong><?php echo rellena_cero($registro->numero,8); ?></strong></div></td>
<td ><div align="left" ><?php echo ($descripcion); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Orden"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->id); ?>','<?php echo ($registro->tipo_solicitud); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Comprobante de Pago"><button type="button" class="btn btn-outline-success waves-effect" onclick="imprimir2('<?php echo encriptar($registro->id); ?>','<?php echo ($registro->tipo_solicitud); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->tipo_pago==1) { ?><a data-toggle="tooltip" title="Cheque"><button type="button" class="btn btn-outline-success waves-effect" onclick="imprimir2('<?php echo encriptar($registro->id); ?>', 'CHEQUE');" ><i class="fas fa-money-check-alt mr-1"></i></button></a><?php } ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>