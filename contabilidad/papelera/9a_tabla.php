<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$valor = $_GET['valor'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
//-----------
$filtro1 = " AND estado_cuenta.id_banco=".$_GET['tipo1'].' ';	
//----------
$consultx = "SELECT * FROM a_cuentas WHERE id=".$_GET['tipo1']; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$_SESSION['titulo'] = 'BANCO '.$registro->banco.' '.$registro->cuenta.' ('.$registro->descripcion.')' ;

//-----------
if ($_GET['tipo2']=='1')	
	{
	$filtro2 = " AND estatus=0 ";	
	$_SESSION['titulo'] .= ' (Por Conciliar) ' ;
	} 
elseif ($_GET['tipo2']=='2')	 
	{
	$filtro2 = " AND estatus=1 ";	
	$_SESSION['titulo'] .= ' (Conciliadas) ' ;
}
	elseif ($_GET['tipo2']=='3')	 
		{	
		$filtro2 = " ";	
		//$_SESSION['titulo'] .= ' ' ;
		}
//-------------
if ($_GET['tipo']=='1')	
	{
	$filtro = " AND estatus=0 ";	
	} 
elseif ($_GET['tipo']=='2')	 
	{
	$filtro = " AND (concepto like '%".($_GET['valor'])."%' OR referencia like '%".($_GET['valor'])."%')";
	$_SESSION['titulo'] .= ' (Por Referencia '.$_GET['valor'] .')';
	}
	elseif ($_GET['tipo']=='3')	 
		{	
		$filtro = " AND monto=$valor ";	
		$_SESSION['titulo'] .= ' (Por Monto '.$_GET['valor'].')' ;
		}
		elseif ($_GET['tipo']=='4')	 
			{	
			$filtro = " AND fecha='".date('Y/m/d')."'";	
			$_SESSION['titulo'] .= ' (Dia '.date('d/m/Y').')' ;
			$fecha1 = date('Y/m/d');
			}
			elseif ($_GET['tipo']=='5')	 
				{$filtro = " AND fecha>='$fecha1' AND fecha<='$fecha2' ";
				$_SESSION['titulo'] .= ' (Desde el '.voltea_fecha($fecha1).' al '.voltea_fecha($fecha2).')' ;}
				elseif ($_GET['tipo']=='7')	 
					{$filtro = " AND fecha_conciliacion>='$fecha1' AND fecha_conciliacion<='$fecha2' ";
					$_SESSION['titulo'] .= ' (Conciliado desde el '.voltea_fecha($fecha1).' al '.voltea_fecha($fecha2).')' ;}
						elseif ($_GET['tipo']=='6')	 
						{	
						$filtro = " ";	
						}
?>		
<table class="table table-hover" width="90%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" align="center">Estado de Cuenta</td>
</tr>
<tr>	
<td align="center"><button type="button" id="boton1a" class="btn btn-lg btn-block btn-warning" onClick="sinc_op();"><i class="fas fa-search mr-2"></i>Actualizar # OP</button></td>
</tr>	
</table>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>	
<td colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Estado de Cuenta en Pdf</button></td>
</tr>	
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong># Orden Pago</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Concepto</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Referencia</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Debe</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Haber</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Saldo</strong></td>
<td bgcolor="#CCCCCC" ></td>
</tr>
<?php 	
$saldo = 0; 
$estatus = array('<div class="badge badge-warning">Por Conciliar</div>','<div class="badge badge-success">Conciliada</div>','<div class="badge badge-warning">Fecha Diferente</div>','<div class="badge badge-warning">Referencia Igual</div>','<div class="badge badge-warning">Monto Igual</div>');
//------ SALDO INICIAL
$consultx = "SELECT SUM(monto) - SUM(debe) as saldo FROM estado_cuenta WHERE fecha<'$fecha1' $filtro1;"; 
$_SESSION['saldo'] = $consultx ;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$saldo = $registro->saldo;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT	estado_cuenta.*, a_cuentas.banco, right(estado_cuenta.referencia,12) as ref FROM estado_cuenta, a_cuentas WHERE		estado_cuenta.id_banco = a_cuentas.id $filtro2 $filtro1 $filtro ORDER BY fecha DESC, ordenado DESC;"; 
//echo $consultx;
$_SESSION['consulta'] = $consultx ;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	$total += $registro->monto;
	$saldo += ($registro->monto-$registro->debe);
	if ($registro->id_orden>0)	{	$op = $registro->id_orden ; } else {	$op = '' ; }
	?>
<tr id="fila<?php echo $registro->id; ?>">

	<td><!--<div align="center" ><?php //echo ($i); ?></div>-->
	<select class="form-control" name="txt_posicion" id="txt_posicion" onChange="posicion(this.value);">
		<option value='S-<?php echo $registro->id; ?>-<?php echo $registro->ordenado; ?>'>Subir</option>';
		<option selected value='<?php echo $registro->id; ?>'><?php echo rellena_cero($i,4); ?></option>';
		<option value='B-<?php echo $registro->id; ?>-<?php echo $registro->ordenado; ?>'>Bajar</option>';
	</select>
	<!--<div align="center" ><?php //echo ($i); ?></div>--></td>
	
<td><div align="center" ><?php echo $estatus[$registro->estatus_op]; ?></div></td>
	
<td ><div align="center" ><?php if ($registro->id_orden>0) { ?><a data-toggle="tooltip" title="Ver Orden de Pago"><button type="button" class="badge badge-success" onclick="imprimir('<?php echo encriptar($op); ?>','<?php echo ($registro->tipo_orden); ?>');" ><?php echo rellena_cero($registro->numero_orden,6); ?></button></a><?php }// else {	echo $estatus[$registro->estatus_op];	} ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><?php echo ($registro->concepto); ?></div></td>
<td ><div align="right" ><a data-toggle="tooltip" title="Buscar la Referencia en las Ordenes de Pago"><button data-toggle="modal" data-target="#modal_largo" data-keyboard="false" type="button" class="btn btn-outline btn-sm" onclick="busca_op(2,'<?php echo ($registro->referencia); ?>','<?php echo ($registro->id); ?>','<?php echo ($registro->id_orden); ?>');"><strong><?php echo trim($registro->referencia); ?></strong></button></a></div></td>	
	
<td ><div align="right" ><a data-toggle="tooltip" title="Buscar Monto en las Ordenes de Pago"><button data-toggle="modal" data-target="#modal_largo" data-keyboard="false" type="button" class="btn btn-outline btn-sm" onclick="busca_op(1,'<?php echo ($registro->debe); ?>','<?php echo ($registro->id); ?>','<?php echo ($registro->id_orden); ?>');"><strong><?php echo formato_moneda($registro->debe); ?></strong></button></a></div></td>	

<td ><div align="right" ><strong><?php echo formato_moneda($registro->monto); ?></strong></div></td>	

<td ><div align="right" ><strong><?php echo formato_moneda($saldo); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>

</tr>
<?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
<script language="JavaScript">
//---------------------
function imprimir(id, tipo)
	{	
	if (tipo=="FINANCIERA")
		{	window.open("administracion/formatos/1b_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="ORDEN" || tipo=="MANUAL")
		{	window.open("administracion/formatos/1a_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/1_orden_pago.php?id="+id,"_blank");	}
	}
//---------------------
function imprimir2(id, tipo)
	{	
	if (tipo=="ORDEN" || tipo=="MANUAL")
		{	window.open("administracion/formatos/2a_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="FINANCIERA")
		{	window.open("administracion/formatos/2b_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/2_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="CHEQUE")
		{	window.open("administracion/formatos/3_cheque.php?id="+id,"_blank");	}
	}	
//----------------
function busca_op(tipo,valor,movimiento,orden)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('contabilidad/9c_modal_buscar.php?valor='+valor+'&tipo='+tipo+'&movimiento='+movimiento+'&orden='+orden);
	}
</script>