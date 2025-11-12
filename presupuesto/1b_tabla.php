<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$anno = $_SESSION['anno'];
$categoria = $_SESSION['categoria'];
$partida = $_SESSION['partida'];
$resumen = $_SESSION['resumen'];
$largog = strlen($categoria);
$largop = strlen($partida);
$partidas_en_negativo = 'no';
$i = 0;
?>
<br>
<div class="row">
	<div class="form-group col-sm-3">
		<button type="button" id="boton" class="btn btn-danger btn-lg px-4 gap-3" onClick="imprimir();"><i class="fa-regular fa-file-pdf fa-2x"></i></button>
	</div>
</div>
<table class="formateada" width="100%" border="0" align="center">
	<!--	-->
	<thead>
		<!--
	<tr>
	<th class="TituloTablaP" height="50" colspan="14" align="center"><a onclick="generar_excel();" data-toggle="tooltip" title="Generar Archivo de Excel">EJECUCION PRESUPUESTARIA <i class="far fa-file-excel ml-1"></i></a></th>
	</tr>
-->
		<tr>
			<!--	<th bgcolor="#CCCCCC" align="center"><strong>#</strong></th>-->
			<th bgcolor="#CCCCCC" align="center"><strong>Partida</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Descripcion</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Asignación</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Credito Adicional</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Traslado Presupuestario</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Aumento (Cred. + Trasl.)</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Disminución</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Total Asignación</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Compromiso</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Causado</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Pagado</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Disponible</strong></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$consultx = "SELECT sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE left(categoria,$largog) = '$categoria' AND left(codigo,$largop) = '$partida' AND left(codigo,8) <> '00000000' ORDER BY categoria, codigo;";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		// echo $consultx;
		$registrx = $tablx->fetch_object();
		$titulo = "PRESUPUESTO TOTAL";
		if ($resumen == 1 and ($categoria) == '') {
			$titulo = 'CONSOLIDADO';
		}
		?>
		<tr bgcolor="#00FF00">
			<!--<td height="40" colspan="1" ><div align="center" ><strong>PRESUPUESTO TOTAL</strong></div></td>-->
			<td height="40">
				<div align="center"></div>
			</td>
			<td>
				<div align="center"><strong><?php echo ($titulo); 	?></strong></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registrx->original); 	?></strong></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registrx->creditos); 	?></strong></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registrx->ingreso); 	?></strong></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registrx->creditos + $registrx->ingreso); 	?></strong></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registrx->egreso); 	?></strong></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registrx->original + $registrx->creditos + $registrx->ingreso - $registrx->egreso); 	?></strong></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registrx->compromiso); 	?></strong></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registrx->causado); 		?></strong></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registrx->pagado); 	?></strong></div>
			</td>
			<td <?php if ($registrx->disponible < 0) {
					echo 'bgcolor="#FF0000"';
				} ?>>
				<div align="right"><strong><?php echo formato_moneda($registrx->disponible); 	?></strong></div>
			</td>
			<!--<td ><div align="right" ><strong><?php //echo formato_moneda(($registrx->original/12*abs(date('m')))-$registrx->compromiso); 	
													?></strong></div></td>
-->
		</tr>
		<?php
		if ($largop > 0) {
			$aux = "AND codigo IN (SELECT categoria FROM a_presupuesto_$anno WHERE left(codigo,$largop)='$partida' AND left(codigo,8) <> '00000000')";
		} else {
			$aux = "";
		}
		//----------
		//echo $resumen;
		//----------
		$consulta = "SELECT codigo, descripcion FROM a_presupuesto_$anno WHERE left(codigo,$largog)='$categoria' AND categoria IS NULL $aux AND left(codigo,8) <> '00000000' GROUP BY codigo ORDER BY categoria, codigo;";

		if ($resumen == 1) {
			$consulta = "SELECT codigo, 'RESUMEN' as descripcion FROM a_presupuesto_$anno WHERE left(codigo,8) <> '00000000' ORDER BY categoria, codigo LIMIT 1;"; //echo $consulta; 
		}
		$tabla = $_SESSION['conexionsql']->query($consulta);
		//echo $consulta;
		//---- PRIMER CICLO
		while ($registro = $tabla->fetch_object()) {
			$categoria = $registro->codigo;
			$i++;
			$j = 0;
			$consultx = "SELECT sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE categoria='$categoria' AND left(codigo,$largop)='$partida' AND left(codigo,8) <> '00000000' ORDER BY categoria, codigo;";
			if ($resumen == 1) {
				$consultx = "SELECT sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE left(codigo,$largop)='$partida' AND left(codigo,8) <> '00000000' ORDER BY categoria, codigo;";
			}
			$tablx = $_SESSION['conexionsql']->query($consultx);
			//	echo $consultx;
			$registrx = $tablx->fetch_object();
			if ($registro->descripcion <> 'RESUMEN') {
		?>
				<tr height="40" bgcolor="#FFFF00">
					<!--	<td><div align="center" ><?php //echo ($i); 
														?></div></td>-->
					<td>
						<div align="center"><?php //echo ($registro->descripcion); 
											?></div>
					</td>
					<td>
						<div align="left"><strong><?php echo ($registro->descripcion); ?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->original); 	?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->creditos); 	?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->ingreso); 	?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->creditos + $registrx->ingreso); 	?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->egreso); 	?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->original + $registrx->creditos + $registrx->ingreso - $registrx->egreso); 	?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->compromiso); 	?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->causado); 		?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->pagado); 	?></strong></div>
					</td>
					<td <?php if ($registrx->disponible < 0) {
							echo 'bgcolor="#FF0000"';
						} ?>>
						<div align="right"><strong><?php echo formato_moneda($registrx->disponible); 	?></strong></div>
					</td>
					<!--	<td ><div align="right" ><strong><?php //echo formato_moneda(($registrx->original/12*abs(date('m')))-$registrx->compromiso); 	
																?></strong></div></td>
-->
				</tr>
			<?php
			}
			$consultx = "SELECT * FROM a_presupuesto_$anno WHERE categoria='$categoria' AND left(codigo,$largop)='$partida' ORDER BY categoria, codigo;";
			if ($resumen == 1) {
				$consultx = "SELECT codigo, descripcion, sum(ingreso) as ingreso, sum(egreso) as egreso, sum(creditos) as creditos, sum(original) as original, sum(ajustado) as ajustado, sum(modificado) as modificado, sum(compromiso) as compromiso, sum(causado) as causado, sum(pagado) as pagado, sum(disponible) as disponible FROM a_presupuesto_$anno WHERE categoria IS NOT NULL AND left(codigo,$largop)='$partida' AND left(codigo,8) <> '00000000' GROUP BY codigo ORDER BY codigo;";
			}
			//	echo $consultx.'<br>';
			$tablx = $_SESSION['conexionsql']->query($consultx);
			while ($registrx = $tablx->fetch_object()) {
				$j++;
			?>
				<tr>
					<!--	<td><div align="center" ><?php //echo ($j); 
														?></div></td>-->
					<td>
						<div align="left"><strong><?php echo formato_partida2($registrx->codigo); 			?></strong></div>
					</td>
					<td>
						<div align="left"><strong><?php echo ($registrx->descripcion); 		?></strong></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->original); 	?></strong></div>
					</td>
					<td>
						<div align="right"><?php echo formato_moneda($registrx->creditos); 	?></div>
					</td>
					<td>
						<div align="right"><?php echo formato_moneda($registrx->ingreso); 	?></div>
					</td>
					<td>
						<div align="right"><?php echo formato_moneda($registrx->creditos + $registrx->ingreso); 	?></div>
					</td>
					<td>
						<div align="right"><?php echo formato_moneda($registrx->egreso); 	?></div>
					</td>
					<td>
						<div align="right"><strong><?php echo formato_moneda($registrx->original + $registrx->creditos + $registrx->ingreso - $registrx->egreso); 	?></strong></div>
					</td>
					<td>
						<div align="right"><?php echo formato_moneda($registrx->compromiso); 	?></div>
					</td>
					<td>
						<div align="right"><?php echo formato_moneda($registrx->causado); 	?></div>
					</td>
					<td>
						<div align="right"><?php echo formato_moneda($registrx->pagado); 		?></div>
					</td>
					<td <?php if ($registrx->disponible < 0) {
							echo 'bgcolor="#FF0000"';
							$partidas_en_negativo = 'si';
						} ?>>
						<div align="right"><strong><?php echo formato_moneda($registrx->disponible); ?></strong></div>
					</td>
					<!--	<td ><div align="right" ><strong><?php //echo formato_moneda(($registrx->original/12*abs(date('m')))-$registrx->compromiso); 	
																?></strong></div></td>
-->
				</tr>
		<?php
			}
		}
		//---- FIN
		?>
		<!--
 <tr>
<td colspan="14" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
-->
	</tbody>
</table>
<?php if ($partidas_en_negativo == 'si') { ?><script language="JavaScript">
		Swal.fire({
			//		  title: 'Informacion!',
			icon: 'info',
			text: 'Existen Partidas con Saldo Negativo...',
			timer: 3500,
			//		  timerProgressBar: true,				
			showDenyButton: false,
			showCancelButton: false
		})
	</script><?php } ?>
<!--
<script language="JavaScript" >$(document).ready(function() {
    $('#hola').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]
    } );
} );</script>
-->
<script type="text/javascript" src="funciones/datatable_h.js"></script>