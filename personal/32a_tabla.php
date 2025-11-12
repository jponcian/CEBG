<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$modulo = decriptar($_GET['modulo']);
$cedula = decriptar($_GET['cedula']);
?>

<div class="table-responsive-sm">
<table class="formateada" border="1" align="center" >
  <tr>
    <td class="TituloTablaP" height="41" colspan="10" align="center">Horario Registrado</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Descripcion</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong></strong></td>
  </tr><?php
$consultx = "SELECT * FROM a_asignaciones"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_array())
	{
		if ($registro_x['activo']>0)
			{	$valor1 = 'checked';	$valor2 = 'no';	}
		else
			{	$valor1 = '';	$valor2 = 'si';	}
		?>
	<tr>
		<td style="vertical-align: middle"><strong><?php echo $registro_x['id']; ?></strong></td>
		<td style="vertical-align: middle"><strong><?php echo $registro_x['decripcion']; ?></strong></td>
		<td style="vertical-align: middle" align="right">

	<input onClick="asignar('<?php echo $registro_x['id']; ?>', '<?php echo $cedula; ?>', '<?php echo $valor2; ?>');" id="txt<?php echo $registro_x['id']; ?>" name="txt<?php echo $registro_x['id']; ?>" type="checkbox" class="switch_new" value="1" <?php echo $valor1; ?> />
	<label for="txt<?php echo $registro_x['id']; ?>" class="lbl_switch"></label>		
			
			
			
		</td>
    </tr>
<?php	
	}
?>
	</table>
</div>