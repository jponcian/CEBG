<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);

//---------
switch ($filtro) {
    case 5: //visitante
		$filtro = " AND (dacs_atencion.cedula like '%$buscar%' OR dacs_atencion.organismo like '%$buscar%' OR rac_visita.nombre like '%$buscar%') ";	//$_SESSION['titulo'] = 'POR VERIFICAR'; OR dacs_atencion.tipo like '%$buscar%' 
        break;
    case 6: // dia actual
		$filtro = " AND (fecha='".date('Y/m/d')."') ";	//$_SESSION['titulo'] = 'POR VERIFICAR';
       break;
    case 3: // por fecha
		$filtro = " AND (fecha>='$fecha1' AND fecha<='$fecha2') ";	//$_SESSION['titulo'] = 'POR VERIFICAR';
        break;
    case 4: // todos
		$filtro = "";	//$_SESSION['titulo'] = 'POR VERIFICAR';
        break;
}
?>
<table class="table table-striped table-hover" bgcolor="#FFFFFF" width="100%" border="0" align="center">

<tr>
<td class="TituloTablaP" colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fa-regular fa-file-pdf"></i> Ver Pdf</button></td>
</tr>
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Historial de Visitas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="left"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Cedula</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Nombre y Apellido</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Sexo</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Organismo</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Cargo</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Telefono</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Motivo</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Ingreso</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Salida</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong></strong></td>-->
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT
	dacs_atencion.id, 
	dacs_atencion.tipo, 
	dacs_atencion.fecha, 
	dacs_atencion.cedula, 
	dacs_atencion.telefono, 
	dacs_atencion.organismo, 
	dacs_atencion.cargo, 
	dacs_atencion.edad, 
	dacs_atencion.comienzo, 
	dacs_atencion.usuario_comienzo, 
	dacs_atencion.fin, 
	dacs_atencion.usuario_fin, 
	dacs_atencion.observacion,
	rac_visita.nombre,
	rac_visita.sexo,
	rac_visita.correo
FROM
	dacs_atencion,
	rac_visita
WHERE
	dacs_atencion.cedula = rac_visita.cedula $filtro ORDER BY fecha DESC, comienzo DESC;";
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++; $j=0;
	?>
<tr >
	<td><div align="center" ><?php echo ($i); ?></div><?php //if ($registro->observacion<>'') { ?>
	<!--	<div class="spinner-grow spinner-grow-sm" role="status"></div><?php //} ?></td>-->
	<td ><div align="left" ><?php echo ($registro->cedula); ?></div></td>
	<td ><div align="left" ><strong><?php echo ($registro->nombre); ?></strong></div></td>
	<td ><div align="left" ><?php echo ($registro->sexo); ?></div></td>
	<td ><div align="left" ><?php echo ($registro->organismo); ?></div></td>
	<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
	<td ><div align="left" ><?php echo ($registro->telefono); ?></div></td>
	<td ><div align="left" ><?php
	$consultx1 = "SELECT descripcion FROM dacs_atencion_gestion WHERE id_tickets = '". $registro->id."' ORDER BY id_atencion;";
	$tablx1 = $_SESSION['conexionsql']->query($consultx1);
	while ($registro1 = $tablx1->fetch_object())
	{ $j++; if ($j>1) {echo '</br>';} echo ($registro1->descripcion);} ?></div></td>
	<td ><div align="left" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
	<!--<td ><div align="left" ><?php //echo voltea_fecha($registro->fecha); ?></div></td>-->
	<td ><div align="left" ><?php echo hora_militar($registro->comienzo); ?></div></td>
	<td ><div align="left" ><?php echo hora_militar($registro->fin); ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>